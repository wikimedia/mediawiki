<?php
/** Occitan (Occitan)
 *
 * @addtogroup Language
 *
 * @author Cedric31
 * @author Nike
 * @author G - ג
 * @author Горан Анђелковић
 * @author Spacebirdy
 * @author SPQRobin
 */

$skinNames = array(
	'standard' => 'Normal',
	'nostalgia' => 'Nostalgia',
	'cologneblue' => 'Còlonha Blau',
);

$bookstoreList = array(
	'Amazon.fr' => 'http://www.amazon.fr/exec/obidos/ISBN=$1'
);

$namespaceNames = array(
	NS_SPECIAL        => 'Especial',
	NS_MAIN           => '',
	NS_TALK           => 'Discutir',
	NS_USER           => 'Utilizaire',
	NS_USER_TALK      => 'Discussion_Utilizaire',
	# NS_PROJECT set by $wgMetaNamespace
	NS_PROJECT_TALK   => 'Discussion_$1',
	NS_IMAGE          => 'Imatge',
	NS_IMAGE_TALK     => 'Discussion_Imatge',
	NS_MEDIAWIKI      => 'MediaWiki',
	NS_MEDIAWIKI_TALK => 'Discussion_MediaWiki',
	NS_TEMPLATE       => 'Modèl',
	NS_TEMPLATE_TALK  => 'Discussion_Modèl',
	NS_HELP           => 'Ajuda',
	NS_HELP_TALK      => 'Discussion_Ajuda',
	NS_CATEGORY       => 'Categoria',
	NS_CATEGORY_TALK  => 'Discussion_Categoria',
);
$namespaceAliases = array(
	'Utilisator'            => NS_USER,
	'Discussion_Utilisator' => NS_USER_TALK,
	'Discutida_Utilisator' => NS_USER_TALK,
	//'Discutida_$1'         => NS_PROJECT_TALK, /// @fixme
	'Discutida_Imatge'     => NS_IMAGE_TALK,
	'Mediaòiqui'           => NS_MEDIAWIKI,
	'Discussion_Mediaòiqui' => NS_MEDIAWIKI_TALK,
	'Discutida_Mediaòiqui' => NS_MEDIAWIKI_TALK,
	'Discutida_Modèl'      => NS_TEMPLATE_TALK,
	'Discutida_Ajuda'      => NS_HELP_TALK,
	'Discutida_Categoria'  => NS_CATEGORY_TALK,
);
$linkTrail = "/^([a-zàâçéèêîôû]+)(.*)\$/sDu";

$dateFormats = array(
	'mdy time' => 'H:i',
	'mdy date' => 'M j, Y',
	'mdy both' => 'M j, Y "a" H:i',

	'dmy time' => 'H:i',
	'dmy date' => 'j M Y',
	'dmy both' => 'j M Y "a" H:i',

	'ymd time' => 'H:i',
	'ymd date' => 'Y M j',
	'ymd both' => 'Y M j "a" H:i',
);

$separatorTransformTable = array( ',' => "\xc2\xa0", '.' => ',' );

$messages = array(
# User preference toggles
'tog-underline'               => 'Soslinhar los ligams :',
'tog-highlightbroken'         => 'Los ligams suls subjèctes non creats aparéisson en roge',
'tog-justify'                 => 'Paragrafs justificats',
'tog-hideminor'               => 'Amagar los <i>Darrièrs cambiaments</i> menors',
'tog-extendwatchlist'         => 'Lista de seguit melhorada',
'tog-usenewrc'                => 'Darrièrs cambiaments melhorats<br /> (pas per totes los navegaires)',
'tog-numberheadings'          => 'Numerotacion automatica dels títols',
'tog-showtoolbar'             => "Mostrar la barra de menut d'edicion",
'tog-editondblclick'          => 'Editar las paginas amb un doble clic (JavaScript)',
'tog-editsection'             => 'Editar una seccion via los ligams [editar]',
'tog-editsectiononrightclick' => 'Editar una seccion en clicant a drecha<br /> sul títol de la seccion',
'tog-showtoc'                 => 'Afichar la taula de las matièras<br /> (pels articles de mai de 3 seccions)',
'tog-rememberpassword'        => 'Se remembrar de mon senhal (cookie)',
'tog-editwidth'               => "La fenèstra d'edicion s'aficha en plena largor",
'tog-watchcreations'          => 'Ajustar las paginas que suprimissi de ma lista de seguit',
'tog-watchdefault'            => 'Seguir los articles que crei o modifiqui',
'tog-watchmoves'              => 'Ajustar las paginas que renomeni a ma lista de seguit',
'tog-watchdeletion'           => 'Ajustar las paginas que suprimissi de ma lista de seguit',
'tog-minordefault'            => 'Mas modificacions son consideradas<br /> coma menoras per defaut',
'tog-previewontop'            => "Mostrar la previsualizacion<br />al dessús de la boita d'edicion",
'tog-previewonfirst'          => 'Mostrar la previsualizacion al moment de la primièra edicion',
'tog-nocache'                 => "Desactivar l'amagatal de paginas",
'tog-enotifwatchlistpages'    => 'Avertissètz-me per corrièr electronic en cas de modificacion de la pagina',
'tog-enotifusertalkpages'     => "Desiri recebre un corrièr electronic quand ma pagina d'utilizaire es modificada.",
'tog-enotifminoredits'        => "Mandatz-me un corrièr electronic quitament per d'edicions menoras de las paginas",
'tog-enotifrevealaddr'        => 'Afichatz mon adreça electronica dins la notificacion dels corrièrs electronics',
'tog-shownumberswatching'     => "Afichar lo nombre d'utilizaires que seguisson aquesta pagina",
'tog-fancysig'                => 'Signatura bruta (sens ligam automatic)',
'tog-externaleditor'          => 'Utilizar un editor extèrn per defaut',
'tog-externaldiff'            => 'Utilizar un comparator extèrn per defaut',
'tog-showjumplinks'           => 'Activar los ligams « navigacion » e « recèrca » en naut de pagina (aparéncias Myskin e autres)',
'tog-uselivepreview'          => 'Utilizar la vista rapida (JavaScript) (Experimental)',
'tog-forceeditsummary'        => "M'avertir quand ai pas modificat lo contengut de la boita de resumit.",
'tog-watchlisthideown'        => 'Amagar mas pròprias modificacions dins la lista de seguit',
'tog-watchlisthidebots'       => 'Amagar los cambiaments faches pels bòts dins la lista de seguit',
'tog-watchlisthideminor'      => 'Amagar las modificacions menoras dins la lista de seguit',
'tog-nolangconversion'        => 'Desactivar la conversion de las variantas de lenga',
'tog-ccmeonemails'            => 'Mandatz-me una còpia dels corrièrs electronics que mandi als autres utilizaires',
'tog-diffonly'                => 'Mostrar pas lo contengut de las paginas jos las difs',

'underline-always'  => 'Totjorn',
'underline-never'   => 'Pas jamai',
'underline-default' => 'Segon lo navegaire',

'skinpreview' => '(Previsualizar)',

# Dates
'sunday'        => 'dimenge',
'monday'        => 'diluns',
'tuesday'       => 'dimarts',
'wednesday'     => 'dimècres',
'thursday'      => 'dijòus',
'friday'        => 'divendres',
'saturday'      => 'dissabte',
'sun'           => 'Dimg',
'mon'           => 'Dil',
'tue'           => 'Dima',
'wed'           => 'Dimè',
'thu'           => 'Dij',
'fri'           => 'Div',
'sat'           => 'Diss',
'january'       => 'de genièr',
'february'      => 'de febrièr',
'march'         => 'de març',
'april'         => "d'abril",
'may_long'      => 'de mai',
'june'          => 'de junh',
'july'          => 'de julhet',
'august'        => "d'agost",
'september'     => 'de setembre',
'october'       => "d'octobre",
'november'      => 'de novembre',
'december'      => 'de decembre',
'january-gen'   => 'Genièr',
'february-gen'  => 'Febrièr',
'march-gen'     => 'Març',
'april-gen'     => 'Abril',
'may-gen'       => 'Mai',
'june-gen'      => 'Junh',
'july-gen'      => 'Julhet',
'august-gen'    => 'Agost',
'september-gen' => 'Setembre',
'october-gen'   => 'Octobre',
'november-gen'  => 'Novembre',
'december-gen'  => 'Decembre',
'jan'           => 'de gen',
'feb'           => 'de feb',
'mar'           => 'de març',
'apr'           => "d'abr",
'may'           => 'de mai',
'jun'           => 'de junh',
'jul'           => 'de julh',
'aug'           => "d'ago",
'sep'           => 'de set',
'oct'           => "d'oct",
'nov'           => 'de nov',
'dec'           => 'de dec',

# Bits of text used by many pages
'categories'            => 'Categorias de la pagina',
'pagecategories'        => '{{PLURAL:$1|Categoria|Categorias}} de la pagina',
'category_header'       => 'Articles dins la categoria "$1"',
'subcategories'         => 'Soscategorias',
'category-media-header' => 'Fichièrs multimèdia dins la categoria "$1"',
'category-empty'        => "''Actualament, aquesta categoria conten pas cap d'articles o de mèdia.''",

'mainpagetext'      => 'Logicial {{SITENAME}} installat.',
'mainpagedocfooter' => "Referissètz-vos a [http://meta.wikimedia.org/wiki/Help:Contents User's Guide] a prepaus de la personalizacion de l'interfàcia.

== Getting started ==

* [http://www.mediawiki.org/wiki/Manual:Configuration_settings Configuration settings list]
* [http://www.mediawiki.org/wiki/Manual:FAQ MediaWiki FAQ]
* [http://lists.wikimedia.org/mailman/listinfo/mediawiki-announce MediaWiki release mailing list]",

'about'          => 'A prepaus',
'article'        => 'Article',
'newwindow'      => '(dobrís una fenèstra novèla)',
'cancel'         => 'Anullar',
'qbfind'         => 'Recercar',
'qbbrowse'       => 'Far desfilar',
'qbedit'         => 'Editar',
'qbpageoptions'  => "Pagina d'opcion",
'qbpageinfo'     => "Pagina d'informacion",
'qbmyoptions'    => 'Mas opcions',
'qbspecialpages' => 'Paginas especialas',
'moredotdotdot'  => 'E mai...',
'mypage'         => 'Ma pagina',
'mytalk'         => 'Ma pagina de discussion',
'anontalk'       => 'Discussion amb aquesta adreça IP',
'navigation'     => 'Navigacion',

# Metadata in edit box
'metadata_help' => 'Metadonadas:',

'returnto'          => 'Tornar a la pagina $1.',
'help'              => 'Ajuda',
'search'            => 'Recercar',
'searchbutton'      => 'Recercar',
'go'                => 'Legir',
'searcharticle'     => 'Consultar',
'history'           => 'Istoric',
'history_short'     => 'Istoric',
'updatedmarker'     => 'modificat dempuèi ma darrièra visita',
'info_short'        => 'Informacions',
'printableversion'  => 'Version imprimibla',
'permalink'         => 'Ligam permanent',
'print'             => 'Imprimir',
'edit'              => 'Editar',
'editthispage'      => 'Modificar aquesta pagina',
'delete'            => 'Suprimir',
'deletethispage'    => 'Suprimir aquesta pagina',
'undelete_short'    => 'Restablir {{PLURAL:$1|1 modificacion| $1 modificacions}}',
'protect'           => 'Protegir',
'protect_change'    => 'cambiar la proteccion',
'protectthispage'   => 'Protegir aquesta pagina',
'unprotect'         => 'desprotegir',
'unprotectthispage' => 'Desprotegir aquesta pagina',
'newpage'           => 'Pagina novèla',
'talkpage'          => 'Pagina de discussion',
'talkpagelinktext'  => 'Discussion',
'specialpage'       => 'Pagina especiala',
'personaltools'     => 'Espleches personals',
'postcomment'       => 'Ajustar un comentari',
'articlepage'       => "Vejatz l'article",
'views'             => 'Afichatges',
'toolbox'           => "Boita d'espleches",
'userpage'          => "Pagina d'utilizaire",
'projectpage'       => 'Pagina meta',
'imagepage'         => "Pagina d'imatge",
'mediawikipage'     => 'Veire la pagina del messatge',
'templatepage'      => 'Veire la pagina del modèl',
'viewhelppage'      => "Veire la pagina d'ajuda",
'categorypage'      => 'Veire la pagina de las categorias',
'viewtalkpage'      => 'Pagina de discussion',
'otherlanguages'    => 'Autras lengas',
'redirectedfrom'    => '(Redirigit dempuèi $1)',
'redirectpagesub'   => 'Pagina de redireccion',
'lastmodifiedat'    => "Darrièr cambiament d'aquesta pagina : $2, $1.", # $1 date, $2 time
'viewcount'         => 'Aquesta pagina es estada consultada {{plural:$1|un còp|$1 còps}}.',
'protectedpage'     => 'Pagina protegida',
'jumpto'            => 'Anar a:',
'jumptonavigation'  => 'navigacion',
'jumptosearch'      => 'Recercar',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'         => 'A prepaus de {{SITENAME}}',
'aboutpage'         => '{{ns:4}}:A prepaus',
'bugreports'        => "Rapòrt d'errors",
'bugreportspage'    => "{{ns:project}}:Rapòrt d'errors",
'copyright'         => 'Lo contengut es disponible segon los tèrmes de la licéncia $1.',
'copyrightpagename' => 'licéncia {{SITENAME}}',
'copyrightpage'     => '{{ns:project}}:Copyright',
'currentevents'     => 'Actualitats',
'currentevents-url' => 'Project:Actualitats',
'disclaimers'       => 'Avertiments',
'disclaimerpage'    => '{{ns:4}}:Avertiments generals',
'edithelp'          => 'Ajuda',
'edithelppage'      => '{{ns:project}}:Cossí editar una pagina',
'helppage'          => '{{ns:project}}:Ajuda',
'mainpage'          => 'Acuèlh',
'portal'            => 'Comunautat',
'portal-url'        => '{{ns:4}}:Acuèlh',
'privacy'           => 'Politica de confidencialitat',
'privacypage'       => 'meta:Confidencialitat',
'sitesupport'       => 'Participar en fasent un don',
'sitesupport-url'   => 'Project:Fasètz un don',

'badaccess'        => 'Error de permission',
'badaccess-group0' => 'Avètz pas los dreches sufisents per realizar l’accion que demandatz.',
'badaccess-group1' => "L’accion qu'ensajatz de realizar es pas accessibla qu’als utilizaires del grop $1.",
'badaccess-group2' => "L’accion qu'ensajatz de realizar es pas accessibla qu’als utilizaires dels gropes $1.",
'badaccess-groups' => "L’accion qu'ensajatz de realizar es pas accessibla qu’als utilizaires dels gropes $1.",

'versionrequired'     => 'Version $1 de MediaWiki necessària',
'versionrequiredtext' => 'La version $1 de MediaWiki es necessària per utilizar aquesta pagina. Consultatz [[Special:Version]]',

'ok'                      => "D'acòrdi",
'retrievedfrom'           => 'Recuperada de "$1"',
'youhavenewmessages'      => 'Avètz $1 ($2).',
'newmessageslink'         => 'messatge(s) novèl(s)',
'newmessagesdifflink'     => 'darrièr cambiament',
'youhavenewmessagesmulti' => 'Avètz de messatges novèls sus $1',
'editsection'             => 'modificar',
'editold'                 => 'modificar',
'editsectionhint'         => 'Modificar la seccion : $1',
'toc'                     => 'Somari',
'showtoc'                 => 'mostrar',
'hidetoc'                 => 'amagar',
'thisisdeleted'           => 'Afichar o restablir $1?',
'viewdeleted'             => 'Veire $1?',
'restorelink'             => '{{PLURAL:$1|una edicion escafada|$1 edicions escafadas}}',
'feedlinks'               => 'Flus:',
'feed-invalid'            => 'Tipe de flus invalid.',
'site-rss-feed'           => 'Flus RSS de $1',
'site-atom-feed'          => 'Flus Atom de $1',
'page-rss-feed'           => 'Flus RSS de "$1"',
'page-atom-feed'          => 'Flus Atom de "$1"',

# Short words for each namespace, by default used in the 'page' tab in monobook
'nstab-main'      => 'Article',
'nstab-user'      => "Pagina d'utilizaire",
'nstab-media'     => 'Pagina de mèdia',
'nstab-special'   => 'Especial',
'nstab-project'   => 'A prepaus',
'nstab-image'     => 'Fichièr',
'nstab-mediawiki' => 'Messatge',
'nstab-template'  => 'Modèl',
'nstab-help'      => 'Ajuda',
'nstab-category'  => 'Categoria',

# Main script and global functions
'nosuchaction'      => 'Accion desconeguda',
'nosuchactiontext'  => "L'accion especificada dins l'Url es pas reconeguda pel logicial {{SITENAME}}.",
'nosuchspecialpage' => 'Pagina especiala inexistanta',
'nospecialpagetext' => "Avètz demandat una pagina especiala qu'es pas reconeguda pel logicial {{SITENAME}}.",

# General errors
'databaseerror'        => 'Error banca de donadas',
'dberrortext'          => 'Error de sintaxi dins la banca de donadas. La darrièra requèsta tractada per la banca de donadas èra :
<blockquote><tt>$1</tt></blockquote>
dempuèi la foncion "<tt>$2</tt>".
MySQL a renviat l\'error "<tt>$3: $4</tt>".',
'dberrortextcl'        => 'Una requèsta a la banca de donadas compòrta una error de sintaxi.
La darrièra requèsta mandada èra :
« $1 »
efectuada per la foncion « $2 ».
MySQL a retornat l\'error "$3: $4"',
'noconnect'            => 'O planhem ! En seguida a de problèmas tecnics, es impossible de se connectar a la banca de donadas pel moment.',
'nodb'                 => 'Seleccion impossibla de la banca de donadas $1',
'cachederror'          => 'Aquò es una còpia de la pagina demandada e pòt pas èsser mesa a jorn',
'laggedslavemode'      => 'Atencion, aquesta pagina pòt conténer pas las totes darrièrs cambiaments efectuats',
'readonly'             => 'Mesas a jorn blocadas sus la banca de donadas',
'enterlockreason'      => 'Indicatz la rason del blocatge, e mai una estimacion de la durada de blocatge',
'readonlytext'         => "Los ajusts e mesas a jorn sus la banca de donadas {{SITENAME}} son actualament blocats, probablament per permetre la mantenença de la banca, aprèp aquò, tot dintrarà dins l'òrdre. Vaquí la rason per laquala l'administrator a blocat la banca :
<p>$1",
'missingarticle'       => 'La banca de donadas a pas pogut trobar lo tèxt d\'una pagina existenta, que lo títol es "$1".
Es pas una error de la banca de donadas, mas mai probablament un bog del logicial {{SITENAME}}.
Raportatz aquesta error a un administrator, en li indicant l\'adreça de la pagina fautiva.',
'readonly_lag'         => 'La banca de donadas es estada automaticament clavada pendent que los serveires segondaris ratrapan lor retard sul serveire principal.',
'internalerror'        => 'Error intèrna',
'internalerror_info'   => 'Error intèrna: $1',
'filecopyerror'        => 'Impossible de copiar "$1" vèrs "$2".',
'filerenameerror'      => 'Impossible de renomenar "$1" en "$2".',
'filedeleteerror'      => 'Impossible de suprimir "$1".',
'directorycreateerror' => 'Impossible de crear lo dorsièr « $1 ».',
'filenotfound'         => 'Fichièr "$1" introbable.',
'fileexistserror'      => 'Impossible d’escriure dins lo dorsièr « $1 » : lo fiquièr existís',
'unexpected'           => 'Valor inesperada : "$1"="$2".',
'formerror'            => 'Error: Impossible de sometre lo formulari',
'badarticleerror'      => 'Aquesta accion pot pas èsser efectuada sus aquesta pagina.',
'cannotdelete'         => "Impossible de suprimir la pagina o l'imatge indicat.",
'badtitle'             => 'Marrit títol',
'badtitletext'         => 'Lo títol de la pagina demandada es invalid, void o lo ligam interlenga es invalid',
'perfdisabled'         => 'O planhem ! Aquesta foncionalitat es temporàriament desactivada
perque alentís la banca de donadas a un punt tal que degun
pòt pas mai utilizar lo wiki.',
'perfcached'           => 'Aquò es una version en amagatal e es benlèu pas a jorn.',
'perfcachedts'         => 'Las donadas seguentas son en amagatal, son doncas pas obligatòriament a jorn. La darrièra actualizacion data del $1.',
'querypage-no-updates' => 'Las mesas a jorn per aquesta pagina son actualamnt desactivadas. Las donadas çai jos son pas mesas a jorn.',
'wrong_wfQuery_params' => 'Paramètres incorrèctes sus wfQuery()<br />
Foncion : $1<br />
Requèsta : $2',
'viewsource'           => 'Veire lo tèxt font',
'viewsourcefor'        => 'per $1',
'protectedpagetext'    => 'Aquesta pagina es estada protegida per empachar sa modificacion.',
'viewsourcetext'       => 'Podètz veire e copiar son còde font :',
'protectedinterface'   => 'Aquesta pagina fornís de tèxt d’interfàcia pel logicial e es protegida per evitar los abuses.',
'editinginterface'     => "'''Atencion:''' Sètz a editar una pagina qu'es utilizada per modificar lo tèxt de l'interfàcia del logicial. Los cambiaments sus aquesta pagina afectaràn l'aparéncia de l'interfàcia d'utilizaire pels autres utilizaires.",
'sqlhidden'            => '(Requèsta SQL amagada)',
'cascadeprotected'     => 'Aquesta pagina es actualament protegida perque es inclusa dins las paginas seguentas, que son estadas protegidas amb l’opcion « proteccion en cascada » activada :',
'namespaceprotected'   => "Avètz pas la permission de modificar las paginas de l’espaci de noms « '''$1''' ».",
'customcssjsprotected' => "Avètz pas la permission d'editar aquesta pagina perque conten de preferéncias d’autres utilizaires.",
'ns-specialprotected'  => 'Las paginas dins l’espaci de noms « Special » pòdon pas èsser modificadas',

# Login and logout pages
'logouttitle'                => 'Desconnexion',
'logouttext'                 => "Ara, sètz desconnectat(ada). Podètz contunhar d'utilizar {{SITENAME}} anonimament, o vos tornar connectar, eventualament amb un autre nom.",
'welcomecreation'            => "<h2>Benvenguda, $1!</h2><p>Vòstre compte d'utilizaire es estat creat.
Doblidetz pas de personalizar vòstre {{SITENAME}} en consultant la pagina Preferéncias.",
'loginpagetitle'             => 'Vòstre identificant',
'yourname'                   => "Vòstre nom d'utilizaire",
'yourpassword'               => 'Vòstre senhal',
'yourpasswordagain'          => 'Picatz tornarmai vòstre senhal',
'remembermypassword'         => 'Se remembrar de mon senhal (cookie)',
'yourdomainname'             => 'Vòstre domeni',
'externaldberror'            => 'Siá una error s’es producha amb la banca de donadas d’autentificacion extèrna, siá sètz pas autorizat a metre a jorn vòstre compte extèrn.',
'loginproblem'               => '<b>Problèma d’identificacion.</b><br />Ensajatz tornarmai !',
'login'                      => 'Identificacion',
'loginprompt'                => 'Devètz activar los cookies per vos connectar a {{SITENAME}}.',
'userlogin'                  => 'Identificacion',
'logout'                     => 'Desconnexion',
'userlogout'                 => 'Desconnexion',
'notloggedin'                => 'Pas connectat',
'nologin'                    => 'Avètz pas de compte ? $1.',
'nologinlink'                => 'Creatz un compte',
'createaccount'              => 'Crear un compte novèl',
'gotaccount'                 => 'Avètz ja un compte ? $1.',
'gotaccountlink'             => 'Identificatz-vos',
'createaccountmail'          => 'per corrièr electronic',
'badretype'                  => "Los senhals qu'avètz picats son pas identics.",
'userexists'                 => "Lo nom d'utilizaire qu'avètz picat es ja utilizat. Causissètz-ne un autre.",
'youremail'                  => 'Mon adreça electronica',
'username'                   => 'Nom d’utilizaire :',
'uid'                        => 'Numèro d’utilizaire :',
'yourrealname'               => 'Nom vertadièr *',
'yourlanguage'               => 'Lenga:',
'yourvariant'                => 'Varianta',
'yournick'                   => 'Mon escais (per las signaturas)',
'badsig'                     => 'Signatura bruta incorrècta ; Verificatz vòstras balisas HTML.',
'badsiglength'               => 'Vòstra signatura es tròp longa : la talha maximala es de $1 caractèrs.',
'email'                      => 'Corrièr electronic',
'prefs-help-realname'        => "* Nom vertadièr (facultatiu) : se l'especificatz, serà utilizat per l'atribucion de vòstras contribucions.",
'loginerror'                 => "Problèma d'identificacion",
'prefs-help-email'           => '*Adreça de corrièr electronic (facultatiu) : permet de vos contactar dempuèi lo sit sens desvelar vòstra identitat.',
'prefs-help-email-required'  => 'Una adreça de corrièr electronic es requesa.',
'nocookiesnew'               => "Lo compte d'utilizaire es estat creat, mas sètz pas connectat. {{SITENAME}} utiliza de cookies per la connexion mas los avètz desactivats. Activatz-los e reconnectatz-vos amb lo meteis nom e lo meteis senhal.",
'nocookieslogin'             => '{{SITENAME}} utiliza de cookies per la connexion mas avètz los cookies desactivats. Activatz-los e reconnectatz-vos.',
'noname'                     => "Avètz pas picat de nom d'utilizaire.",
'loginsuccesstitle'          => 'Identificacion capitada.',
'loginsuccess'               => 'Sètz actualament connectat(ada) sus {{SITENAME}} en tant que "$1".',
'nosuchuser'                 => 'L\'utilizaire "$1" existís pas.
Verificatz qu\'avètz plan ortografiat lo nom, o utilizatz lo formulari çai jos per crear un compte d\'utilizaire novèl.',
'nosuchusershort'            => 'I a pas de contributor amb lo nom « $1 ». Verificatz l’ortografia.',
'nouserspecified'            => 'Devètz picar un nom d’utilizaire.',
'wrongpassword'              => 'Lo senhal es incorrècte. Ensajatz tornarmai.',
'wrongpasswordempty'         => 'Avètz pas entrat de senhal. Ensajatz tornarmai.',
'passwordtooshort'           => 'Vòstre senhal es tròp cort. Deu conténer al mens $1 caractèrs.',
'mailmypassword'             => 'Mandatz-me un senhal novèl',
'passwordremindertitle'      => 'Vòstre senhal novèl sus {{SITENAME}}',
'passwordremindertext'       => 'Qualqu\'un (probablament vos) amb l\'adreça IP $1 a demandat qu\'un senhal novèl vos siá mandat per vòstre accès a {{SITENAME}} ($4).
Lo senhal de l\'utilizaire "$2" es a present "$3".

Vos conselham de vos connectar e de modificar aqueste senhal tre que possible.',
'noemail'                    => 'Cap adreça electronica es pas estada enregistrada per l\'utilizaire "$1".',
'passwordsent'               => 'Un senhal novèl es estat mandat a l\'adreça electronica de l\'utilizaire "$1".
Identificatz-vos tre que l\'aurètz recebut.',
'blocked-mailpassword'       => 'Vòstra adreça IP es blocada en edicion, la foncion de rapèl del senhal es doncas desactivada per evitar los abuses.',
'eauthentsent'               => 'Un corrièr de confirmacion es estat mandat a l’adreça indicada.
Abans qu’un autre corrièr sià mandat a aqueste compte, devretz seguir las instruccions donadas dins lo messatge per confirmar que sètz plan lo titular.',
'throttled-mailpassword'     => 'Un corrièr electronic de rapèl de vòstre senhal ja es estat mandat durant las $1 darrièras oras. Per evitar los abuses, un sol corrièr de rapèl serà mandat en $1 oras.',
'mailerror'                  => 'Error en mandant lo corrièr electronic : $1',
'acct_creation_throttle_hit' => "O planhèm, avètz ja $1 comptes creats. Podètz pas ne crear d'autres.",
'emailauthenticated'         => 'Vòstra adreça de corrièr electronic es estada autentificada lo $1.',
'emailnotauthenticated'      => 'Vòstra adreça de corrièr electronic es <strong>pas encara autentificada</strong>. Cap corrièr serà pas mandat per caduna de las foncions seguentas.',
'noemailprefs'               => "Fornissetz una adreça de corrièr electronic pel bon foncionament d'aquestas foncionalitats.",
'emailconfirmlink'           => 'Confirmatz vòstra adreça de corrièr electronic',
'invalidemailaddress'        => 'Aquesta adreça de corrièr electronic pòt pas èsser acceptada perque sembla aver un format invalid. Entratz una adreça valida o daissatz aqueste camp void.',
'accountcreated'             => 'Compte creat.',
'accountcreatedtext'         => "Lo compte d'utilizaire de $1 es estat creat.",
'loginlanguagelabel'         => 'Lenga: $1',

# Password reset dialog
'resetpass'               => 'Remesa a zèro del senhal',
'resetpass_announce'      => 'Vos sètz enregistrat amb un senhal temporari mandat per corrièr electronic. Per acabar l’enregistrament, devètz picar un senhal novèl aicí :',
'resetpass_text'          => '<!-- Ajust de tèxt aicí -->',
'resetpass_header'        => 'Remesa a zèro del senhal',
'resetpass_submit'        => 'Cambiar lo senhal e s’enregistrar',
'resetpass_success'       => 'Vòstre senhal es estat cambiat amb succès ! Enregistrament en cors...',
'resetpass_bad_temporary' => 'Senhal temporari invalid. Benlèu avètz ja cambiat vòstre senhal amb succès, o demandat un senhal temporari novèl.',
'resetpass_forbidden'     => 'Los senhals pòdon pas èsser cambiats sus aqueste wiki',
'resetpass_missing'       => 'Cap de donada entrada.',

# Edit page toolbar
'bold_sample'     => 'Tèxt en gras',
'bold_tip'        => 'Tèxt en gras',
'italic_sample'   => 'Tèxt en italica',
'italic_tip'      => 'Tèxt en italica',
'link_sample'     => 'Títol del ligam',
'link_tip'        => 'Ligam intèrn',
'extlink_sample'  => 'http://www.example.com títol del ligam',
'extlink_tip'     => 'Ligam extèrn (doblidez pas lo prefix http://)',
'headline_sample' => 'Tèxt de sostítol',
'headline_tip'    => 'Sostítol nivèl 2',
'math_sample'     => 'Picatz vòstra formula aicí',
'math_tip'        => 'Formula matematica (LaTeX)',
'nowiki_sample'   => 'Picatz lo tèxt pas formatat aicí',
'nowiki_tip'      => 'Ignorar la sintaxi wiki',
'image_sample'    => 'Exemple.jpg',
'image_tip'       => 'Imatge inserit',
'media_sample'    => 'Exemple.ogg',
'media_tip'       => 'Ligam vèrs un fichièr mèdia',
'sig_tip'         => 'Vòstra signatura amb la data',
'hr_tip'          => "Linha orizontala (n'abusetz pas)",

# Edit pages
'summary'                   => 'Resumit',
'subject'                   => 'Subjècte/títol',
'minoredit'                 => 'Cambiament menor.',
'watchthis'                 => 'Seguir aqueste article',
'savearticle'               => 'Salvagardar',
'preview'                   => 'Previsualizar',
'showpreview'               => 'Previsualizacion',
'showlivepreview'           => 'Previsualizacion',
'showdiff'                  => 'Cambiaments en cors',
'anoneditwarning'           => "Utilizatz pas de [[Special:Userlogin|compte anonim]]. Sètz '''localizat per vòstra adreça IP''', que serà archivada publicament dins l’<span class=\"plainlinks\">[{{fullurl:{{FULLPAGENAME}}|action=history}} istoric]</span> se modificatz aquesta pagina. <br /><strong> Devètz previzualisar la pagina abans de salvagardar vòstra modificacion.</strong>",
'missingsummary'            => "'''Atencion :''' avètz pas modificat lo resumit de vòstra modificacion. Se clicatz tornarmai sul boton « Salvagardar », la salvagarda serà facha sens avertiment novèl.",
'missingcommenttext'        => 'Mercé de metre un comentari çai jos.',
'missingcommentheader'      => "'''Rapèl :''' Avètz pas provesit de subjècte/títol per aqueste comentari. Se clicatz tornarmai sus ''Salvagardar'', vòstra edicion serà enregistrada sens aquò.",
'summary-preview'           => 'Previsualizacion del resumit',
'subject-preview'           => 'Previsualizacion del subjècte/títol',
'blockedtitle'              => 'Utilizaire blocat',
'blockedtext'               => "<big>'''Vòstre compte d'utilizaire o vòstra adreça IP son estadas blocadas'''</big> per $1 per la rason seguenta :<br />$2<p> Podètz contactar $1 o un autre [[{{MediaWiki:grouppage-sysop}}|administrator]] per ne discutir.
Vòstra adreça IP actuala es $3, e lo blocatge d'adreça IP es #$5. Inclusissètz caduna d'aquestas entre-senha dins vòstra requèsta.",
'autoblockedtext'           => 'Vòstra adreça IP es estada blocada automaticament perque es estada utilizada per un autre utilizaire, ele-meteis blocat per $1. La rason balhada es : :\'\'$2\'\' Expiracion del blocatge : $6 Podètz contactar $1 o un dels autres [[{{MediaWiki:grouppage-sysop}}|administrators]] per discutir d\'aqueste blocatge. Notatz que podètz pas utilizar la foncion "Mandar un messatge a aqueste utilizaire" a mens qu’aguessetz balhat una adreça e-mail valida dins vòstras [[Special:Preferences|preferéncias]]. Vòstre identificant de blocatge es $5. Precizatz-lo dins tota requèsta.',
'blockedoriginalsource'     => "Lo còde font de '''$1''' es indicat çai jos :",
'blockededitsource'         => "Lo tèxt de '''vòstras edicions''' sus '''$1''' es afichat çai jos :",
'whitelistedittitle'        => 'Enregistrament necessari per modificar lo contengut',
'whitelistedittext'         => 'Devètz vos $1 per editar las paginas.',
'whitelistreadtitle'        => 'Enregistrament necessari per legir lo contengut',
'whitelistreadtext'         => 'Devètz [[Special:Userlogin|vos identificar]] per legir las paginas.',
'whitelistacctitle'         => 'Vos es pas permés de crear un compte',
'whitelistacctext'          => 'Per èstre autorizat a crear de comptes dins aquesta Wiki devètz [[Special:Userlogin|vos identificar]] e aver las autorizacions apropriadas.',
'confirmedittitle'          => "Confirmacion de l'adreça electronica demandada per editar",
'confirmedittext'           => "Devètz confirmar vòstra adreça electronica abans de modificar l'enciclopèdia. Entratz e validatz vòstra adreça electronica amb l'ajuda de la pagina [[Special:Preferences|preferéncias]].",
'nosuchsectiontitle'        => 'Seccion mancanta',
'nosuchsectiontext'         => "Avètz ensajat de modificar una seccion qu’existís pas. Coma i a pas de seccion $1, i a pas d'endrech ont salvagardar vòstras modificacions.",
'loginreqtitle'             => 'Enregistrament necessari',
'loginreqlink'              => 'connectar',
'loginreqpagetext'          => 'Devètz vos $1 per veire las autras paginas.',
'accmailtitle'              => 'Senhal mandat.',
'accmailtext'               => "Lo senhal de '$1' es estat mandat a $2.",
'newarticle'                => '(Novèl)',
'newarticletext'            => 'Picatz aicí lo tèxt de vòstre article.',
'anontalkpagetext'          => "---- ''Aquò es la pagina de discussion per un utilizaire anonim qu'a pas encara creat un compte o que l'utiliza pas. Per aqueste rason, devem utilizar l'adreça IP numerica per l'identificar. Una adreça d'aqueste tope pòt èsser pertejada entre mantun utilizaires. Se sètz un utilizaire anonim e se constatatz que de comentaris que vos concernisson pas vos son estats adreçats, podètz [[Special:Userlogin|crear un compte o vos connectar]] per evitar tota confusion venenta.",
'noarticletext'             => "Pel moment, i a pas cap de tèxt sus aquesta pagina ; podètz [[{{ns:special}}:Search/{{PAGENAME}}|lançar una recèrca sul títol d'aquesta pagina]] o [{{fullurl:{{NAMESPACE}}:{{FULLPAGENAME}}|action=edit}} modificar aquesta pagina].",
'clearyourcache'            => 'Nòta : Aprèp aver salvagardat, devètz forçar lo recargament de la pagina per veire los cambiaments : Mozilla / Konqueror / Firefox : ctrl-shift-r, IE / Opera : ctrl-f5, Safari : cmd-r.',
'usercssjsyoucanpreview'    => "'''Astúcia :''' utilisatz lo boton '''Previsualisacion''' per testar vòstra fuèlha novèla css/js abans de l'enregistrar.<br />Per importar vòstra fuèlha monobook dempuèi una URL, utilisatz ''@import url (VÒSTRA_URL_AICÍ&action=raw&ctype=text/css)''",
'usercsspreview'            => "'''Remembratz-vos que sètz a previsualizar vòstra pròpria fuèlha CSS e qu’es pas encara estada enregistrada !'''",
'userjspreview'             => "'''Remembrat-vos que sètz a visualizar o testar vòstre còde JavaScript e qu’es pas encara estat enregistrat !'''",
'userinvalidcssjstitle'     => "'''Atencion :''' existís pas d'estil « $1 ». Remembratz-vos que las paginas personalas amb extensions .css e .js utilizan de títols en minusculas aprèp lo nom d'utilizaire e la barra de fraccion /.<br />Atal, Utilizaire:Foo/monobook.css es valid, alara que Utilizaire:Foo/Monobook.css serà una fuèlha d'estil invalida.",
'updated'                   => '(Mes a jorn)',
'note'                      => '<strong>Nòta :</strong>',
'previewnote'               => "Atencion, aqueste tèxt es pas qu'una previsualizacion e es pas encara estat salvagardat !",
'previewconflict'           => "La previsualizacion mòstra lo tèxt d'aquesta pagina tal coma apareisserà un còp salvagardat.",
'session_fail_preview'      => '<strong>O planhem ! Podem pas enregistrar vòstra modificacion a causa d’una pèrda d’informacions concernent vòstra session. Ensajatz tornarmai. Se aquò capita pas encara, desconnectatz-vos, puèi reconnectatz-vos.</strong>',
'session_fail_preview_html' => "<strong>O planhem ! Podem pas enregistrar vòstra modificacion a causa d’una pèrda d’informacions concernent vòstra session.</strong> ''L’HTML brut essent activat sus aqueste wiki, la previsualizacion es estada amagada per prevenir un atac per JavaScript.'' <strong>Se la tentativa de modificacion èra legitima, ensajatz tornarmai. Se aquò capita pas encara , desconnectatz-vos, puèi reconnectatz-vos.</strong>",
'token_suffix_mismatch'     => '<strong>Vòstra edicion es pas estada acceptada perque vòstre navegaire a mesclat los caractèrs de ponctuacion dins l’identificant d’edicion. L’edicion es estada rejetada per empachar la corrupcion del tèxt de l’article. Aqueste problèma se produtz quand utilizatz un proxy anonim amb problèma.</strong>',
'editing'                   => 'modificacion de $1',
'editinguser'               => 'modificacion de $1',
'editingsection'            => 'Modificacion de $1 (seccion)',
'editingcomment'            => 'Modificacion de $1 (comentari)',
'editconflict'              => 'Conflicte de modificacion : $1',
'explainconflict'           => "<b>Aqueste pagina es estada salvagardada aprèp qu'avètz començat de la modificar.
La zòna d'edicion superiora conten lo tèxt tal coma es enregistrat actualament dins la banca de donadas. Vòstras modificacions apareisson dins la zòna d'edicion inferiora. Anatz dever aportar vòstras modificacions al tèxt existent. Sol lo tèxt de la zòna superiora serà salvagardat.</b><br />",
'yourtext'                  => 'Vòstre tèxt',
'storedversion'             => 'Version enregistrada',
'nonunicodebrowser'         => '<strong>Atencion : Vòstre navegaire supòrta pas l’unicode. Una solucion temporària es estada trobada per vos permetre de modificar en tota seguretat un article : los caractèrs non-ASCII apareisseràn dins vòstra boita de modificacion en tant que còdes exadecimals. Deuriatz utilizar un navegaire mai recent.</strong>',
'editingold'                => "<strong>Atencion : sètz a modificar una version obsolèta d'aquesta pagina. Se salvagardatz, totas las modificacions efectuadas dempuèi aquesta version seràn perdudas.</strong>",
'yourdiff'                  => 'Diferéncias',
'copyrightwarning'          => "Totas las contribucions a {{SITENAME}} son consideradas coma publicadas jols tèrmes de la $2 (vejatz $1 per mai de detalhs). Se desiratz pas que vòstres escriches sián modificats e distribuits a volontat, mercés de los sometre pas aicí.<br /> Nos prometètz tanben qu'avètz escrich aquò vos-meteis, o que l’avètz copiat d’una font provenent del domeni public, o d’una ressorsa liura.<strong>UTILIZETZ PAS DE TRABALHS JOS COPYRIGHT SENS AUTORIZACION EXPRÈSSA !</strong>",
'copyrightwarning2'         => "Totas las contribucions a {{SITENAME}} pòdon èsser modificadas o suprimidas per d’autres utilizaires. Se desiratz pas que vòstres escriches sián modificats e distribuits a volontat, mercés de los sometre pas aicí.<br /> Tanben nos prometètz qu'avètz escrich aquò vos-meteis, o que l’avètz copiat d’una font provenent del domeni public, o d’una ressorsa liura. (vejatz $1 per mai de detalhs). <strong>UTILISETZ PAS DE TRABALHS JOS COPYRIGHT SENS AUTORIZACION EXPRÈSSA !</strong>",
'longpagewarning'           => '<strong>AVERTIMENT : aquesta pagina a una longor de $1 ko. De delà de 32 ko, es preferible per cèrts navegaires de devesir aquesta pagina en seccions mai pichonas.</strong>',
'longpageerror'             => "<strong>ERROR: Lo tèxt qu'avètz mandat es de $1 Ko, e despassa doncas lo limit autorizat dels $2 Ko. Lo tèxt pòt pas èsser salvagardat.</strong>",
'readonlywarning'           => "<strong>AVERTIMENT : '''aquesta pagina es <span style=\"color:red\">protegida</span> <u>temporàriament</u> e <u>automaticament</u> per mantenença.'''<br />Doncas poiretz pas i salvagardar vòstras modificacions ara. Podètz copiar lo tèxt dins un fichièr e lo salvagardar per mai tard.</strong>",
'protectedpagewarning'      => "<strong>ATENCION : Aquesta pagina es protegida. Sols los utilizaires amb l'estatut d'administrator la pòdon modificar. Asseguratz-vos que seguissètz las directivas concernent las paginas protegidas.</strong>",
'semiprotectedpagewarning'  => "'''Nòta:''' Aquesta pagina es estada blocada, pòt èsser editada pas que pels utiliaires enregistats.",
'cascadeprotectedwarning'   => '<strong>ATENCION : Aquesta pagina es estada protegida de biais que sols los [[{{MediaWiki:grouppage-sysop}}|administrators]] pòscan l’editar. Aquesta proteccion es estada facha perque aquesta pagina es inclusa dins una pagina protegida amb la « proteccion en cascada » activada.</strong>',
'templatesused'             => 'Modèls utilizats sus aquesta pagina :',
'templatesusedpreview'      => 'Modèls utilizats dins aquesta previsualizacion :',
'templatesusedsection'      => 'Modèls utilizats dins aquesta seccion :',
'template-protected'        => '(protegit)',
'template-semiprotected'    => '(semiprotegit)',
'edittools'                 => '<!-- Tot tèxt picat aicí serà afichat jos las boitas de modificacion o d’impòrt de fichièr. -->',
'nocreatetitle'             => 'Creacion de pagina limitada',
'nocreatetext'              => 'Aqueste sit a restrenhut la possibilitat de crear de paginas novèlas. Podètz tonar en arrièr e modificar una pagina existenta, [[Special:Userlogin|vos connectar o crear un compte]].',
'nocreate-loggedin'         => 'Avètz pas la permission de crear de paginas novèlas sus aqueste wiki.',
'permissionserrors'         => 'Error de permissions',
'permissionserrorstext'     => 'Avètz pas la permission d’efectuar l’operacion demandada per {{PLURAL:$1|la rason seguenta|las rasons seguentas}} :',
'recreate-deleted-warn'     => "'''Atencion : sètz a recrear una pagina qu'es estada precedentament suprimida.''' 

Demandatz-vos se es vertadièrament apropriat de la tornar crear en vos referissent al jornal de las supressions afichat çaijos :",

# "Undo" feature
'undo-success' => 'Aquesta modificacion es estada desfacha. Confirmatz, e salvagardatz los cambiaments çai jos.',
'undo-failure' => 'Aquesta modificacion a pas pogut èsser desfacha a causa de conflictes amb de modificacions intermediàrias.',
'undo-summary' => 'Anullacion de las modificacions $1 de [[Special:Contributions/$2|$2]] ([[User talk:$2|discutir]])',

# Account creation failure
'cantcreateaccounttitle' => 'Podètz pas crear de compte.',
'cantcreateaccount-text' => "La creacion de compte dempuèi aquesta adreça IP (<b>$1</b>) es estada blocada per [[User:$3|$3]].
La rason donada per $3 èra ''$2''.",

# History pages
'revhistory'          => 'Versions precedentas',
'viewpagelogs'        => "Vejatz lo jornal d'aquesta pagina",
'nohistory'           => "Existís pas d'istoric per aquesta pagina.",
'revnotfound'         => 'Version introbabla',
'revnotfoundtext'     => "La version precedenta d'aquesta pagina a pas pogut èsser retrobada. Verificatz l'URL qu'avètz utilizat per accedir a aquesta pagina.",
'loadhist'            => "Cargament de l'istoric de la pagina",
'currentrev'          => 'Version actuala',
'revisionasof'        => 'Version del $1',
'revision-info'       => 'Version del $1 per $2',
'previousrevision'    => '←Version precedenta',
'nextrevision'        => 'Version seguenta→',
'currentrevisionlink' => 'vejatz la version correnta',
'cur'                 => 'actu',
'next'                => 'seg',
'last'                => 'darr',
'page_first'          => 'prim',
'page_last'           => 'darr',
'histlegend'          => 'Legenda : (actu) = diferéncia amb la version actuala ,
(darr) = diferéncia amb la version precedenta, M = modificacion menora',
'deletedrev'          => '[suprimit]',
'histfirst'           => 'Primièras contribucions',
'histlast'            => 'Darrièras contribucions',
'historysize'         => '({{PLURAL:$1|1 octet|$1 octets}})',
'historyempty'        => '(void)',

# Revision feed
'history-feed-title'          => 'Istoric de las versions',
'history-feed-description'    => 'Istoric per aquesta pagina sul wiki',
'history-feed-item-nocomment' => '$1 lo $2', # user at time
'history-feed-empty'          => 'La pagina demandada existís pas. Benlèu es estada suprimida del wiki o renomenada. Podètz ensajar de [[Special:Search|recercar dins lo wiki]] de las paginas pertinentas recentas.',

# Revision deletion
'rev-deleted-comment'         => '(Comentari suprimit)',
'rev-deleted-user'            => '(nom d’utilizaire suprimit)',
'rev-deleted-event'           => '(comentari suprimit)',
'rev-deleted-text-permission' => '<div class="mw-warning plainlinks"> Aquesta version de la pagina es estada levada de las archius publicas. Pòt i aver de detalhs dins lo [{{fullurl:Special:Log/delete|page={{PAGENAMEE}}}} jornal de las supressions]. </div>',
'rev-deleted-text-view'       => '<div class="mw-warning plainlinks"> Aquesta version de la pagina es estada levada de las archius publicas. En tant qu’administrator d\'aqueste sit, podètz la visualizar ; pòt i aver de detahls dins lo [{{fullurl:Special:Log/delete|page={{PAGENAMEE}}}} jornal de las supressions]. </div>',
'rev-delundel'                => 'afichar/amagar',
'revisiondelete'              => 'Suprimir/Restablir de versions',
'revdelete-nooldid-title'     => 'Pas de cibla per la revision',
'revdelete-nooldid-text'      => 'Avètz pas precisat la o las revision(s) cibla(s) per utilizar aquesta foncion.',
'revdelete-selected'          => 'Version seleccionada de [[:$1]] :',
'logdelete-selected'          => "{{PLURAL:$2|Eveniment de jornal seleccionat|Eveniments de jornal seleccionats}} per '''$1''' :",
'revdelete-text'              => "Las versions suprimidas apareisseràn encara dins l’istoric de l’article, mas lor contengut textual serà inaccessible al public. 

D’autres administrators sus aqueste wiki poiràn totjorn accedir al contengut amagat e lo restablir tornarmai a travèrs d'aquesta meteissa interfàcia, a mens qu’una restriccion suplementària siá mesa en plaça pels operators del sit.",
'revdelete-legend'            => 'Metre en plaça de restriccions de version :',
'revdelete-hide-text'         => 'Amagar lo tèxt de la version',
'revdelete-hide-name'         => 'Amagar l’accion e la cibla',
'revdelete-hide-comment'      => 'Amagar lo comentari de modificacion',
'revdelete-hide-user'         => 'Amagar lo pseudonim o l’adreça IP del contributor.',
'revdelete-hide-restricted'   => 'Aplicar aquestas restriccions als administrators e mai als autres utilizaires',
'revdelete-suppress'          => 'Suprimir las donadas dels administrators e dels autres',
'revdelete-hide-image'        => 'Amagar lo contengut del fichièr',
'revdelete-unsuppress'        => 'Levar las restriccions sus las versions restablidas',
'revdelete-log'               => 'Comentari pel jornal :',
'revdelete-submit'            => 'La visibilitat de la version es estada modificada per [[$1]]',
'revdelete-logentry'          => 'La visibilitat de la version es estada modificada per [[$1]]',
'logdelete-logentry'          => 'La visibilitat de l’eveniment es estada modificada per [[$1]]',
'revdelete-logaction'         => '$1 {{plural:$1|version cambiada|versions cambiadas}} en mòde $2',
'logdelete-logaction'         => '$1 {{plural:$1|eveniment de [[$3]] cambiat|eveniments de [[$3]] cambiats}} en mòde $2',
'revdelete-success'           => 'Visibilitat de las versions cambiadas amb succès.',
'logdelete-success'           => 'Visibilitat dels eveniments cambiada amb succès.',

# Oversight log
'oversightlog'    => 'Jornal oversight',
'overlogpagetext' => 'la lista çai jos mòstra las supressions e blocatges recents que lo contengut es amagat quitament pels administrators. Consultatz la [[Special:Ipblocklist|lista dels comptes blocats]] per la lista dels blocatges en cors.',

# Diffs
'history-title'             => 'Istoric de las versions de « $1 »',
'difference'                => '(Diferéncias entre las versions)',
'loadingrev'                => 'cargament de la version anciana per comparason',
'lineno'                    => 'Linha $1:',
'editcurrent'               => "Modificar la version actuala d'aquesta pagina",
'selectnewerversionfordiff' => 'Causir una version mai recenta',
'selectolderversionfordiff' => 'Causir una version mai anciana',
'compareselectedversions'   => 'Comparar las versions seleccionadas',
'editundo'                  => 'desfar',
'diff-multi'                => '({{plural:$1|Una revision intermediària amagada|$1 revisions intermediàrias amagadas}})',

# Search results
'searchresults'         => 'Resultat de la recèrca',
'searchresulttext'      => "Per mai d'informacions sus la recèrca dins {{SITENAME}}, vejatz [[Projècte:Recèrca|Cercar dins {{SITENAME}}]].",
'searchsubtitle'        => 'Per la requèsta "[[:$1]]"',
'searchsubtitleinvalid' => 'Per la requèsta "$1"',
'noexactmatch'          => 'Cap de pagina amb lo títol "$1" existís pas, ensajatz amb la recèrca complèta. Si que non, podètz [[:$1|crear aquesta pagina]]',
'titlematches'          => 'Correspondéncias dins los títols',
'notitlematches'        => "Cap de títol d'article conten pas lo(s) mot(s) demandat(s)",
'textmatches'           => 'Correspondéncias dins los tèxtes',
'notextmatches'         => "Cap de tèxt d'article conten pas lo(s) mot(s) demandat(s)",
'prevn'                 => '$1 precedents',
'nextn'                 => '$1 seguents',
'viewprevnext'          => 'Veire ($1) ($2) ($3).',
'showingresults'        => 'Afichatge de <b>$1</b> resultats a partir del #<b>$2</b>.',
'showingresultsnum'     => 'Afichatge de <b>$3</b> resultats a partir del #<b>$2</b>.',
'nonefound'             => '<strong>Nòta</strong>: l\'abséncia de resultat es sovent deguda a l\'emplec de tèrmes de recèrca tròp corrents, coma "a" o "de",
que son pas indexats, o a l\'emplec de mantun tèrme de recèrca (solas las paginas
contenent totes los tèrmes apareisson dins los resultats).',
'powersearch'           => 'Recèrca',
'powersearchtext'       => 'Recercar dins los espacis :<br />
$1<br />
$2 Enclure las paginas de redireccions   Recercar $3 $9',
'searchdisabled'        => 'La recèrca sus {{SITENAME}} es desactivada. En esperant la reactivacion, podètz efectuar una recèrca via Google. 
Atencion, lor indexacion de contengut {{SITENAME}} benlèu es pas a jorn.',

# Preferences page
'preferences'              => 'Preferéncias',
'mypreferences'            => 'Mas preferéncias',
'prefs-edits'              => 'Nombre d’edicions :',
'prefsnologin'             => 'Non connectat(da)',
'prefsnologintext'         => "Devètz èsser [[Special:Userlogin|connectat]]
per modificar vòstras preferéncias d'utilizaire.",
'prefsreset'               => 'Las preferéncias son estadas restablidas a partir de la version enregistrada.',
'qbsettings'               => "Personalizacion de la barra d'espleches",
'qbsettings-none'          => 'Cap',
'qbsettings-fixedleft'     => 'Esquèrra',
'qbsettings-fixedright'    => 'Drecha',
'qbsettings-floatingleft'  => 'Flotejant a esquèrra',
'qbsettings-floatingright' => 'Flotanta a drecha',
'changepassword'           => 'Modificacion del senhal',
'skin'                     => 'Aparéncia',
'math'                     => 'Rendut de las matas',
'dateformat'               => 'Format de data',
'datedefault'              => 'Cap de preferéncia',
'datetime'                 => 'Data e ora',
'math_failure'             => 'Error matas',
'math_unknown_error'       => 'error indeterminada',
'math_unknown_function'    => 'foncion desconeguda',
'math_lexing_error'        => 'error lexicala',
'math_syntax_error'        => 'error de sintaxi',
'math_image_error'         => 'La conversion en PNG a pas capitat ; verificatz l’installacion de Latex, dvips, gs e convert',
'math_bad_tmpdir'          => 'Impossible de crear o d’escriure dins lo repertòri math temporari',
'math_bad_output'          => 'Impossible de crear o d’escriure dins lo repertòri math de sortida',
'math_notexvc'             => 'L’executable « texvc » es introbable. Legissètz math/README per lo configurar.',
'prefs-personal'           => 'Informacions personalas',
'prefs-rc'                 => 'Darrièrs cambiaments',
'prefs-watchlist'          => 'Lista de seguit',
'prefs-watchlist-days'     => 'Nombre de jorns de mostrar dins la lista de seguit :',
'prefs-watchlist-edits'    => "Nombre de modificacions d'afichar dins la lista de seguit espandida :",
'prefs-misc'               => 'Preferéncias divèrsas',
'saveprefs'                => 'Enregistrar las preferéncias',
'resetprefs'               => 'Restablir las preferéncias',
'oldpassword'              => 'Senhal ancian',
'newpassword'              => 'Senhal novèl',
'retypenew'                => 'Confirmar lo senhal novèl',
'textboxsize'              => "Talha de la fenèstra d'edicion",
'rows'                     => 'Rengadas',
'columns'                  => 'Colomnas',
'searchresultshead'        => 'Afichatge dels resultats de recèrca',
'resultsperpage'           => 'Nombre de responsas per pagina',
'contextlines'             => 'Nombre de linhas per responsa',
'contextchars'             => 'Nombre de caractèrs de contèxt per linha',
'stub-threshold'           => 'Limita superiora pels <a href="#" class="stub">ligams vèrs los esbòces</a> (octets) :',
'recentchangesdays'        => 'Jorns de mostrar dins los darrièrs cambiaments:',
'recentchangescount'       => 'Nombre de títols dins los darrièrs cambiaments',
'savedprefs'               => 'Las preferéncias son estadas salvagardadas.',
'timezonelegend'           => 'Fus orari',
'timezonetext'             => "Se precisatz pas de decalatge orari, es l'ora d'Euròpa de l'oèst que serà utilizada.",
'localtime'                => 'Ora locala',
'timezoneoffset'           => 'Decalatge orari',
'servertime'               => 'Ora del serveire',
'guesstimezone'            => 'Utilizar la valor del navegaire',
'allowemail'               => 'Autorizar lo mandadís de corrièr electronic venent d’autres utilizaires',
'defaultns'                => 'Per defaut, recercar dins aquestes espacis :',
'default'                  => 'defaut',
'files'                    => 'Fichièrs',

# User rights
'userrights-lookup-user'      => "Gestion dels dreches d'utilizaire",
'userrights-user-editname'    => 'Entrar un nom d’utilizaire :',
'editusergroup'               => "Modificacion dels gropes d'utilizaires",
'userrights-editusergroup'    => 'Modificar los gropes de l’utilizaire',
'saveusergroups'              => "Salvagardar los gropes d'utilizaire",
'userrights-groupsmember'     => 'Membre de:',
'userrights-groupsavailable'  => 'Gropes disponibles:',
'userrights-groupshelp'       => "Causissètz las permissions que volètz levar o ajustar a l'utilizaire.
Los gropes pas seleccionats seràn pas modificats. Podètz deseleccionar un grop amb CTRL + Clic esquèrra.",
'userrights-reason'           => 'Rason del cambiament:',
'userrights-available-none'   => 'Podètz pas cambiar l’apartenéncia als diferents gropes.',
'userrights-available-add'    => "Podètz ajustar d'utilizaires a $1.",
'userrights-available-remove' => "Podètz levar d'utilizaires de $1.",

# Groups
'group'               => 'Grop:',
'group-autoconfirmed' => 'Utilizaires enregistrats',
'group-bot'           => 'Bòts',
'group-sysop'         => 'Administrators',
'group-bureaucrat'    => 'Burocratas',
'group-all'           => '(totes)',

'group-autoconfirmed-member' => 'Utilizaire enregistrat',
'group-bot-member'           => 'Bòt',
'group-sysop-member'         => 'Administrator',
'group-bureaucrat-member'    => 'Burocrata',

'grouppage-autoconfirmed' => '{{ns:project}}:Utilizaires enregistrats',
'grouppage-bot'           => '{{ns:project}}:Bòts',
'grouppage-bureaucrat'    => '{{ns:project}}:Burocratas',

# User rights log
'rightslog'      => "Istoric de las modificacions d'estatut",
'rightslogtext'  => "Aquò es un jornal de las modificacions d'estatut d’utilizaire.",
'rightslogentry' => 'a modificat los dreches de l’utilizaire « $1 » de $2 a $3',
'rightsnone'     => '(cap)',

# Recent changes
'nchanges'                          => '$1 {{PLURAL:$1|cambiament|cambiaments}}',
'recentchanges'                     => 'Darrièrs cambiaments',
'recentchangestext'                 => 'Vaquí sus aquesta pagina, los darrièrs cambiaments de {{SITENAME}}.',
'recentchanges-feed-description'    => "Seguissètz los darrièrs cambiaments d'aqueste wiki dins un flus.",
'rcnote'                            => "Vaquí <strong>{{PLURAL:$1|lo cambiament|los '''$1''' cambiaments}}</strong> efectuats al cors <strong>{{PLURAL:$2|del darrièr jorn|dels '''$2''' darrièrs jorns}}</strong>.",
'rcnotefrom'                        => 'Vaquí los cambiamtns efectuats dempuèi lo <strong>$2</strong> (<b>$1</b> al maximom).',
'rclistfrom'                        => 'Afichar las modificacions novèlas dempuèi lo $1.',
'rcshowhideminor'                   => '$1 modificacions menoras',
'rcshowhidebots'                    => '$1 bòts',
'rcshowhideliu'                     => '$1 utilizaires enregistrats',
'rcshowhideanons'                   => '$1 utilizaires anonims',
'rcshowhidepatr'                    => '$1 edicions susvelhadas',
'rcshowhidemine'                    => '$1 mas edicions',
'rclinks'                           => 'Afichar los $1 darrièrs cambiaments efectuats al cors dels $2 darrièrs jorns; $3 cambiaments menors.',
'diff'                              => 'dif',
'hist'                              => 'ist',
'hide'                              => 'amagar',
'show'                              => 'mostrar',
'minoreditletter'                   => 'M',
'number_of_watching_users_pageview' => '[$1 utilizaire(s) seguent(s)]',
'rc_categories'                     => 'Limit de las categorias (separacion amb « | »)',
'rc_categories_any'                 => 'Totas',
'newsectionsummary'                 => '/* $1 */ seccion novèla',

# Recent changes linked
'recentchangeslinked'          => 'Seguit dels ligams',
'recentchangeslinked-title'    => 'Seguit dels ligams associats a $1',
'recentchangeslinked-noresult' => 'Cap de cambiament sus las paginas ligadas pendent lo periòde causit.',
'recentchangeslinked-summary'  => "Aquesta pagina especiala mòstra las modificacions recentas sus las paginas que son ligadas. Las paginas de vòstra lista de seguit son '''en gras'''.",

# Upload
'upload'                      => 'Copiar sul serveire',
'uploadbtn'                   => 'Copiar un fichièr',
'reupload'                    => 'Copiar tornarmai',
'reuploaddesc'                => 'Retorn al formulari.',
'uploadnologin'               => 'Non connectat(ada)',
'uploadnologintext'           => 'Devètz èsser [[Special:Userlogin|connectat(ada)]]
per copiar de fichièrs sul serveire.',
'upload_directory_read_only'  => 'Lo serveire Web pòt escriure dins lo dorsièr cibla ($1).',
'uploaderror'                 => 'Error',
'uploadtext'                  => "Utilizatz lo formulari çai jos per copiar d'imatges novèls sul serveire. Per veire los imatges ja plaçats sul serveire o per efectuar una recèrca demèst eles, anatz a [[Special:Imagelist|la lista dels imatges]]. Los uploads e las supressions son listats dins lo [[Special:Log/upload|jornal dels uploads]].

Per inclure un imatge dins una pagina, utilizatz un dels modèls seguents:
'''<nowiki>[[</nowiki>{{ns:image}}<nowiki>:File.jpg]]</nowiki>''',
'''<nowiki>[[</nowiki>{{ns:image}}<nowiki>:File.png|alt text]]</nowiki>''' o
'''<nowiki>[[</nowiki>{{ns:media}}<nowiki>:File.ogg]]</nowiki>''' per un ligam dirèct vèrs lo fichièr.",
'uploadlog'                   => 'Jornals dels telecargaments (uploads)',
'uploadlogpage'               => 'Istoric de las importacions de fichièrs multimèdia',
'uploadlogpagetext'           => "Vaquí la lista dels darrièrs fichièrs copiats sul serveire.
L'ora indicada es la del serveire (UTC).",
'filename'                    => 'Nom',
'filedesc'                    => 'Descripcion',
'fileuploadsummary'           => 'Resumit:',
'filestatus'                  => 'Estatut del copyright',
'filesource'                  => 'Font',
'uploadedfiles'               => 'Fichièrs copiats',
'ignorewarning'               => 'Ignorar l’avertiment e salvagardar lo fichièr.',
'ignorewarnings'              => "Ignorar los avertiments a l'ocasion de l’impòrt",
'minlength1'                  => 'Los noms de fichièrs dèvon comprendre al mens una letra.',
'illegalfilename'             => 'Lo nom de fichièr « $1 » conten de caractèrs interdiches dins los títols de paginas. Mercé de lo renomenar e de lo copiar tornarmai.',
'badfilename'                 => 'Imatge es estat torni nom "$1".',
'filetype-badmime'            => 'Los fichièrs del tipe MIME « $1 » pòdon pas èsser importats.',
'filetype-badtype'            => "'''« .$1 »''' es un tipe de fichièr non desirat 
: Lista dels tipes de fichièrs autorizats : $2",
'filetype-missing'            => "Lo fichièr a pas cap d'extension (coma « .jpg » per exemple).",
'large-file'                  => 'Los fichièrs importats deurián pas èsser mai gros que $1 ; aqueste fichièr fa $2.',
'largefileserver'             => "La talha d'aqueste fichièr es superiora al maximom autorizat.",
'emptyfile'                   => 'Lo fichièr que volètz importar sembla void. Aquò pòt èsser degut a una error dins lo nom del fichièr. Verificatz que desiratz vertadièrament copiar aqueste fichièr.',
'fileexists'                  => 'Un fichièr amb aqueste nom existís ja. Mercé de verificar $1. Sètz segur de voler modificar aqueste fichièr ?',
'fileexists-extension'        => "Un fichièr amb un nom similar existís ja :<br /> Nom del fichièr d'importar : <strong><tt>$1</tt></strong><br /> Nom del fichièr existent : <strong><tt>$2</tt></strong><br /> la sola diferéncia es la cassa (majusculas / minusculas) de l’extension. Verificatz que lo fichièr es diferent e cambiatz son nom.",
'fileexists-thumb'            => "'''<center>Imatge existent</center>'''",
'fileexists-thumbnail-yes'    => 'Lo fichièr sembla èsser un imatge en talha reducha <i>(thumbnail)</i>. Verificatz lo fichièr <strong><tt>$1</tt></strong>.<br /> Se lo fichièr verificat es lo meteis imatge (dins una resolucion melhora), es pas de besonh d’importar una version reducha.',
'file-thumbnail-no'           => 'Lo nom del fichièr comença per <strong><tt>$1</tt></strong>. Es possible que s’agisca d’una version reducha <i>(thumbnail)</i>. Se dispausatz del fichièr en resolucion nauta, importatz-lo, si que non cambiatz lo nom del fichièr.',
'fileexists-forbidden'        => 'Un fichièr amb aqueste nom existís ja ; mercé de tornar en arrièr e de copiar lo fichièr jos un nom novèl. [[Image:$1|thumb|center|$1]]',
'fileexists-shared-forbidden' => 'Un fichièr portant lo meteis nom existís ja dins la banca de donadas comuna ; tornatz en arrièr e mandatz-lo tornarmai jos un autre nom. [[Image:$1|thumb|center|$1]]',
'successfulupload'            => 'Còpia capitada',
'uploadwarning'               => 'Atencion !',
'savefile'                    => 'Salvagardar lo fichièr',
'uploadedimage'               => ' "[[$1]]" copiat sul serveire',
'overwroteimage'              => 'a importat una version novèla de « [[$1]] »',
'uploaddisabled'              => 'O planhem, lo mandadís de fichièr es desactivat.',
'uploaddisabledtext'          => 'La còpia de fichièrs es desactivada sus aqueste wiki.',
'uploadscripted'              => "Aqueste fichièr conten de còde HTML o un escript que poiriá èsser interpretat d'un biais incorrècte per un navegaire Internet.",
'uploadcorrupt'               => 'Aqueste fichièr es corromput, a una talha nulla o possedís una extension invalida. Verificatz lo fichièr.',
'uploadvirus'                 => 'Aqueste fichièr conten un virús ! Per mai de detalhs, consultatz : $1',
'sourcefilename'              => 'Nom del fichièr de mandar',
'destfilename'                => 'Nom jolqual lo fichièr serà enregistrat',
'watchthisupload'             => 'Seguir aqueste fichièr',
'filewasdeleted'              => 'Un fichièr amb aqueste nom es estat copiat ja, puèi suprimit. Deuriatz verificar lo $1 abans de procedir a una còpia novèla.',
'upload-wasdeleted'           => "'''Atencion : Sètz a importar un fichièr que ja es estat suprimit deperabans.''' Deuriatz considerar se es oportun de contunhar l'impòrt d'aqueste fichièr. Lo jornal de las supressions vos donarà los elements d'informacion.",
'filename-bad-prefix'         => 'Lo nom del fichièr qu\'importatz comença per <strong>"$1"</strong> qu\'es un nom generalament donat pels aparelhs de fòto numerica e que decritz pas lo fichièr. Causissetz un nom de fichièr descrivent vòstre fichièr.',

'upload-proto-error'      => 'Protocòl incorrècte',
'upload-proto-error-text' => "L’impòrt requerís d'URLs començant per <code>http://</code> o <code>ftp://</code>.",
'upload-file-error'       => 'Error intèrna',
'upload-file-error-text'  => 'Una error intèrna es subrevenguda en volent crear un fichièr temporari sul serveire. Contactatz un administrator de sistèma.',
'upload-misc-error'       => 'Error d’impòrt desconeguda',
'upload-misc-error-text'  => 'Una error desconeguda es subrevenguda pendent l’impòrt. Verificatz que l’URL es valida e accessibla, puèi ensajatz tornarmai. Se lo problèma persistís, contactatz un administrator del sistèma.',

# Some likely curl errors. More could be added from <http://curl.haxx.se/libcurl/c/libcurl-errors.html>
'upload-curl-error6'       => 'Pòt pas aténher l’URL',
'upload-curl-error6-text'  => 'L’URL fornida pòt pas èsser atenhuda. Verificatz que l’URL es corrècta e que lo sit es en linha.',
'upload-curl-error28'      => 'Depassament de la sosta al moment de l’impòrt',
'upload-curl-error28-text' => "Lo sit a mis tròp de temps per respondre. Verificatz que lo sit es en linha, esperatz un pauc e ensajatz tornarmai. Podètz tanben ensajar a una ora d'afluéncia mendra.",

'license'            => 'Licéncia',
'nolicense'          => 'Cap de licéncia seleccionada',
'license-nopreview'  => '(Previsualizacion impossibla)',
'upload_source_url'  => ' (una URL valida e accessibla publicament)',
'upload_source_file' => '(un fichièr sus vòstre ordenador)',

# Image list
'imagelist'                 => 'Lista dels imatges',
'imagelisttext'             => 'Vaquí una lista de $1 imatges classats $2.',
'getimagelist'              => 'Recuperacion de la lista dels imatges',
'ilsubmit'                  => 'Cercar',
'showlast'                  => 'Afichar los $1 darrièrs imatges classats $2.',
'byname'                    => 'per nom',
'bydate'                    => 'per data',
'bysize'                    => 'per talha',
'imgdelete'                 => 'supr',
'imgdesc'                   => 'descr',
'imgfile'                   => 'fichièr',
'filehist'                  => 'Istoric del fichièr',
'filehist-help'             => 'Clicar sus una data e una ora per veire lo fichièr tal coma èra a aqueste moment',
'filehist-deleteall'        => 'tot suprimir',
'filehist-deleteone'        => 'suprimir aquò',
'filehist-revert'           => 'revocar',
'filehist-current'          => 'actual',
'filehist-datetime'         => 'Data e ora',
'filehist-user'             => 'Utilizaire',
'filehist-filesize'         => 'Talha del fichièr',
'filehist-comment'          => 'Comentari',
'imagelinks'                => "Ligams vèrs l'imatge",
'linkstoimage'              => 'Las paginas çai jos compòrtan un ligam vèrs aqueste imatge:',
'nolinkstoimage'            => 'Cap de pagina compòrta pas de ligam vèrs aqueste imatge.',
'sharedupload'              => 'Aqueste fichièr es pertejat e pòt èsser utilizat per d’autres projèctes.',
'shareduploadwiki'          => 'Reportatz-vos a la $1 per mai d’informacion.',
'shareduploadwiki-linktext' => 'Pagina de descripcion del fichièr',
'noimage'                   => 'Cap de fichièr possedissent aqueste nom existís pas, podètz $1.',
'noimage-linktext'          => "n'importar un",
'uploadnewversion-linktext' => "Copiar una version novèla d'aqueste fichièr",
'imagelist_date'            => 'Data',
'imagelist_name'            => 'Nom',
'imagelist_user'            => 'Utilizaire',
'imagelist_size'            => 'Talha (en octets)',
'imagelist_description'     => 'Descripcion',
'imagelist_search_for'      => 'Recèrca per l’imatge nomenat :',

# File reversion
'filerevert'                => 'Revocar $1',
'filerevert-legend'         => 'Revocar lo fichièr',
'filerevert-intro'          => '<span class="plainlinks">Anatz revocar \'\'\'[[Media:$1|$1]]\'\'\' fins a [$4 la version del $2 a $3].</span>',
'filerevert-comment'        => 'Comentari:',
'filerevert-defaultcomment' => 'Revocat fins a la version del $1 a $2',
'filerevert-submit'         => 'Revocar',
'filerevert-success'        => '<span class="plainlinks">\'\'\'[[Media:$1|$1]]\'\'\' es estat revocat fins a [$4 la version del $2 a $3].</span>',
'filerevert-badversion'     => 'I a pas de version mai anciana del fichièr amb lo Timestamp donat.',

# File deletion
'filedelete'             => 'Suprimís $1',
'filedelete-legend'      => 'Suprimir lo fichièr',
'filedelete-intro'       => "Sètz a suprimir '''[[Media:$1|$1]]'''.",
'filedelete-intro-old'   => '<span class="plainlinks">Sètz a escafar la version de \'\'\'[[Media:$1|$1]]\'\'\' del [$4 $2 a $3].</span>',
'filedelete-comment'     => 'Comentari:',
'filedelete-submit'      => 'Suprimir',
'filedelete-success'     => "'''$1''' es estat suprimit.",
'filedelete-success-old' => '<span class="plainlinks">La version de \'\'\'[[Media:$1|$1]]\'\'\' del $2 a $3 es estada suprimida.</span>',
'filedelete-nofile'      => "'''$1''' existís pas sus aqueste site.",
'filedelete-nofile-old'  => "Existís pas cap de version archivada de '''$1''' amb los atributs indicats.",
'filedelete-iscurrent'   => "Sètz a ensajar de suprimir la version mai recenta d'aqueste fichièr. Devètz, deperabans, restablir una version anciana d'aqueste.",

# MIME search
'mimesearch'         => 'Recèrca per tipe MIME',
'mimesearch-summary' => 'Aquesta pagina especiala permet de cercar de fichièrs en foncion de lor tipe MIME. Entrada : tipe/sostipe, per exemple <tt>image/jpeg</tt>.',
'mimetype'           => 'Tipe MIME:',
'download'           => 'telecargament',

# Unwatched pages
'unwatchedpages' => 'Paginas pas seguidas',

# List redirects
'listredirects' => 'Lista de las redireccions',

# Unused templates
'unusedtemplates'     => 'Modèls inutilizats',
'unusedtemplatestext' => 'Aquesta pagina lista totas las paginas de l’espaci de noms « Modèl » que son incluses dins cap autra pagina. Doblidetz pas de verificar se i a pas d’autre ligam vèrs los modèls abans de los suprimir.',
'unusedtemplateswlh'  => 'autres ligams',

# Random pages
'randompage'         => "Una pagina a l'azard",
'randompage-nopages' => 'I a pas cap de pagina dins aqueste espaci de nom.',

# Random redirect
'randomredirect'         => "Una pagina de redireccion a l'azard",
'randomredirect-nopages' => 'I a pas cap de redireccion dins aqueste espaci de nom.',

# Statistics
'statistics'             => 'Estatisticas',
'sitestats'              => 'Estatisticas del sit',
'userstats'              => "Estatisticas d'utilizaire",
'sitestatstext'          => "La banca de donadas conten actualament <b>{{PLURAL:\$1|'''1''' pagina|'''\$1''' paginas}}</b>.

Aquesta chifra inclutz las paginas \"discussion\", las paginas relativas a {{SITENAME}}, las paginas minimalas (\"taps\"),  las paginas de redireccion, e mai d'autras paginas que pòdon sens dobte pas èsser consideradas coma d'articles.
Se s'exclutz aquestes paginas,  <b>{{PLURAL:\$2|demòra '''1''' pagina qu'es|demòran '''\$2''' paginas que son}}</b> probablament d'articles vertadièrs.<p>
'''\$8''' {{PLURAL:\$8|fichièr|fichièrs}} son estats telecargat.
{{PLURAL:\$3|'''1''' pagina es estada consultada|'''\$3''' paginas son estadas consultadas}} e {{PLURAL:\$4| '''1''' pagina modificada|'''\$4''' paginas modificadas}} dempuèi la mesa a jorn del logicial (31 d'octobre de 2002).
Aquò representa una mejana de <b>\$5</b> modificacions per pagina e de <b>\$6</b> consultacions per una modificacion.",
'userstatstext'          => "I a {{PLURAL:$1|'''1''' utilizaire enregistrat|'''$1''' utilizaires enregistrats}}.
Demest eles, <b>$2</b> (o '''$4%''') {{PLURAL:$2|a|an}} l'estatut d'administrator (vejatz $3).",
'statistics-mostpopular' => 'Paginas mai consultadas',

'disambiguations'      => "Paginas d'omonimia",
'disambiguationspage'  => "Template:Ligams_a_las_paginas_d'omonimia",
'disambiguations-text' => 'Las paginas seguentas ligan vèrs una <i>pagina d’omonimia</i>. Deurián puslèu ligar vèrs una pagina pertinenta.<br /> Una pagina es tractada coma una pagina d’omonimia se es ligada dempuèi $1.<br /> Los ligams dempuèi d’autres espacis de noms <i>son pas</i> listats aicí.',

'doubleredirects'     => 'Redireccion dobla',
'doubleredirectstext' => '<b>Atencion:</b> Aquesta lista pòt conténer de "positius falses". Dins aqueste cas, es probablament la pagina del primièr #REDIRECT conten tanben de tèxt.<br />Cada linha conten los ligams e la 1èra e 2nda pagina de redireccion, e mai la primièra linha d\'aquesta darrièra, que balha normalament la "vertadièra" destinacion. Lo primièr #REDIRECT deurià ligar vèrs aquesta destinacion.',

'brokenredirects'        => 'Redireccions copadas',
'brokenredirectstext'    => "Aquestas redireccions mènan a una pagina qu'existís pas.",
'brokenredirects-edit'   => '(modificar)',
'brokenredirects-delete' => '(suprimir)',

'withoutinterwiki'        => 'Paginas sens ligams interlengas',
'withoutinterwiki-header' => "Aquesta pagina a pas de ligams vèrs las versions dins d'autras lengas:",

'fewestrevisions' => 'Articles amb lo mens de revisions',

# Miscellaneous special pages
'nbytes'                  => '$1 octets',
'ncategories'             => '$1 {{PLURAL:$1|categoria|categorias}}',
'nlinks'                  => '$1 referéncias',
'nmembers'                => '$1 {{PLURAL:$1|membre|membres}}',
'nviews'                  => '$1 consultacions',
'specialpage-empty'       => 'Aquesta pagina es voida.',
'lonelypages'             => 'Paginas orfanèlas',
'lonelypagestext'         => 'Las paginas seguentas son pas ligadas a partir d’autras paginas del wiki.',
'uncategorizedpages'      => 'Paginas sens categorias',
'uncategorizedcategories' => 'Categorias sens categorias',
'uncategorizedimages'     => 'Imatges sens categorias',
'uncategorizedtemplates'  => 'Modèls sens categoria',
'unusedcategories'        => 'Categorias inutilizadas',
'unusedimages'            => 'Imatges orfanèls',
'popularpages'            => 'Paginas mai consultadas',
'wantedcategories'        => 'Categorias mai demandadas',
'wantedpages'             => 'Paginas mai demandadas',
'mostlinked'              => 'Paginas mai ligadas',
'mostlinkedcategories'    => 'Categorias mai utilizadas',
'mostlinkedtemplates'     => 'Modèls mai utilizats',
'mostcategories'          => 'Articles utilizant mai de categorias',
'mostimages'              => 'Imatges mai utilizats',
'mostrevisions'           => 'Articles mai modificats',
'allpages'                => 'Totas las paginas',
'prefixindex'             => 'Totas las paginas per primièras letras',
'shortpages'              => 'Articles corts',
'longpages'               => 'Articles longs',
'deadendpages'            => "Paginas sul camin d'enlòc",
'deadendpagestext'        => 'Las paginas seguentas contenon pas cap de ligam vèrs d’autras paginas del wiki.',
'protectedpages'          => 'Paginas protegidas',
'protectedpagestext'      => 'Las paginas seguentas son protegidas contra las modificacions e/o lo renomenatge :',
'protectedpagesempty'     => 'Cap de pagina es pas protegida actualament.',
'listusers'               => 'Lista dels participants',
'specialpages'            => 'Paginas especialas',
'spheading'               => 'Paginas especialas',
'restrictedpheading'      => 'Paginas especialas reservadas',
'rclsub'                  => '(de las paginas ligadas a "$1")',
'newpages'                => 'Paginas novèlas',
'newpages-username'       => 'Utilizaire :',
'ancientpages'            => 'Articles mai ancians',
'intl'                    => 'Ligams interlengas',
'move'                    => 'Renomenar',
'movethispage'            => 'Desplaçar la pagina',
'unusedimagestext'        => "<p>Doblidetz pas que d'autres sits non occitanofònes, pòdon conténer un ligam dirèct vèrs aqueste imatge, e qu'aqueste pòt èsser plaçat dins aquesta lista alara qu'es en realitat utilizada.",
'unusedcategoriestext'    => "Las categorias seguentas existisson mas cap d'article o de categoria los utilizan pas.",
'notargettitle'           => 'Pas de cibla',
'notargettext'            => 'Indicatz una pagina cibla o un utilizaire cibla.',

# Book sources
'booksources'               => 'Obratges de referéncia',
'booksources-search-legend' => "Recèrca demest d'obratges de referéncia",
'booksources-go'            => 'Validar',
'booksources-text'          => "Vaquí una lista de ligams vèrs d’autres sites que vendon de libres nous e d’occasion e sulsquals trobarètz benlèu d'informacions suls obratges que cercatz. {{SITENAME}} es pas ligada a cap d'aquestas societats, a pas l’intencion de ne far la promocion.",

'categoriespagetext' => 'Las categorias seguentas existisson dins lo wiki.',
'data'               => 'Donadas',
'userrights'         => "Gestion dels dreches d'utilizaire",
'groups'             => "Gropes d'utilizaires",
'alphaindexline'     => '$1 a $2',

# Special:Log
'specialloguserlabel'  => 'Utilizaire:',
'speciallogtitlelabel' => 'Títol:',
'log'                  => 'Jornals',
'all-logs-page'        => 'Totes los jornals',
'log-search-legend'    => "Recèrca d'istorics",
'log-search-submit'    => 'Anar',
'alllogstext'          => 'Afichatge combinat dels jornals de còpia, supression, proteccion, blocatge, e administrator. Podètz restrénher la vista en seleccionant un tipe de jornal, un nom d’utilizaire o la pagina concernida.',
'logempty'             => 'I a pas res dins l’istoric per aquesta pagina.',
'log-title-wildcard'   => 'Recercar de títols que començan per aqueste tèxt',

# Special:Allpages
'nextpage'          => 'Pagina seguenta ($1)',
'prevpage'          => 'Pagina precedenta ($1)',
'allpagesfrom'      => 'Afichar las paginas a partir de :',
'allarticles'       => 'Totes los articles',
'allinnamespace'    => 'Totas las paginas (espaci de noms $1)',
'allnotinnamespace' => 'Totas las paginas (que son pas dins l’espaci de noms $1)',
'allpagesprev'      => 'Precedent',
'allpagesnext'      => 'Seguent',
'allpagessubmit'    => 'Validar',
'allpagesprefix'    => 'Afichar las paginas començant pel prefix :',
'allpagesbadtitle'  => 'Lo títol rensenhat per la pagina es incorrècte o possedís un prefix reservat. Conten segurament un o mantun caractèr especial que pòt pas èsser utilizats dins los títols.',
'allpages-bad-ns'   => '{{SITENAME}} a pas d’espaci de noms « $1 ».',

# Special:Listusers
'listusersfrom'      => 'Afichar los utilizaires a partir de :',
'listusers-submit'   => 'Mostrar',
'listusers-noresult' => "S'es pas trobat de noms d'utilizaires correspondents. Cercatz tanben amb de majusculas e minusculas.",

# E-mail user
'mailnologin'     => "Pas d'adreça",
'mailnologintext' => 'Devètz èsser [[Special:Userlogin|connectat(ada)]]
e aver indicat una adreça electronica valida dins vòstras [[Special:Preferences|preferéncias]]
per poder mandar un messatge a un autre utilizaire.',
'emailuser'       => 'Mandar un messatge a aqueste utilizaire',
'emailpage'       => 'Mandar un corrièr electronic a l’utilizaire',
'emailpagetext'   => 'Se aqueste utilizaire a indicat una adreça electronica valida dins sas preferéncias, lo formulari çai jos li mandarà un messatge.
L\'adreça electronica qu\'avètz indicada dins vòstras preferéncias apareisserà dins lo camp "Expeditor" de vòstre messatge, per que lo destinatari pòsca vos respondre.',
'usermailererror' => 'Error dins lo subjècte del corrièr electronic :',
'defemailsubject' => 'Corrièr electronic mandat dempuèi {{SITENAME}}',
'noemailtitle'    => "Pas d'adreça electronica",
'noemailtext'     => "Aquesta utilizaire a pas especificat d'adreça electronica valida o a causit de recebre pas de corrièr electronic dels autres utilizaires.",
'emailfrom'       => 'Expeditor',
'emailto'         => 'Destinatari',
'emailsubject'    => 'Objècte',
'emailmessage'    => 'Messatge',
'emailsend'       => 'Mandar',
'emailccme'       => 'Me mandar per corrièr electronic una còpia de mon messatge.',
'emailccsubject'  => 'Còpia de vòstre messatge a $1 : $2',
'emailsent'       => 'Messatge mandat',
'emailsenttext'   => 'Vòstre messatge es estat mandat.',

# Watchlist
'watchlist'            => 'Lista de seguit',
'mywatchlist'          => 'Lista de seguit',
'watchlistfor'         => "(per l’utilizaire '''$1''')",
'nowatchlist'          => "Vòstra lista de seguit conten pas cap d'article.",
'watchlistanontext'    => 'Per poder afichar o editar los elements de vòstra lista de seguit, devètz vos $1.',
'watchnologin'         => 'Non connectat',
'watchnologintext'     => 'Devètz èsser [[Special:Userlogin|connectat(ada)]]
per modificar vòstra lista.',
'addedwatch'           => 'Ajustat a la lista',
'addedwatchtext'       => 'La pagina "$1" es estada ajustada a vòstra [[Special:Watchlist|lista de seguit]].
Las modificacions venetas d\'aquesta pagina e de la pagina de discussion associada seràn repertoriadas aicí, e la pagina apareisserà <b>en gras</b> dins la [[Special:Recentchanges|lista dels darrièrs cambiaments]] per èsser localisada mai aisidament.

Per suprimir aquesta pagina de vòstra lista de seguida, clicatz sus "Arrestar de seguir" dins lo quadre de navigacion.',
'removedwatch'         => 'Suprimida de la lista de seguit',
'removedwatchtext'     => 'La pagina "[[:$1]]" es estada suprimida de vòstra lista de seguit.',
'watch'                => 'Seguir',
'watchthispage'        => 'Seguir aquesta pagina',
'unwatch'              => 'Arrestar de seguir',
'unwatchthispage'      => 'Arrestar de seguir',
'notanarticle'         => "Cap d'article",
'watchnochange'        => 'Cap de las paginas que seguissètz son pas estadas modificadas pendent lo periòde afichat.',
'watchlist-details'    => 'Seguissètz {{PLURAL:$1|$1 pagina|$1 paginas}}, sens comptar las paginas de discussion.',
'wlheader-enotif'      => '* La notificacion per corrièr electronic es activada.',
'wlheader-showupdated' => '* Las paginas que son estadas modificadas dempuèi vòstra darrièra visita son mostradas en <b>gras</b>',
'watchmethod-recent'   => 'verificacion dels darrièrs cambiaments de las paginas seguidas',
'watchmethod-list'     => 'verificacion de las paginas seguidas per de modificacions recentas',
'watchlistcontains'    => 'Vòstra lista de seguit conten $1 {{PLURAL:$1|pagina|paginas}}.',
'iteminvalidname'      => "Problèma amb l'article '$1': lo nom es invalid...",
'wlnote'               => 'Los darrièrs cambiaments dempuèi las <br>$2</b> darrièras oras se tròban çai jos.',
'wlshowlast'           => 'Mostrar las darrièras $1 oras, los darrièrs $2 jorns, o $3.',
'watchlist-show-bots'  => 'Afichar las contribucions dels bòts',
'watchlist-hide-bots'  => 'Amagar las contribucions dels bòts',
'watchlist-show-own'   => 'Afichar mas modificacions',
'watchlist-hide-own'   => 'Amagar mas modificacions',
'watchlist-show-minor' => 'Afichar las modificacions menoras',
'watchlist-hide-minor' => 'Amagar las modificacions menoras',

# Displayed when you click the "watch" button and it's in the process of watching
'watching'   => 'Seguit...',
'unwatching' => 'Fin del seguit...',

'enotif_mailer'                => 'Sistèma d’expedicion de notificacion de {{SITENAME}}',
'enotif_reset'                 => 'Marcar totas las paginas coma visitadas',
'enotif_newpagetext'           => 'Aquò es una pagina novèla.',
'enotif_impersonal_salutation' => 'Utilizaire de {{SITENAME}}',
'changed'                      => 'modificada',
'created'                      => 'creada',
'enotif_subject'               => 'La pagina $PAGETITLE de {{SITENAME}} es estada $CHANGEDORCREATED per $PAGEEDITORemailto',
'enotif_lastvisited'           => 'Consultatz $1 per totes los cambiaments dempuèi vòstra darrièra visita.',
'enotif_lastdiff'              => 'Consultatz $1 per veire aquesta modificacion.',
'enotif_anon_editor'           => 'utilizaire non-enregistrat $1',
'enotif_body'                  => 'Car $WATCHINGUSERNAME,

la pagina de {{SITENAME}} $PAGETITLE es estada $CHANGEDORCREATED lo $PAGEEDITDATE per $PAGEEDITOR, vejatz $PAGETITLE_URL per la version actuala.

$NEWPAGE

Resumit de l’editor : $PAGESUMMARY $PAGEMINOREDIT

Contactatz l’editor :
corrièr electronic : $PAGEEDITOR_EMAIL
wiki : $PAGEEDITOR_WIKI

I aurà pas de notificacions novèlas en cas d’autras modificacions a mens que visitetz aquesta pagina. Podètz tanben remetre a zèro lo notificator per totas las paginas de vòstra lista de seguit.

             Vòstre {{SITENAME}} sistèma de notificacion

--
Per modificar los paramètres de vòstra lista de seguit, visitatz
{{fullurl:Special:Watchlist/edit}}

Retorn e assisténcia :
{{fullurl:{{MediaWiki:helppage}}}}',

# Delete/protect/revert
'deletepage'                  => 'Suprimir una pagina',
'confirm'                     => 'Confirmar',
'excontent'                   => "contenent '$1'",
'excontentauthor'             => 'lo contengut èra : « $1 » (e lo sol contributor èra « [[Special:Contributions/$2|$2]] »)',
'exbeforeblank'               => "lo contengut abans escafament èra :'$1'",
'exblank'                     => 'pagina voida',
'confirmdelete'               => 'Confirmar la supression',
'deletesub'                   => '(Supression de "$1")',
'historywarning'              => 'Atencion: La pagina que sètz a mand de suprimir a un istoric:',
'confirmdeletetext'           => "Sètz a mand de suprimir definitivament de la banca de donadas una pagina
o un imatge, e mai totas sas versions anterioras.
Confirmatz qu'es plan çò que volètz far, que ne comprenètz las consequéncias e que fasètz aquò en acòrdi amb las [[{{MediaWiki:policy-url}}]].",
'actioncomplete'              => 'Supression efectuada',
'deletedtext'                 => '"$1" es estat suprimit.
Vejatz $2 per una lista de las supressions recentas.',
'deletedarticle'              => 'escafament de "[[$1]]"',
'dellogpage'                  => 'Traça dels escafaments',
'dellogpagetext'              => "Vaquí la lista de las supressions recentas.
L'ora indicada es la del serveire (UTC).
<ul>
</ul>",
'deletionlog'                 => 'traça dels escafaments',
'reverted'                    => 'Restabliment de la version precedenta',
'deletecomment'               => 'Motiu de la supression',
'rollback'                    => 'revocar las modificacions',
'rollback_short'              => 'Revocar',
'rollbacklink'                => 'revocar',
'rollbackfailed'              => 'La revocacion a pas capitat',
'cantrollback'                => 'Impossible de revocar: darrièr autor es lo sol a aver modificat aqueste article',
'alreadyrolled'               => "Impossible de revocar la darrièra modificacion de [[:$1]]
per  [[User:$2|$2]] ([[User talk:$2|Discussion]]); qualqu'un d'autre a ja modificat o revocat l'article.

La darrièra modificacion èra de [[User:$3|$3]] ([[User talk:$3|Discussion]]).",
'editcomment'                 => 'Lo resumit de la modificacion èra: "<i>$1</i>".', # only shown if there is an edit comment
'revertpage'                  => 'restitucion de la darrièra modificacion de [[Special:Contributions/$2|$2]] ([[User talk:$2|Discussion]]); retorn a la darrièra version de [[User:$1|$1]]',
'rollback-success'            => 'Revocacion de las modificacions de $1 ; retorn a la version de $2.',
'sessionfailure'              => 'Vòstra session de connexion sembla aver de problèmas ;
aquesta accion es estada anullada en prevencion d’un piratatge de session.
Clicatz sus « Precedent » e recargatz la pagina d’ont venètz, puèi ensajatz tornarmai.',
'protectlogpage'              => 'Istoric de las proteccions',
'protectlogtext'              => 'Vejatz las [[Special:Protectedpages|directivas]] per mai d’informacion.',
'protectedarticle'            => 'a protegit « [[$1]] »',
'modifiedarticleprotection'   => 'a modificat lo nivèl de proteccion de « [[$1]] »',
'unprotectedarticle'          => 'a desprotegit « [[$1]] »',
'protectsub'                  => '(Protegir « $1 »)',
'confirmprotect'              => 'Confirmar la proteccion',
'protectcomment'              => 'Rason de la proteccion',
'protectexpiry'               => 'Expiracion (expira pas per defaut)',
'protect_expiry_invalid'      => 'Lo temps d’expiracion es invalid',
'protect_expiry_old'          => 'Lo temps d’expiracion ja es passat.',
'unprotectsub'                => '(Desprotegir « $1 »)',
'protect-unchain'             => 'Desblocar las permissions de renomenatge',
'protect-text'                => 'Podètz consultar e modificar lo nivèl de proteccion de la pagina <strong>$1</strong>. Asseguratz-vos que seguissètz las règlas intèrnas.',
'protect-locked-blocked'      => 'Podètz pas modificar lo nivèl de proteccion tant que sètz blocat. Vaquí los reglatges actuals de la pagina <strong>$1</strong> :',
'protect-locked-dblock'       => 'Lo nivèl de proteccion pòt pas èsser modificat perque la banca de donadas es blocada. Vaquí los reglatges actuals de la pagina <strong>$1</strong> :',
'protect-locked-access'       => 'Avètz pas los dreches necessaris per modificar la proteccion de la pagina. Vaquí los reglatges actuals de la pagina <strong>$1</strong> :',
'protect-cascadeon'           => "Aquesta pagina es actualament protegida perque es inclusa dins las paginas seguentas, que son estadas protegidas amb l’opcion « proteccion en cascada » activada. Podètz cambiar lo nivèl de proteccion d'aquesta pagina sens qu'aquò afècte la proteccion en cascada.",
'protect-default'             => 'Pas de proteccion',
'protect-fallback'            => 'Necessita l’abilitacion "$1"',
'protect-level-autoconfirmed' => 'Semiproteccion',
'protect-level-sysop'         => 'Administrators unicament',
'protect-summary-cascade'     => 'proteccion en cascada',
'protect-expiring'            => 'expira lo $1',
'protect-cascade'             => 'Proteccion en cascada - Protegís totas las paginas inclusas dins aquesta.',
'restriction-level'           => 'Nivèl de restriccion',
'minimum-size'                => 'Talha minimom (octets)',
'maximum-size'                => 'Talha maximala',
'pagesize'                    => '(octets)',

# Restrictions (nouns)
'restriction-edit' => 'Modificacion',
'restriction-move' => 'Renomenatge',

# Restriction levels
'restriction-level-sysop'         => 'Proteccion complèta',
'restriction-level-autoconfirmed' => 'Semiproteccion',
'restriction-level-all'           => 'Totes',

# Undelete
'undelete'                     => 'Restablir la pagina escafada',
'undeletepage'                 => 'Veire e restablir la pagina escafada',
'viewdeletedpage'              => 'Istoric de la pagina suprimida',
'undeletepagetext'             => 'Aquestas paginas son estadas escafadas e se tròban dins la corbelha, son totjorn dins la banca de donada e pòdon èsser restablidas.
La corbelha pòt èsser escafada periodicament.',
'undeleteextrahelp'            => "Per restablir totas las versions d'aquesta pagina, daissatz vèrjas totas las casas de marcar, puèi clicatz sus '''''Procedir al restabliment'''''.<br />Per procedir a un restabliment selectiu, marcatz las casas correspondent a las versions que son de restablir, puèi clicatz sus '''''Procedir a la restabliment'''''.<br />En clicant sul boton '''''Reinicializar''''', la boita de resumit e las casas marcadas seràn remesas a zèro.",
'undeleterevisions'            => '$1 revisions archivadas',
'undeletehistory'              => "Se restablissètz la pagina, totas las revisions seràn restablidas dins l'istoric.
Se una pagina novèla amb lo meteis nom es estada creada dempuèi la supression,
las revisions restablidas apareisseràn dins l'istoric anterior e la version correnta serà pas automaticament remplaçada.",
'undeleterevdel'               => 'Lo restabliment serà pas efectuat se, fin finala, la version mai recenta de la pagina serà parcialament suprimida. Dins aqueste cas, devètz deseleccionatz las versions mai recentas (en naut). Las versions dels fichièrs a lasqualas avètz pas accès seràn pas restablidas.',
'undeletehistorynoadmin'       => "Aqueste article es estat suprimit. Lo motiu de la supression es indicat dins lo resumit çai jos, amb los detalhs dels utilizaires que l’an modificat abans sa supression. Lo contengut d'aquestas versions es pas accessible qu’als administrators.",
'undelete-revision'            => 'Version suprimida de $1, lo $2 :',
'undeleterevision-missing'     => 'Version invalida o mancanta. Benlèu avètz un ligam marrit, o la version es estada restablida o suprimida de l’archiu.',
'undeletebtn'                  => 'Restablir !',
'undeletereset'                => 'Reinicializar',
'undeletecomment'              => 'Comentari:',
'undeletedarticle'             => 'restaurat "[[$1]]"',
'undeletedrevisions'           => '$1 version(s) restablida(s)',
'undeletedrevisions-files'     => '$1 versions e $2 fichièr(s) restablits',
'undeletedfiles'               => '$1 {{PLURAL:$1|fichièr restablit|fichièrs restablits}}',
'cannotundelete'               => 'Lo restabliment a pas capitat. Un autre utilizaire a probablament restablit la pagina abans.',
'undeletedpage'                => "<big>'''La pagina $1 es estada restablida'''.</big> 

Consultatz l’[[Special:Log/delete|istoric de las supressions]] per veire las paginas recentament suprimidas e restablidas.",
'undelete-header'              => 'Consultatz l’[[Special:Log/delete|istoric de las supressions]] per veire las paginas recentament suprimidas.',
'undelete-search-box'          => 'Cercar una pagina suprimida',
'undelete-search-prefix'       => 'Mostrar las paginas començant per :',
'undelete-search-submit'       => 'Cercar',
'undelete-no-results'          => 'Cap de pagina correspondent a la recèrca es pas estada trobada dins las archius.',
'undelete-filename-mismatch'   => 'Impossible de restablir lo fichièr amb lo timestamp $1 : fichièr introbable',
'undelete-bad-store-key'       => 'Impossible de restablir lo fichièr amb lo timestamp $1 : lo fichièr èra absent abans la supression.',
'undelete-cleanup-error'       => 'Error al moment de la supression de l’archiu inutilizada « $1 ».',
'undelete-missing-filearchive' => 'Impossible de restablir lo fichièr amb l’ID $1 perque es pas dins la banca de donadas. Benlèu ja i es estat restablit.',
'undelete-error-short'         => 'Error al moment del restabliment del fichièr : $1',
'undelete-error-long'          => "D'errors son estadas rencontradas al moment del restabliment del fichièr : 

$1",

# Namespace form on various pages
'namespace'      => 'Espaci de nom :',
'invert'         => 'Inversar la seleccion',
'blanknamespace' => '(Principal)',

# Contributions
'contributions' => "Contribucions d'aqueste contributor",
'mycontris'     => 'Mas contribucions',
'contribsub2'   => 'Per $1 ($2)',
'nocontribs'    => 'Cap de modificacion correspondenta a aquestes critèris es pas estada trobada.',
'ucnote'        => 'Vaquí los <b>$1</b> darrièrs cambiaments efectuats per aqueste utilizaire al cors dels <b>$2</b> darrièrs jorns.',
'uclinks'       => 'Afichar los $1 darrièrs cambiaments; afichar los $2 darrièrs jorns.',
'uctop'         => ' (darrièra)',
'month'         => 'A partir del mes (e precedents) :',
'year'          => 'A partir de l’annada (e precedentas) :',

'sp-contributions-newest'      => 'Darrièras contribucions',
'sp-contributions-oldest'      => 'Primièras contribucions',
'sp-contributions-newer'       => '$1 precedents',
'sp-contributions-older'       => '$1 seguents',
'sp-contributions-newbies'     => 'Mostrar pas que las contribucions dels utilizaires novèls',
'sp-contributions-newbies-sub' => 'Lista de las contribucions dels utilizaires novèls. Las paginas que son estadas suprimidas son pas afichadas.',
'sp-contributions-blocklog'    => 'Jornal dels blocatges',
'sp-contributions-search'      => 'Cercar las contribucions',
'sp-contributions-username'    => 'Adreça IP o nom d’utilizaire :',
'sp-contributions-submit'      => 'Cercar',

'sp-newimages-showfrom' => 'Afichar los imatges importats dempuèi lo $1',

# What links here
'whatlinkshere'       => 'Paginas ligadas a aquesta',
'whatlinkshere-title' => "Paginas qu'an de ligams puntant vèrs $1",
'whatlinkshere-page'  => 'Pagina :',
'linklistsub'         => '(Lista de ligams)',
'linkshere'           => 'Las paginas çai jos contenon un ligam vèrs aquesta:',
'nolinkshere'         => "Cap de pagina conten pas de ligam vèrs '''[[:$1]]'''.",
'nolinkshere-ns'      => "Cap de pagina conten pas de ligam vèrs '''[[:$1]]''' dins l’espaci de nom causit.",
'isredirect'          => 'pagina de redireccion',
'whatlinkshere-prev'  => '{{PLURAL:$1|precedent|$1 precedents}}',
'whatlinkshere-next'  => '{{PLURAL:$1|seguent|$1 seguents}}',
'whatlinkshere-links' => '← ligams',

# Block/unblock
'blockip'                     => 'Blocar una adreça IP',
'blockiptext'                 => "Utilizatz lo formulari çai jos per blocar l'accès en escritura a partir d'una adreça IP donada.
Una tala mesura deu pas èsser presa pas que per empachar lo vandalisme e en acòrdi amb [[{{MediaWiki:policy-url}}]].
Donatz çai jos una rason precisa (per exemple en indicant las paginas que son estadas vandalizadas).",
'ipaddress'                   => 'Adreça IP',
'ipadressorusername'          => 'Adreça IP o nom d’utilizaire',
'ipbexpiry'                   => 'Durada del blocatge',
'ipbreason'                   => 'Motiu',
'ipbreasonotherlist'          => 'Autra rason',
'ipbreason-dropdown'          => '* Motius de blocatge mai frequents 
** Vandalisme 
** Insercion d’informacions faussas 
** Supression de contengut sens justificacion 
** Insercion repetida de ligams extèrnes publicitaris (spam) 
** Insercion de contengut sens cap de sens 
** Temptativa d’intimidacion o agarriment 
** Abús d’utilizacion de comptes multiples 
** Nom d’utilizaire inacceptable, injuriós o difamant',
'ipbanononly'                 => 'Blocar unicament los utilizaires anonims',
'ipbcreateaccount'            => 'Empachar la creacion de compte',
'ipbemailban'                 => 'Empachar l’utilizaire de mandar de corrièrs electronics',
'ipbenableautoblock'          => 'Blocar automaticament las adreças IP utilizadas per aqueste utilizaire',
'ipbsubmit'                   => 'Blocar aquesta adreça',
'ipbother'                    => 'Autra durada',
'ipboptions'                  => '2 oras:2 hours,1 jorn:1 day,3 jorns:3 days,1 setmana:1 week,2 setmanas:2 weeks,1 mes:1 month,3 meses:3 months,6 meses:6 months,1 an:1 year,indefinidament:infinite',
'ipbotheroption'              => 'autre',
'ipbotherreason'              => 'Motiu diferent o suplementari',
'ipbhidename'                 => "Amagar lo nom d’utilizaire o l’IP de l'istoric de blocatge, de la lista dels blocatges actius e de la lista dels utilizaires",
'badipaddress'                => "L'adreça IP es pas corrècta.",
'blockipsuccesssub'           => 'Blocatge capitat',
'blockipsuccesstext'          => 'L\'adreça IP "$1" es estada blocada.
<br />Podètz consultar sus aquesta [[Special:Ipblocklist|pagina]] la lista de las adreças IP blocadas.',
'ipb-edit-dropdown'           => 'Modificar los motius de blocatge per defaut',
'ipb-unblock-addr'            => 'Desblocar $1',
'ipb-unblock'                 => "Desblocar un compte d'utilizaire o una adreça IP",
'ipb-blocklist-addr'          => 'Veire los blocatges existents per $1',
'ipb-blocklist'               => 'Veire los blocatges existents',
'unblockip'                   => 'Desblocar una adreça IP',
'unblockiptext'               => "Utilizatz lo formulari çai jos per restablir l'accès en escritura
a partir d'una adreça IP precedentament blocada.",
'ipusubmit'                   => 'Desblocar aquesta adreça',
'unblocked'                   => '[[User:$1|$1]] es estat desblocat',
'unblocked-id'                => 'Lo blocatge $1 es estat levat',
'ipblocklist'                 => 'Lista de las adreças IP blocadas',
'ipblocklist-legend'          => 'Cercar un utilizaire blocat',
'ipblocklist-username'        => 'Nom de l’utilizaire o adreça IP :',
'ipblocklist-submit'          => 'Recèrca',
'blocklistline'               => '$1, $2 a blocat $3 ($4)',
'infiniteblock'               => 'permanent',
'expiringblock'               => 'expira lo $1',
'anononlyblock'               => 'utilizaire non enregistrat unicament',
'noautoblockblock'            => 'Blocatge automatic desactivat',
'createaccountblock'          => 'La creacion de compte es blocada.',
'emailblock'                  => 'e-mail blocat',
'ipblocklist-empty'           => 'La lista dels blocatges es voida.',
'ipblocklist-no-results'      => 'L’adreça IP o l’utilizaire es pas esta blocat.',
'blocklink'                   => 'blocar',
'unblocklink'                 => 'desblocar',
'contribslink'                => 'contribucions',
'autoblocker'                 => 'Autoblocat perque pertejatz una adreça IP amb "[[User:$1|$1]]". Rason : "\'\'\'$2\'\'\'".',
'blocklogpage'                => 'Istoric dels blocatges',
'blocklogentry'               => 'a blocat « [[$1]] » - durada : $2 $3',
'blocklogtext'                => 'Aquò es la traça dels blocatges e desblocatges dels utiliaires. Las adreças IP automaticament blocadas son pas listadas. Consultatz la [[Special:Ipblocklist|lista dels utilizaires blocats]] per veire qui es actualament efectivament blocat.',
'unblocklogentry'             => 'a desblocat « $1 »',
'block-log-flags-anononly'    => 'utilizaires anonims solament',
'block-log-flags-nocreate'    => 'creacion de compte interdicha',
'block-log-flags-noautoblock' => 'autoblocatge de las IP desactivat',
'block-log-flags-noemail'     => 'e-mail blocat',
'range_block_disabled'        => "Lo blocatge de plajas d'IP es estat desactivat.",
'ipb_expiry_invalid'          => 'Temps d’expiracion invalid.',
'ipb_already_blocked'         => '« $1 » ja es blocat',
'ipb_cant_unblock'            => 'Error : Lo blocatge d’ID $1 existís pas. Es possible qu’un desblocatge ja siá estat efectuat.',
'ip_range_invalid'            => 'Blòt IP incorrècte.',
'proxyblocker'                => 'Blocaire de proxy',
'proxyblocker-disabled'       => 'Aquesta foncion es desactivada.',
'proxyblockreason'            => "Vòstra ip es estada blocada perque s’agís d’un proxy dobert. Mercé de contactar vòstre fornidor d’accès internet o vòstre supòrt tecnic e de l’informar d'aqueste problèma de seguretat.",
'proxyblocksuccess'           => 'Acabat.',
'sorbsreason'                 => 'Vòstra adreça IP es listada en tant que proxy dobert DNSBL.',
'sorbs_create_account_reason' => 'Vòstra adreça IP es listada en tant que proxy dobert DNSBL. Podètz pas crear un compte',

# Developer tools
'lockdb'              => 'Varrolhar la banca',
'unlockdb'            => 'Desvarrolhar la banca',
'lockdbtext'          => "Lo clavatge de la banca de donadas empacharà totes los utilizaires de modificar las paginas, de salvagardar lors preferéncias, de modificar lor lista de seguit e d'efectuar totas las autras operacions necessitant de modificacions dins la banca de donadas.
Confirmatz qu'es plan çò que volètz far e que desblocarètz la banca tre que vòstra operacion de mantenença serà acabada.",
'unlockdbtext'        => "Lo desclavatge de la banca de donadas permetrà a totes los utilizaires de modificar tornarmai de paginas, de metre a jorn lors preferéncias e lor lista de seguit, e mai d'efectuar las autras operacions necessitant de modificacions dins la banca de donadas.
Confirmatz qu'es plan çò que volètz far.",
'lockconfirm'         => 'Òc, confirmi que desiri clavar la banca de donadas.',
'unlockconfirm'       => 'Òc, confirmi que desiri desclavar la banca de donadas.',
'lockbtn'             => 'Varrolhar la banca',
'unlockbtn'           => 'Desvarrolhar la banca',
'locknoconfirm'       => 'Avètz pas marcat la casa de confirmacion.',
'lockdbsuccesssub'    => 'Varrolhatge de la banca capitat.',
'unlockdbsuccesssub'  => 'Banca desvarrolhada.',
'lockdbsuccesstext'   => 'La banca de donadas es clavada.

<br />Doblidetz pas de la desclavar quand aurètz acabat vòstra operacion de mantenença.',
'unlockdbsuccesstext' => 'La banca de donadas de {{SITENAME}} es desvarrolhada.',
'lockfilenotwritable' => 'Lo fichièr de blocatge de la banca de donadas es pas inscriptible. Per blocar o desblocar la banca de donadas, devètz poder escriure sul serveire web.',
'databasenotlocked'   => 'La banca de donadas es pas clavada.',

# Move page
'movepage'                => 'Desplaçar un article',
'movepagetext'            => "Utilizatz lo formulari çai jos per renomenar un article, en desplaçant totas sas versions anterioras vèrs lo nom novèl.
Lo títol precedent devendrà una pagina de redireccion vèrs lo títol novèl.
Los ligams vèrs lo títol ancian seràn pas modificats e la pagina de discussion, s'existís, serà pas desplaçada.<br />
<b>ATENCION !</b>
Se pòt agir d'un cambiament radical e inesperat per un article sovent consultat;
asseguratz-vos que ne comprenètz plan las consequéncias abans de procedir.",
'movepagetalktext'        => "La pagina de discussion associada, se presenta, serà automaticament desplaçada amb '''en defòra de se:'''
*Desplaçatz una pagina vèrs un autre espaci,
*Una pagina de discussion existís ja amb lo nom novèl, o
*Avètz deseleccionat lo boton çai jos.

Dins aqueste cas, deurètz desplaçar o fusionar la pagina manualament se o volètz.",
'movearticle'             => "Desplaçar l'article",
'movenologin'             => 'Non connectat',
'movenologintext'         => "Per poder desplaçar un article, devètz èsser [[Special:Userlogin|connectat]]
en tant qu'utilizaire enregistrat.",
'movenotallowed'          => 'Avètz pas la permission de renomenar de paginas sus aqueste wiki.',
'newtitle'                => 'Títol novèl',
'move-watch'              => 'Seguir aquesta pagina',
'movepagebtn'             => "Desplaçar l'article",
'pagemovedsub'            => 'Desplaçament capitat',
'movepage-moved'          => 'La pagina « $1 » <small>([[Special:Whatlinkshere/$3|ligams]])</small> es estada renomenada en « $2 » <small>([[Special:Whatlinkshere/$4|ligams]])</small>. Verificatz qu’existís pas cap de redireccion dobla, e corregissetz-las se mestièr fa.', # The two titles are passed in plain text as $3 and $4 to allow additional goodies in the message.
'articleexists'           => "Existís ja un article portant aqueste títol, o lo títol qu'avètz causit es pas valid.
Causissètz-ne un autre.",
'talkexists'              => "La pagina ela-meteissa es estada desplaçada amb succès, mas
la pagina de discussion a pas pogut èsser desplaçada perque n'existissiá ja una
jol nom novèl. Se vos plai, fusionatz-las manualament.",
'movedto'                 => 'desplaçat vèrs',
'movetalk'                => 'Desplaçar tanben la pagina "discussion", se fa mestièr.',
'talkpagemoved'           => 'La pagina de discussion correspondenta tanben es estada desplaçada.',
'talkpagenotmoved'        => 'La pagina de discussion correspondenta es <strong>pas</strong> estada desplaçada.',
'1movedto2'               => 'a desplaçat [[$1]] vèrs [[$2]]',
'1movedto2_redir'         => 'a redirigit [[$1]] vèrs [[$2]]',
'movelogpage'             => 'Istoric dels renomenatges',
'movelogpagetext'         => 'Vaquí la lista de las darrièras paginas renomenadas.',
'movereason'              => 'Rason del renomenatge',
'revertmove'              => 'anullar',
'delete_and_move'         => 'Suprimir e renomenar',
'delete_and_move_text'    => '==Supression requesida== 

L’article de destinacion « [[$1]] » existís ja. Volètz lo suprimir per permetre lo renomenatge ?',
'delete_and_move_confirm' => 'Òc, accèpti de suprimir la pagina de destinacion per permetre lo renomenatge.',
'delete_and_move_reason'  => 'Pagina suprimida per permetre un renomenatge',
'selfmove'                => 'Los títols d’origina e de destinacion son los meteisses : impossible de renomenar una pagina sus ela-meteissa.',
'immobile_namespace'      => 'Lo títol de destinacion es d’un tipe especial ; es impossible de renomenar de paginas vèrs aqueste espaci de noms.',

# Export
'export'            => 'Exportar de paginas',
'exporttext'        => "Podètz exportar en XML lo tèxt e l’istoric d’una pagina o d’un ensemble de paginas; lo resultat pòt alara èsser importat dins un autre wiki foncionant amb lo logicial MediaWiki.

Per exportar de paginas, entratz lors títols dins la boita de tèxt çai jos, un títol per linha, e seleccionatz se o desiratz o pas la version actuala amb totas las versions ancianas, amb la pagina d’istoric, o simplament la pagina actuala amb d'informacions sus la darrièra modificacion.

Dins aqueste darrièr cas, podètz tanben utilizar un ligam, coma [[{{ns:Special}}:Export/{{Mediawiki:mainpage}}]] per la pagina {{Mediawiki:mainpage}}.",
'exportcuronly'     => 'Exportar unicament la version correnta sens l’istoric complet',
'exportnohistory'   => "---- 
'''Nòta :''' l’exportacion complèta de l’istoric de las paginas amb l’ajuda d'aqueste formulari es estada desactivada per de rasons de performàncias.",
'export-submit'     => 'Exportar',
'export-addcattext' => 'Ajustar las paginas de la categoria :',
'export-addcat'     => 'Ajustar',
'export-download'   => 'Salvagardar en tant que fichièr',

# Namespace 8 related
'allmessages'               => 'Lista dels messatges del sistèma',
'allmessagesname'           => 'Nom del camp',
'allmessagesdefault'        => 'Messatge per defaut',
'allmessagescurrent'        => 'Messatge actual',
'allmessagestext'           => 'Aquò es la lista de totes los messatges disponibles dins l’espaci MediaWiki',
'allmessagesnotsupportedDB' => '<b>Special:Allmessages</b> es pas disponible perque <b>$wgUseDatabaseMessages</b> es desactivat.',
'allmessagesfilter'         => 'Filtre d’expression racionala :',
'allmessagesmodified'       => 'Afichar pas que las modificacions',

# Thumbnails
'thumbnail-more'           => 'Agrandir',
'missingimage'             => '<b>Imatge mancant</b><br /><i>$1</i>',
'filemissing'              => 'Fichièr absent',
'thumbnail_error'          => 'Error al moment de la creacion de la miniatura : $1',
'djvu_page_error'          => 'Pagina DjVu fòra limits',
'djvu_no_xml'              => "Impossible d’obténer l'XML pel fichièr DjVu",
'thumbnail_invalid_params' => 'Paramètres de la miniatura invalids',
'thumbnail_dest_directory' => 'Impossible de crear lo repertòri de destinacion',

# Special:Import
'import'                     => 'Importar de paginas',
'importinterwiki'            => 'Impòrt inter-wiki',
'import-interwiki-text'      => "Seleccionatz un wiki e un títol de pagina d'importar.
Las datas de las versions e los noms dels editors seràn preservats.
Totas las accions d’importacion interwiki son conservadas dins lo [[Special:Log/import|jornal d’impòrt]].",
'import-interwiki-history'   => "Copiar totas las versions de l'istoric d'aquesta pagina",
'import-interwiki-submit'    => 'Importar',
'import-interwiki-namespace' => 'Transferir las paginas dins l’espaci de nom :',
'importtext'                 => 'Exportatz lo fichièr dempuèi lo wiki d’origina en utilizant l’esplech Special:Export, lo salvagardar sus vòstre disc dur e lo copiar aicí.',
'importstart'                => 'Impòrt de las paginas...',
'importnopages'              => "Cap de pagina d'importar.",
'importfailed'               => 'Fracàs de l’impòrt : $1',
'importunknownsource'        => 'Tipe de la font d’impòrt desconegut',
'importcantopen'             => "Impossible de dobrir lo fichièr d'importar",
'importbadinterwiki'         => 'Marrit ligam interwiki',
'importnotext'               => 'Void o sens tèxt',
'importsuccess'              => "L'impòrt a capitat!",
'importhistoryconflict'      => "I a un conflicte dins l'istoric de las versions (aquesta pagina a pogut èsser importada de per abans).",
'importnosources'            => 'Cap de font inter-wiki es pas estada definida e la còpia dirècta d’istoric es desactivada.',
'importnofile'               => 'Cap de fichièr es pas estat importat.',
'importuploaderror'          => "L’impòrt del fichièr a pas capitat : es possible qu'aqueste depasse la talha autorizada.",

# Import log
'importlogpage'                    => 'Istoric de las importacions de paginas',
'importlogpagetext'                => 'Impòrts administratius de paginas amb l’istoric a partir dels autres wikis.',
'import-logentry-upload'           => 'a importat (telecargament) $1',
'import-logentry-upload-detail'    => '$1 version(s)',
'import-logentry-interwiki'        => '$1 version(s) dempuèi $2',
'import-logentry-interwiki-detail' => '$1 version(s) dempuèi $2',

# Tooltip help for the actions
'tooltip-pt-userpage'             => "Ma pagina d'utilizaire",
'tooltip-pt-anonuserpage'         => "La pagina d'utilizare de l’IP amb laquala contribuissètz",
'tooltip-pt-mytalk'               => 'Ma pagina de discussion',
'tooltip-pt-anontalk'             => 'La pagina de discussion per aquesta adreça IP',
'tooltip-pt-preferences'          => 'Mas preferéncias',
'tooltip-pt-watchlist'            => 'La lista de las paginas que seguissètz',
'tooltip-pt-mycontris'            => 'Lista de mas contribucions',
'tooltip-pt-login'                => 'Sètz convidat a vos identificar, mas es pas obligatòri.',
'tooltip-pt-anonlogin'            => 'Sètz convidat a vos identificar, mas es pas obligatòri.',
'tooltip-pt-logout'               => 'Se desconnectar',
'tooltip-ca-talk'                 => "Discussion a prepais d'aquesta pagina",
'tooltip-ca-edit'                 => 'Podètz modificar aquesta pagina. Mercé de previsualizar abans d’enregistrar.',
'tooltip-ca-addsection'           => 'Ajustar un comentari a aquesta discussion.',
'tooltip-ca-viewsource'           => 'Aquesta pagina es protegida. Podètz çaquelà ne veire lo contengut.',
'tooltip-ca-history'              => "Los autors e versions precedentas d'aquesta pagina.",
'tooltip-ca-protect'              => 'Protegir aquesta pagina',
'tooltip-ca-delete'               => 'Suprimir aquesta pagina',
'tooltip-ca-undelete'             => 'Restablir aquesta pagina',
'tooltip-ca-move'                 => 'Renomenar aquesta pagina',
'tooltip-ca-watch'                => 'Ajustatz aquesta pagina a vòstra lista de seguit',
'tooltip-ca-unwatch'              => 'Levatz aquesta pagina de vòstra lista de seguit',
'tooltip-search'                  => 'Cercar dins {{SITENAME}}',
'tooltip-search-go'               => 'Anar vèrs una pagina portant exactament aqueste nom se existís.',
'tooltip-search-fulltext'         => 'Recercar las paginas comportant aqueste tèxt.',
'tooltip-p-logo'                  => 'Pagina principala',
'tooltip-n-mainpage'              => 'Visitatz la pagina principala',
'tooltip-n-portal'                => 'A prepaus del projècte',
'tooltip-n-currentevents'         => "Trobar d'informacions suls eveniments actuals",
'tooltip-n-recentchanges'         => 'Lista dels darrièrs cambiaments sul wiki.',
'tooltip-n-randompage'            => "Afichar una pagina a l'azard",
'tooltip-n-help'                  => 'Ajuda.',
'tooltip-n-sitesupport'           => 'Sostenètz lo projècte',
'tooltip-t-whatlinkshere'         => 'Lista de las paginas ligadas a aquesta',
'tooltip-t-recentchangeslinked'   => 'Lista dels darrièrs cambiaments de las paginas ligadas a aquesta',
'tooltip-feed-rss'                => 'Flus RSS per aquesta pagina',
'tooltip-feed-atom'               => 'Flus Atom per aquesta pagina',
'tooltip-t-contributions'         => "Veire la lista de las contribucions d'aqueste utilizaire",
'tooltip-t-emailuser'             => 'Mandar un corrièr electronic a aqueste utilizaire',
'tooltip-t-upload'                => 'Importar un imatge o fichièr mèdia sul serveire',
'tooltip-t-specialpages'          => 'Lista de totas las paginas especialas',
'tooltip-t-print'                 => "Version imprimibla d'aquesta pagina",
'tooltip-t-permalink'             => 'Ligam permanent vèrs aquesta version de la pagina',
'tooltip-ca-nstab-main'           => 'Veire l’article',
'tooltip-ca-nstab-user'           => "Veire la pagina d'utilizaire",
'tooltip-ca-nstab-media'          => 'Veire la pagina del mèdia',
'tooltip-ca-nstab-special'        => 'Aquò es una pagina especiala, podètz pas la modificar.',
'tooltip-ca-nstab-project'        => 'Veire la pagina del projècte',
'tooltip-ca-nstab-image'          => 'Veire la pagina de l’imatge',
'tooltip-ca-nstab-mediawiki'      => 'Veire lo messatge del sistèma',
'tooltip-ca-nstab-template'       => 'Veire lo modèl',
'tooltip-ca-nstab-help'           => 'Veire la pagina d’ajuda',
'tooltip-ca-nstab-category'       => 'Veire la pagina de la categoria',
'tooltip-minoredit'               => 'Marcar mas modificacions coma un cambiament menor',
'tooltip-save'                    => 'Salvagardar vòstras modificacions',
'tooltip-preview'                 => 'Mercé de previsualizar vòstras modificacions abans de salvagardar!',
'tooltip-diff'                    => "Permet de visualizar los cambiaments qu'avètz efectuats",
'tooltip-compareselectedversions' => "Afichar las diferéncias entre doas versions d'aquesta pagina",
'tooltip-watch'                   => 'Ajustar aquesta pagina a vòstra lista de seguit',
'tooltip-recreate'                => 'Recrear la pagina, quitament se es estada escafada',

# Stylesheets
'common.css'   => '/** Lo CSS plaçat aicí serà aplicat a totas las aparéncias. */',
'monobook.css' => '/* Lo CSS plaçat aicí afectarà los utilizaires del skin Monobook */',

# Scripts
'common.js'   => '/* Un JavaScript quin que siá aicí serà cargat per un utilizaire quin que siá e per cada pagina accedida. */',
'monobook.js' => '/* Perimit; utilizatz [[MediaWiki:common.js]] */',

# Metadata
'nodublincore'      => 'Las metadonadas « Dublin Core RDF » son desactivadas sus aqueste serveire.',
'nocreativecommons' => 'Las donadas meta « Creative Commons RDF » son desactivadas sus aqueste serveire.',
'notacceptable'     => 'Aqueste serveire wiki pòt pas fornir las donadas dins un format que vòstre client es capable de legir.',

# Attribution
'anonymous'        => 'Utilizaire(s) pas enregistrat(s) de {{SITENAME}}',
'siteuser'         => 'Utilizaire $1 de {{SITENAME}}',
'lastmodifiedatby' => 'Aquesta pagina es estada modificada pel darrièr còp lo $1 a $2 per $3.', # $1 date, $2 time, $3 user
'and'              => 'e',
'othercontribs'    => "Contribucions de l'utilizaire $1.",
'others'           => 'autres',
'siteusers'        => 'Utilizaire(s) $1',
'creditspage'      => 'Pagina de crèdits',
'nocredits'        => 'I a pas d’informacions d’atribucion disponiblas per aquesta pagina.',

# Spam protection
'spamprotectiontitle'    => 'Pagina protegida automaticament per causa de spam',
'spamprotectiontext'     => "La pagina qu'avètz ensajat de salvagardar es estada blocada pel filtre anti-spam. Aquò es probablament causat per un ligam vèrs un sit extèrn.",
'spamprotectionmatch'    => 'Lo tèxt seguent a desenclavaat lo detector de spam : $1',
'subcategorycount'       => '{{PLURAL:$1|Una soscategoria es listada |$1 soscategorias son listadas}} çai jos. Se un ligam « (200 precedents) » o « (200 seguents) » es present çai sus, pòt menar a d’autras soscategorias.',
'categoryarticlecount'   => 'I a {{PLURAL:$1|un article|$1 articles}} dins aquesta categoria.',
'category-media-count'   => 'I a {{plural:$1|un fichièr|$1 fichièrs}} multimèdia dins aquesta categoria.',
'listingcontinuesabbrev' => '(seguida)',
'spambot_username'       => 'Netejatge de spam MediaWiki',
'spam_reverting'         => 'Restauracion de la darrièra version contenent pas de ligam vèrs $1',
'spam_blanking'          => 'Totas las versions que contenon de ligams vèrs $1 son blanquidas',

# Info page
'infosubtitle'   => 'Informacions per la pagina',
'numedits'       => 'Nombre de modificacions : $1',
'numtalkedits'   => 'Nombre de modificacions (pagina de discussion) : $1',
'numwatchers'    => "Nombre de contributors qu'an la pagina dins lor lista de seguit : $1",
'numauthors'     => 'Nombre d’autors distints : $1',
'numtalkauthors' => 'Nombre d’autors distints (pagina de discussion) : $1',

# Math options
'mw_math_png'    => 'Totjorn produire un imatge PNG',
'mw_math_simple' => 'HTML se plan simpla, si que non PNG',
'mw_math_html'   => 'HTML se possible, si que non PNG',
'mw_math_source' => "Daissar lo còde TeX d'origina",
'mw_math_modern' => 'Pels navegaires modèrnes',
'mw_math_mathml' => 'MathML',

# Patrolling
'markaspatrolleddiff'                 => 'Marcar coma essent pas un vandalisme',
'markaspatrolledtext'                 => 'Marcar aqueste article coma non vandalizat',
'markedaspatrolled'                   => 'Marcat coma non vandalizat',
'markedaspatrolledtext'               => 'La version seleccionada es estada marcada coma non vandalizada.',
'rcpatroldisabled'                    => 'La foncion de patrolha dels darrièrs cambiaments es pas activada.',
'rcpatroldisabledtext'                => 'La foncionalitat de susvelhança dels darrièrs cambiaments es pas activada.',
'markedaspatrollederror'              => 'Pòt pas èsser marcat coma non vandalizat',
'markedaspatrollederrortext'          => 'Devètz seleccionar una version per poder la marcar coma non vandalizada.',
'markedaspatrollederror-noautopatrol' => 'Avètz pas lo drech de marcar vòstras pròprias modificacions coma susvelhadas.',
'nppatroldisabled'                    => 'Patrolha de las paginas novèlas desactivada.',
'nppatroldisabledtext'                => 'La Patrolha de las paginas novèlas es actualament desactivada.',

# Patrol log
'patrol-log-page' => 'Istoric de las versions patrolhadas',
'patrol-log-line' => 'a marcat la version $1 de $2 coma verificada $3',
'patrol-log-diff' => '$1',

# Image deletion
'deletedrevision'                 => 'La version anciana $1 es estada suprimida.',
'filedeleteerror-short'           => 'Error al moment de la supression del fichièr : $1',
'filedeleteerror-long'            => "D'errors son estadas rencontradas al moment de la supression del fichièr :

$1",
'filedelete-missing'              => 'Lo fichièr « $1 » pòt pas èsser suprimit perque existís pas.',
'filedelete-old-unregistered'     => 'La revision del fichièr especificat « $1 » es pas dins la banca de donadas.',
'filedelete-current-unregistered' => 'Lo fichièr especificat « $1 » es pas dins la banca de donadas.',
'filedelete-archive-read-only'    => 'Lo dorsièr d’archivatge « $1 » es pas modificable pel serveire.',

# Browsing diffs
'previousdiff' => '← Dif precedenta',
'nextdiff'     => 'Dif seguenta →',

# Media information
'mediawarning'         => '<b>Atencion</b>: Aqueste fichièr pòt conténer de còde malvolent, vòstre sistèma pòt èsser mes en dangièr per son execucion. <hr />',
'imagemaxsize'         => 'Format maximal pels imatges dins las paginas de descripcion d’imatges :',
'thumbsize'            => 'Talha de la miniatura :',
'widthheightpage'      => '$1×$2, $3 paginas',
'file-info'            => 'Talha del fichièr: $1, tipe MIME: $2',
'file-info-size'       => '($1 × $2 pixel, talha del fichièr: $3, tipe MIME: $4)',
'file-nohires'         => '<small>Pas de resolucion mai nauta disponibla.</small>',
'svg-long-desc'        => '(Fichièr SVG, resolucion de $1 × $2 pixels, talha : $3)',
'show-big-image'       => 'Imatge en resolucion mai nauta',
'show-big-image-thumb' => "<small>Talha d'aqueste apercebut : $1 × $2 pixels</small>",

# Special:Newimages
'newimages'    => 'Galariá de fichièrs novèls',
'showhidebots' => '($1 bòts)',
'noimages'     => "Cap imatge d'afichar.",

# Bad image list
'bad_image_list' => "Lo format es lo seguent:
Solament las linhas començant per * son presas en compte. Lo primièr ligam de la linha es lo vèrs un marrit imatge. Los autres ligams sus la meteissa linha son considerats coma d'excepcions, per exemple d'articles sulsquals l'imatge deu aparéisser.",

# Metadata
'metadata'          => 'Metadonadas',
'metadata-help'     => "Aqueste fichièr conten d'informacions suplementàrias probablament ajustadas per l’aparelh de fòto o l'escanèr que l’a producha. Se lo fichièr es estat modificat, cèrts detalhs pòdon refletar pas l’imatge modificat.",
'metadata-expand'   => 'Mostrar las informacions detalhadas',
'metadata-collapse' => 'Amagar las informacions detalhadas',
'metadata-fields'   => 'Los camps de metadonadas d’EXIF listats dins aqueste message seràn incluses dins la pagina de descripcion de l’imatge quand la taula de metadonadas serà reduccha. Los autres camps seràn amagats per defaut.
* make
* model
* datetimeoriginal
* exposuretime
* fnumber
* focallength',

# EXIF tags
'exif-imagewidth'                  => 'Largor',
'exif-imagelength'                 => 'Nautor',
'exif-bitspersample'               => 'Bits per compausanta',
'exif-compression'                 => 'Tipe de compression',
'exif-photometricinterpretation'   => 'Composicion dels pixels',
'exif-orientation'                 => 'Orientacion',
'exif-samplesperpixel'             => 'Nombre de compausants',
'exif-planarconfiguration'         => 'Arrengament de las donadas',
'exif-ycbcrsubsampling'            => 'Taus d’escandalhatge de las compausantas de la crominança',
'exif-ycbcrpositioning'            => 'Posicionament YCbCr',
'exif-xresolution'                 => 'Resolucion de l’imatge en largor',
'exif-yresolution'                 => 'Resolucion de l’imatge en nautor',
'exif-resolutionunit'              => 'Unitats de resolucion X e Y',
'exif-stripoffsets'                => 'Emplaçament de las donadas de l’imatge',
'exif-rowsperstrip'                => 'Nombre de linhas per benda',
'exif-stripbytecounts'             => 'Talha en octets per benda',
'exif-jpeginterchangeformat'       => 'Posicion del SOI JPEG',
'exif-jpeginterchangeformatlength' => 'Talha en octet de las donadas JPEG',
'exif-transferfunction'            => 'Foncion de transferiment',
'exif-whitepoint'                  => 'Cromaticitat del punt blanc',
'exif-primarychromaticities'       => 'Cromaticitats de las colors primàrias',
'exif-ycbcrcoefficients'           => 'Coeficients de la matritz de transformacion de l’espaci colorimetric',
'exif-referenceblackwhite'         => 'Valors de referéncia negre e blanc',
'exif-datetime'                    => 'Data e ora de cambiament del fichièr',
'exif-imagedescription'            => 'Títol de l’imatge',
'exif-make'                        => 'Fabricant de l’aparelh',
'exif-model'                       => 'Modèl de l’aparelh',
'exif-software'                    => 'Logicial utilizat',
'exif-artist'                      => 'Autor',
'exif-copyright'                   => 'Detentor del copyright',
'exif-exifversion'                 => 'Version exif',
'exif-flashpixversion'             => 'Version Flashpix suportada',
'exif-colorspace'                  => 'Espaci colorimetric',
'exif-componentsconfiguration'     => 'Significacion de cada compausanta',
'exif-compressedbitsperpixel'      => 'Mòde de compression de l’imatge',
'exif-pixelydimension'             => 'Largor d’imatge valida',
'exif-pixelxdimension'             => 'Nautor d’imatge valida',
'exif-makernote'                   => 'Nòtas del fabricant',
'exif-usercomment'                 => 'Comentaris',
'exif-relatedsoundfile'            => 'Fichièr audiò associat',
'exif-datetimeoriginal'            => 'Data e ora de la generacion de donadas',
'exif-datetimedigitized'           => 'Data e ora de numerizacion',
'exif-subsectime'                  => 'Data de darrièr cambiament',
'exif-subsectimeoriginal'          => 'Data de la presa originala',
'exif-subsectimedigitized'         => 'Data de la numerizacion',
'exif-exposuretime'                => "Temps d'exposicion",
'exif-exposuretime-format'         => '$1 seg ($2)',
'exif-fnumber'                     => 'Focala',
'exif-exposureprogram'             => 'Programa d’exposicion',
'exif-spectralsensitivity'         => 'Sensibilitat espectrala',
'exif-isospeedratings'             => 'Sensibilitat ISO',
'exif-oecf'                        => 'Factor de conversion optoelectronic',
'exif-shutterspeedvalue'           => 'Velocitat d’obturacion',
'exif-aperturevalue'               => 'Dobertura',
'exif-brightnessvalue'             => 'Luminositat',
'exif-exposurebiasvalue'           => 'Correccion d’exposicion',
'exif-maxaperturevalue'            => 'Camp de dobertura maximal',
'exif-subjectdistance'             => 'Distància del subjècte',
'exif-meteringmode'                => 'Mòde de mesura',
'exif-lightsource'                 => 'Font de lutz',
'exif-focallength'                 => 'Longor de focala',
'exif-subjectarea'                 => 'Emplaçament del subjècte',
'exif-flashenergy'                 => 'Energia del flash',
'exif-spatialfrequencyresponse'    => 'Responsa en frequéncia espaciala',
'exif-focalplanexresolution'       => 'Resolucion X focala plana',
'exif-focalplaneyresolution'       => 'Resolucion Y focala plana',
'exif-focalplaneresolutionunit'    => 'Unitat de resolucion de focala plana',
'exif-subjectlocation'             => 'Posicion del subjècte',
'exif-exposureindex'               => 'Indèx d’exposicion',
'exif-sensingmethod'               => 'Metòde de deteccion',
'exif-filesource'                  => 'Font del fichièr',
'exif-scenetype'                   => 'Tipe de scèna',
'exif-cfapattern'                  => 'Matritz de filtratge de color',
'exif-customrendered'              => 'Tractament d’imatge personalizat',
'exif-exposuremode'                => 'Mòde d’exposicion',
'exif-whitebalance'                => 'Balança dels blancs',
'exif-digitalzoomratio'            => 'Taus d’agrandiment numeric (zoom)',
'exif-focallengthin35mmfilm'       => 'Longor de focala per un filme 35 mm',
'exif-scenecapturetype'            => 'Tipe de captura de la scèna',
'exif-gaincontrol'                 => 'Contraròtle de luminositat',
'exif-saturation'                  => 'Saturacion',
'exif-sharpness'                   => 'Netetat',
'exif-devicesettingdescription'    => 'Descripcion de la configuracion del dispositiu',
'exif-subjectdistancerange'        => 'Distància del subjècte',
'exif-imageuniqueid'               => 'Identificant unic de l’imatge',
'exif-gpsversionid'                => 'Version del tag GPS',
'exif-gpslatituderef'              => 'Latitud Nòrd o Sud',
'exif-gpslatitude'                 => 'Latitud',
'exif-gpslongituderef'             => 'Longitud Èst o Oèst',
'exif-gpslongitude'                => 'Longitud',
'exif-gpsaltituderef'              => 'Referéncia d’altitud',
'exif-gpsaltitude'                 => 'Altitud',
'exif-gpstimestamp'                => 'Ora GPS (relòtge atomic)',
'exif-gpssatellites'               => 'Satellits utilizats per la mesura',
'exif-gpsstatus'                   => 'Estatut receptor',
'exif-gpsmeasuremode'              => 'Mòde de mesura',
'exif-gpsdop'                      => 'Precision de la mesura',
'exif-gpsspeedref'                 => 'Unitat de velocitat',
'exif-gpsspeed'                    => 'Velocitat del receptor GPS',
'exif-gpstrackref'                 => 'Referéncia per la direccion del movement',
'exif-gpstrack'                    => 'Direccion del movement',
'exif-gpsimgdirectionref'          => 'Referéncia per l’orientacion de l’imatge',
'exif-gpsimgdirection'             => 'Direccion de l’imatge',
'exif-gpsmapdatum'                 => 'Sistèma geodesic utilizat',
'exif-gpsdestlatituderef'          => 'Referéncia per la latitud de la destinacion',
'exif-gpsdestlatitude'             => 'Latitud de la destinacion',
'exif-gpsdestlongituderef'         => 'Referéncia per la longitud de la destinacion',
'exif-gpsdestlongitude'            => 'Longitud de la destinacion',
'exif-gpsdestbearingref'           => 'Referéncia pel relevament de la destinacion',
'exif-gpsdestbearing'              => 'Relevament de la destinacion',
'exif-gpsdestdistanceref'          => 'Referéncia per la distància de la destinacion',
'exif-gpsdestdistance'             => 'Distància a la destinacion',
'exif-gpsprocessingmethod'         => 'Nom del metòde de tractament del GPS',
'exif-gpsareainformation'          => 'Nom de la zòna GPS',
'exif-gpsdatestamp'                => 'Data GPS',
'exif-gpsdifferential'             => 'Correccion diferenciala GPS',

# EXIF attributes
'exif-compression-1' => 'Pas compressat',

'exif-unknowndate' => 'Data desconeguda',

'exif-orientation-2' => 'Inversada orizontalament', # 0th row: top; 0th column: right
'exif-orientation-3' => 'Virada de 180°', # 0th row: bottom; 0th column: right
'exif-orientation-4' => 'Inversada verticalament', # 0th row: bottom; 0th column: left
'exif-orientation-5' => 'Virada de 90° a esquèrra e inversada verticalament', # 0th row: left; 0th column: top
'exif-orientation-6' => 'Virada de 90° a drecha', # 0th row: right; 0th column: top
'exif-orientation-7' => 'Virada de 90° a drecha e inversada verticalament', # 0th row: right; 0th column: bottom
'exif-orientation-8' => 'Virada de 90° a esquèrra', # 0th row: left; 0th column: bottom

'exif-planarconfiguration-1' => 'Donadas atenentas',
'exif-planarconfiguration-2' => 'Donadas separadas',

'exif-componentsconfiguration-0' => 'existís pas',

'exif-exposureprogram-0' => 'Indefinit',
'exif-exposureprogram-2' => 'Programa normal',
'exif-exposureprogram-3' => 'Prioritat a la dobertura',
'exif-exposureprogram-4' => 'Prioritat a l’obturacion',
'exif-exposureprogram-5' => 'Programa creacion (preferéncia a la pregondor de camp)',
'exif-exposureprogram-6' => 'Programa accion (preferéncia a la velocitat d’obturacion)',
'exif-exposureprogram-7' => 'Mòde retrach (per clichats de prèp amb arrièr plan vague)',
'exif-exposureprogram-8' => 'Mòde paisatge (per de clichats de paisatges nets)',

'exif-subjectdistance-value' => '$1 mètres',

'exif-meteringmode-0'   => 'Desconegut',
'exif-meteringmode-1'   => 'Mejana',
'exif-meteringmode-2'   => 'Mesura centrala mejana',
'exif-meteringmode-3'   => 'Espòt',
'exif-meteringmode-4'   => 'MultiEspòt',
'exif-meteringmode-5'   => 'Paleta',
'exif-meteringmode-6'   => 'Parcial',
'exif-meteringmode-255' => 'Autra',

'exif-lightsource-0'   => 'Desconeguda',
'exif-lightsource-1'   => 'Lutz del jorn',
'exif-lightsource-3'   => 'Tungstèn (lum incandescent)',
'exif-lightsource-9'   => 'Temps clar',
'exif-lightsource-10'  => 'Temps ennivolat',
'exif-lightsource-11'  => 'Ombra',
'exif-lightsource-12'  => 'Esclairatge fluorescent lutz del jorn (D 5700 – 7100K)',
'exif-lightsource-13'  => 'Esclairatge fluorescent blanc (N 4600 – 5400K)',
'exif-lightsource-14'  => 'Esclairatge fluorescent blanc freg (W 3900 – 4500K)',
'exif-lightsource-15'  => 'Esclairatge fluorescent blanc (WW 3200 – 3700K)',
'exif-lightsource-17'  => 'Lum estandard A',
'exif-lightsource-18'  => 'Lum estandard B',
'exif-lightsource-19'  => 'Lum estandard C',
'exif-lightsource-24'  => "Tungstèni ISO d'estudiò",
'exif-lightsource-255' => 'Autra font de lum',

'exif-focalplaneresolutionunit-2' => 'poces',

'exif-sensingmethod-1' => 'Pas definit',
'exif-sensingmethod-2' => 'Captaire de zòna de colors monocromaticas',
'exif-sensingmethod-3' => 'Captaire de zòna de colors bicromaticas',
'exif-sensingmethod-4' => 'Captaire de zòna de colors tricromaticas',
'exif-sensingmethod-5' => 'Captaire color sequencial',
'exif-sensingmethod-7' => 'Captaire trilinear',
'exif-sensingmethod-8' => "Esclairatge d'estudiò al tungstèn ISO",

'exif-scenetype-1' => 'Imatge dirèctament fotografiat',

'exif-customrendered-0' => 'Procediment normal',
'exif-customrendered-1' => 'Procediment personalizat',

'exif-exposuremode-0' => 'Exposicion automatica',
'exif-exposuremode-1' => 'Exposicion manuala',
'exif-exposuremode-2' => 'Bracketting automatic',

'exif-whitebalance-0' => 'Balança dels blancs automatica',
'exif-whitebalance-1' => 'Balança dels blancs manuala',

'exif-scenecapturetype-0' => 'Estandard',
'exif-scenecapturetype-1' => 'Paisatge',
'exif-scenecapturetype-2' => 'Retrach',
'exif-scenecapturetype-3' => 'Scèna nuechenca',

'exif-gaincontrol-0' => 'Cap',
'exif-gaincontrol-1' => 'Augmentacion febla de l’aquisicion',
'exif-gaincontrol-2' => 'Augmentacion fòrta de l’aquisicion',
'exif-gaincontrol-3' => 'Reduccion febla de l’aquisicion',
'exif-gaincontrol-4' => 'Reduccion fòrta de l’aquisicion',

'exif-contrast-1' => 'Feble',
'exif-contrast-2' => 'Fòrt',

'exif-saturation-1' => 'Saturacion febla',
'exif-saturation-2' => 'Saturacion elevada',

'exif-sharpness-0' => 'Normala',
'exif-sharpness-1' => 'Doça',
'exif-sharpness-2' => 'Dura',

'exif-subjectdistancerange-0' => 'Desconegut',
'exif-subjectdistancerange-1' => 'Macrò',
'exif-subjectdistancerange-2' => 'Sarrat',
'exif-subjectdistancerange-3' => 'Luenhenc',

# Pseudotags used for GPSLatitudeRef and GPSDestLatitudeRef
'exif-gpslatitude-n' => 'Latitud Nòrd',
'exif-gpslatitude-s' => 'Latitud Sud',

# Pseudotags used for GPSLongitudeRef and GPSDestLongitudeRef
'exif-gpslongitude-e' => 'Longitud Èst',
'exif-gpslongitude-w' => 'Longitud Oèst',

'exif-gpsstatus-a' => 'Mesura en cors',
'exif-gpsstatus-v' => 'Interoperabilitat de la mesura',

'exif-gpsmeasuremode-2' => 'Mesura a 2 dimensions',
'exif-gpsmeasuremode-3' => 'Mesura a 3 dimensions',

# Pseudotags used for GPSSpeedRef and GPSDestDistanceRef
'exif-gpsspeed-k' => 'Kilomètres/ora',
'exif-gpsspeed-m' => 'Miles/ora',
'exif-gpsspeed-n' => 'Noses',

# Pseudotags used for GPSTrackRef, GPSImgDirectionRef and GPSDestBearingRef
'exif-gpsdirection-t' => 'Direccion vertadièra',
'exif-gpsdirection-m' => 'Nòrd magnetic',

# External editor support
'edit-externally'      => 'Modificar aqueste fichièr en utilizant una aplicacion extèrna',
'edit-externally-help' => 'Vejatz [http://meta.wikimedia.org/wiki/Help:External_editors las instruccions] per mai d’informacions.',

# 'all' in various places, this might be different for inflected languages
'recentchangesall' => 'totes',
'imagelistall'     => 'totes',
'watchlistall2'    => 'tot',
'namespacesall'    => 'Totes',

# E-mail address confirmation
'confirmemail'            => "Confirmar l'adreça de corrièr electronic",
'confirmemail_noemail'    => 'L’adreça de corrièr electronic configurada dins vòstras [[Special:Preferences|preferéncias]] es pas valida.',
'confirmemail_text'       => 'Aqueste wiki necessita la verificacion de vòstra adreça de corrièr electronic abans de poder utilizar tota foncion de messatjariá. Utilizatz lo boton çai jos per mandar un corrièr electronic de confirmacion a vòstra adreça. Lo corrièr contendrà un ligam contenent un còde, cargatz aqueste ligam dins vòstre navegaire per validar vòstra adreça.',
'confirmemail_pending'    => '<div class="error">
Un còde de confirmacion ja vos es estat mandat per corrièr electronic ; se venètz de crear vòstre compte, esperatz qualques minutas que l’e-mail arribe abans de demandar un còde novèl. </div>',
'confirmemail_send'       => 'Mandar un còde de confirmacion',
'confirmemail_sent'       => 'Corrièr electronic de confirmacion mandat.',
'confirmemail_oncreate'   => "Un còde de confirmacion es estat mandat a vòstra adreça de corrièr electronic.
Aqueste còde es pas requesit per se connectar, mas n'aurètz besonh per activar las foncionalitats ligadas als corrièrs electronics sus aqueste wiki.",
'confirmemail_sendfailed' => 'Impossible de mandar lo corrièr electronic de confirmacion.

Verificatz vòstra adreça. Retorn del programa de corrièr electronic : $1',
'confirmemail_invalid'    => 'Còde de confirmacion incorrècte. Benlèu lo còde a expirat.',
'confirmemail_needlogin'  => 'Devètz vos $1 per confirmar vòstra adreça de corrièr electronic.',
'confirmemail_success'    => 'Vòstra adreça de corrièr electronic es confirmada. Ara podètz vos connectar e profitar del wiki.',
'confirmemail_loggedin'   => 'Vòstra adreça es ara confirmada',
'confirmemail_error'      => 'Un problèma es subrevengut e volent enregistrar vòstra confirmacion',
'confirmemail_subject'    => 'Confirmacion d’adreça de corrièr electronic per {{SITENAME}}',
'confirmemail_body'       => "Qualqu’un, probablament vos amb l’adreça IP $1, a enregistrat un compte « $2 » amb aquesta adreça de corrièr electronic sul sit {{SITENAME}}.

Per confirmar qu'aqueste compte vos aparten vertadièrament e activar las foncions de messatjariá sus {{SITENAME}}, seguissètz lo ligam çai jos dins vòstre navegaire : 

$3 

Se s’agís pas de vos, dobrissez pas lo ligam. Aqueste còde de confirmacion expirarà lo $4.",

# Scary transclusion
'scarytranscludedisabled' => '[La transclusion interwiki es desactivada]',
'scarytranscludefailed'   => '[La recuperacion de modèl a pas capitat per $1 ; o planhem]',
'scarytranscludetoolong'  => '[L’URL es tròp longa ; o planhem]',

# Trackbacks
'trackbackbox'      => '<div id="mw_trackbacks">
Retroligams vèrs aqueste article :<br />
$1
</div>',
'trackbackremove'   => '([$1 Suprimir])',
'trackbacklink'     => 'Retroligam',
'trackbackdeleteok' => 'Lo retroligam es estat suprimit amb succès.',

# Delete conflict
'deletedwhileediting' => "Atencion : aquesta pagina es estada suprimida aprèp qu'avètz començat de la modificar.",
'confirmrecreate'     => "L'utilizaire [[User:$1|$1]] ([[User talk:$1|talk]]) a suprimit aquesta pagina, alara qu'aviatz començat de l'editar, pel motiu seguent:
: ''$2''
Confirmatz que desiratz recrear aqueste article.",
'recreate'            => 'Recrear',

# HTML dump
'redirectingto' => 'Redireccion vèrs [[$1]]...',

# action=purge
'confirm_purge'        => "Volètz refrescar aquesta pagina (purgar l'amagatal) ?

$1",
'confirm_purge_button' => 'Confirmar',

# AJAX search
'searchcontaining' => 'Cercar los articles contenent « $1 ».',
'searchnamed'      => 'Cercar los articles nomenats « $1 ».',
'articletitles'    => 'Articles començant per « $1 »',
'hideresults'      => 'Amagar los resultats',

# Multipage image navigation
'imgmultipageprev'   => '&larr; pagina precedenta',
'imgmultipagenext'   => 'pagina seguenta &rarr;',
'imgmultigo'         => 'Accedir !',
'imgmultigotopre'    => 'Accedir a la pagina',
'imgmultiparseerror' => 'Aqueste fichièr imatge es aparentament corromput o incorrècte, e {{SITENAME}} pòt pas fornir una lista de las paginas.',

# Table pager
'table_pager_next'         => 'Pagina seguenta',
'table_pager_prev'         => 'Pagina precedenta',
'table_pager_first'        => 'Primièra pagina',
'table_pager_last'         => 'Darrièra pagina',
'table_pager_limit'        => 'Mostrar $1 elements per pagina',
'table_pager_limit_submit' => 'Accedir',
'table_pager_empty'        => 'Cap de resultat',

# Auto-summaries
'autosumm-blank'   => 'Resumit automatic : blanquiment',
'autosumm-replace' => "Resumit automatic : contengut remplaçat per '$1'",
'autoredircomment' => 'Redireccion vèrs [[$1]]',
'autosumm-new'     => 'Pagina novèla: $1',

# Size units
'size-bytes'     => '$1 o',
'size-kilobytes' => '$1 Ko',
'size-megabytes' => '$1 Mo',
'size-gigabytes' => '$1 Go',

# Live preview
'livepreview-loading' => 'Cargament…',
'livepreview-ready'   => 'Cargament… Acabat!',
'livepreview-failed'  => 'L’apercebut rapid a pas capitat!
Ensajatz la previsualizacion normala.',
'livepreview-error'   => 'Impossible de se connectar : $1 "$2"
Ensajatz la previsualizacion normala.',

# Friendlier slave lag warnings
'lag-warn-normal' => 'Los cambiaments datant de mens de $1 segondas pòdon aparéisser pas dins aquesta lista.',
'lag-warn-high'   => 'En rason d’una fòrta carga de las bancas de donadas, los cambiaments datant de mens de $1 segondas pòdon aparéisser pas dins aquesta lista.',

# Watchlist editor
'watchlistedit-numitems'       => 'Vòstra lista de seguit conten {{PLURAL:$1|una pagina|$1 paginas}}, sens comptar las paginas de discussion',
'watchlistedit-noitems'        => 'Vòstra lista de seguit conten pas cap de pagina.',
'watchlistedit-normal-title'   => 'Modificacion de la lista de seguit',
'watchlistedit-normal-legend'  => 'Levar de paginas de la lista de seguit',
'watchlistedit-normal-explain' => 'Las paginas de vòstra lista de seguit son visiblas çaijos, classadas per espaci de noms. Per levar una pagina (e sa pagina de discussion) de la lista, seleccionatz la casa al costat puèi clicatz sul boton en bas. Tanben podètz [[Special:Watchlist/raw|la modificar en mòde brut]] o [[Special:Watchlist/clear|la voidar entièrament]].',
'watchlistedit-normal-submit'  => 'Levar las paginas seleccionadas',
'watchlistedit-normal-done'    => '{{PLURAL:$1|Una pagina es estada levada|$1 paginas son estadas levadas}} de vòstra lista de seguit :',
'watchlistedit-raw-title'      => 'Modificacion de la lista de seguit (mòde brut)',
'watchlistedit-raw-legend'     => 'Modificacion de la lista de seguit en mòde brut',
'watchlistedit-raw-explain'    => 'La lista de las paginas de vòstra lista de seguit es mostrada çaijos, sens las paginas de discussion (automaticament inclusas) e destriadas per espaci de noms. Podètz modificar la lista : ajustatz las paginas que volètz seguir (pauc impòrta ont), una pagina per linha, e levatz las paginas que volètz pas mai seguir. Quand avètz acabat, clicatz sul boton en bas per metre la lista a jorn. Podètz tanben utilizar [[Special:Watchlist/edit|l’editaire normal]].',
'watchlistedit-raw-titles'     => 'Paginas:',
'watchlistedit-raw-submit'     => 'Metre a jorn la lista',
'watchlistedit-raw-done'       => 'Vòstra lista de seguit es estada mesa a jorn.',
'watchlistedit-raw-added'      => '{{PLURAL:$1|Una pagina es estada ajustada|$1 paginas son estadas ajustadas}} :',
'watchlistedit-raw-removed'    => '{{PLURAL:$1|Una pagina es estada levada|$1 paginas son estadas levadas}} :',

# Watchlist editing tools
'watchlisttools-view' => 'Lista de seguit',
'watchlisttools-edit' => 'Veire e modificar la lista de seguit',
'watchlisttools-raw'  => 'Modificar la lista (mòde brut)',

);
