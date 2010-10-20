<?php
/** Aragonese (Aragonés)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Juanpabl
 * @author Malafaya
 * @author Remember the dot
 * @author The Evil IP address
 * @author Urhixidur
 * @author Willtron
 * @author לערי ריינהארט
 */

$fallback = 'es';

$namespaceNames = array(
	NS_MEDIA            => 'Media',
	NS_SPECIAL          => 'Espezial',
	NS_TALK             => 'Descusión',
	NS_USER             => 'Usuario',
	NS_USER_TALK        => 'Descusión_usuario',
	NS_PROJECT_TALK     => 'Descusión_$1',
	NS_FILE             => 'Imachen',
	NS_FILE_TALK        => 'Descusión_imachen',
	NS_MEDIAWIKI        => 'MediaWiki',
	NS_MEDIAWIKI_TALK   => 'Descusión_MediaWiki',
	NS_TEMPLATE         => 'Plantilla',
	NS_TEMPLATE_TALK    => 'Descusión_plantilla',
	NS_HELP             => 'Aduya',
	NS_HELP_TALK        => 'Descusión_aduya',
	NS_CATEGORY         => 'Categoría',
	NS_CATEGORY_TALK    => 'Descusión_categoría',
);

$magicWords = array(
	'redirect'              => array( '0', '#ENDRECERA', '#REENDRECERA', '#REDIRECCIÓN', '#REDIRECCION', '#REDIRECT' ),
	'namespace'             => array( '1', 'ESPACIODENOMBRES', 'ESPACIODENOMBRE', 'NAMESPACE' ),
	'namespacee'            => array( '1', 'ESPACIODENOMBRESE', 'ESPACIODENOMBREC', 'NAMESPACEE' ),
	'img_right'             => array( '1', 'dreita', 'derecha', 'dcha', 'der', 'right' ),
	'img_left'              => array( '1', 'cucha', 'zurda', 'izquierda', 'izda', 'izq', 'left' ),
	'ns'                    => array( '0', 'EN:', 'EDN:', 'NS:' ),
	'displaytitle'          => array( '1', 'TÍTOL', 'MOSTRARTÍTULO', 'MOSTRARTITULO', 'DISPLAYTITLE' ),
	'currentversion'        => array( '1', 'BERSIÓNAUTUAL', 'BERSIONAUTUAL', 'REVISIÓNACTUAL', 'VERSIONACTUAL', 'VERSIÓNACTUAL', 'CURRENTVERSION' ),
	'language'              => array( '0', '#LUENGA:', '#IDIOMA:', '#LANGUAGE:' ),
	'special'               => array( '0', 'espezial', 'especial', 'special' ),
);

$specialPageAliases = array(
	'DoubleRedirects'           => array( 'Endreceras_doples', 'Reendrezeras_dobles', 'Dobles_reendrezeras', 'Endrezeras_dobles', 'Dobles_endrezeras' ),
	'BrokenRedirects'           => array( 'Endreceras_trencatas', 'Endreceras_trencadas', 'Reendrezeras_trencatas', 'Endrezeras_trencatas', 'Reendrezeras_crebatas', 'Endrezeras_crebatas', 'Endrezeras_trencadas', 'Endrezeras_crebadas' ),
	'Disambiguations'           => array( 'Desambigacions', 'Desambigazions', 'Pachinas_de_desambigazión' ),
	'Userlogin'                 => array( 'Encetar_sesión', 'Enzetar_sesión', 'Dentrar' ),
	'Userlogout'                => array( 'Salir', 'Rematar_sesión' ),
	'CreateAccount'             => array( 'Creyar_cuenta' ),
	'Preferences'               => array( 'Preferencias' ),
	'Watchlist'                 => array( 'Lista_de_seguimiento' ),
	'Recentchanges'             => array( 'Zaguers_cambeos', 'Cambeos_recients' ),
	'Upload'                    => array( 'Cargar', 'Puyar' ),
	'Listfiles'                 => array( 'Lista_de_fichers', 'Lista_d\'imáchens', 'Lista_d\'imachens' ),
	'Newimages'                 => array( 'Nuevos_fichers', 'Nuevas_imáchens', 'Nuevas_imachens', 'Nuebas_imachens' ),
	'Listusers'                 => array( 'Lista_d\'usuarios' ),
	'Listgrouprights'           => array( 'ListaDreitosGrupos' ),
	'Statistics'                => array( 'Estatisticas', 'Estadisticas' ),
	'Randompage'                => array( 'Pachina_a_l\'azar', 'Pachina_aleatoria', 'Pachina_aliatoria' ),
	'Lonelypages'               => array( 'Pachinas_popiellas' ),
	'Uncategorizedpages'        => array( 'Pachinas_sin_categorizar', 'Pachinas_sin_categorías' ),
	'Uncategorizedcategories'   => array( 'Categorías_sin_categorizar._Categorías_sin_categorías' ),
	'Uncategorizedimages'       => array( 'Fichers_sin_categorizar', 'Fichers_sin_categorías', 'Imáchens_sin_categorías', 'Imachens_sin_categorizar', 'Imáchens_sin_categorizar' ),
	'Uncategorizedtemplates'    => array( 'Plantillas_sin_categorizar._Plantillas_sin_categorías' ),
	'Unusedcategories'          => array( 'Categorías_no_emplegatas', 'Categorías_sin_emplegar' ),
	'Unusedimages'              => array( 'Fichers_no_emplegatos', 'Fichers_sin_emplegar', 'Imáchens_no_emplegatas', 'Imáchens_sin_emplegar' ),
	'Wantedpages'               => array( 'Pachinas_requiestas', 'Pachinas_demandatas', 'Binclos_crebatos', 'Binclos_trencatos' ),
	'Wantedcategories'          => array( 'Categorías_requiestas', 'Categorías_demandatas' ),
	'Wantedfiles'               => array( 'Fichers_requiestos', 'Fichers_demandaus', 'Archibos_requiestos', 'Archibos_demandatos' ),
	'Wantedtemplates'           => array( 'Plantillas_requiestas', 'Plantillas_demandatas' ),
	'Mostlinked'                => array( 'Pachinas_más_enlazatas', 'Pachinas_más_vinculatas' ),
	'Mostlinkedcategories'      => array( 'Categorías_más_emplegatas', 'Categorías_más_enlazatas', 'Categorías_más_binculatas' ),
	'Mostlinkedtemplates'       => array( 'Plantillas_más_emplegatas', 'Plantillas_más_enlazatas', 'Plantillas_más_binculatas' ),
	'Mostimages'                => array( 'Fichers_más_emplegatos', 'Imáchens_más_emplegatas', 'Imachens_más_emplegatas' ),
	'Mostcategories'            => array( 'Pachinas_con_más_categorías' ),
	'Mostrevisions'             => array( 'Pachinas_con_más_edicions', 'Pachinas_con_más_edizions', 'Pachinas_más_editatas', 'Pachinas_con_más_bersions' ),
	'Fewestrevisions'           => array( 'Pachinas_con_menos_edicions', 'Pachinas_con_menos_edizions', 'Pachinas_menos_editatas', 'Pachinas_con_menos_bersions' ),
	'Shortpages'                => array( 'Pachinas_más_curtas' ),
	'Longpages'                 => array( 'Pachinas_más_largas' ),
	'Newpages'                  => array( 'Pachinas_nuevas', 'Pachinas_recients', 'Pachinas_nuebas', 'Pachinas_más_nuebas', 'Pachinas_más_rezients', 'Pachinas_rezients' ),
	'Ancientpages'              => array( 'Pachinas_más_viellas', 'Pachinas_más_antigas', 'Pachinas_más_biellas', 'Pachinas_biellas', 'Pachinas_antigas' ),
	'Deadendpages'              => array( 'Pachinas_sin_salida', 'Pachinas_sin_de_salida' ),
	'Protectedpages'            => array( 'Pachinas_protechitas', 'Pachinas_protechidas' ),
	'Protectedtitles'           => array( 'Títols_protechitos', 'Títols_protexitos', 'Títols_protechius' ),
	'Allpages'                  => array( 'Todas_as_pachinas' ),
	'Prefixindex'               => array( 'Pachinas_por_prefixo', 'Mirar_por_prefixo' ),
	'Ipblocklist'               => array( 'Lista_d\'IPs_bloqueyatas', 'Lista_d\'IPs_bloquiatas', 'Lista_d\'adrezas_IP_bloqueyatas', 'Lista_d\'adrezas_IP_bloquiatas' ),
	'Specialpages'              => array( 'Pachinas_especials', 'Pachinas_espezials' ),
	'Contributions'             => array( 'Contrebucions', 'Contrebuzions' ),
	'Emailuser'                 => array( 'Ninvía_mensache', 'Nimbía_mensache' ),
	'Confirmemail'              => array( 'Confirmar_e-mail' ),
	'Movepage'                  => array( 'TresladarPachina', 'Renombrar_pachina', 'Mober_pachina', 'Tresladar_pachina' ),
	'Blockme'                   => array( 'Bloqueya-me' ),
	'Booksources'               => array( 'Fuents_de_libros' ),
	'Categories'                => array( 'Categorías' ),
	'Export'                    => array( 'Exportar' ),
	'Version'                   => array( 'Versión', 'Bersión' ),
	'Allmessages'               => array( 'Totz_os_mensaches', 'Toz_os_mensaches' ),
	'Log'                       => array( 'Rechistro', 'Rechistros' ),
	'Blockip'                   => array( 'Bloqueyar' ),
	'Undelete'                  => array( 'Restaurar' ),
	'Import'                    => array( 'Importar' ),
	'Unwatchedpages'            => array( 'Pachinas_no_cosiratas', 'Pachinas_sin_cosirar' ),
	'Mypage'                    => array( 'A_mía_pachina', 'A_mía_pachina_d\'usuario' ),
	'Mytalk'                    => array( 'A_mía_descusión', 'A_mía_pachina_de_descusión' ),
	'Mycontributions'           => array( 'As_mías_contrebucions', 'As_mías_contrebuzions' ),
	'Listadmins'                => array( 'Lista_d\'almenistradors' ),
	'Listbots'                  => array( 'Lista_de_botz', 'Lista_de_bots' ),
	'Popularpages'              => array( 'Pachinas_populars', 'Pachinas_más_populars' ),
	'Search'                    => array( 'Mirar' ),
	'Resetpass'                 => array( 'Cambiar_contrasenya' ),
);

$messages = array(
# User preference toggles
'tog-underline'               => 'Subrayar os vinclos:',
'tog-highlightbroken'         => 'Formateyar os vinclos trencatos <a href="" class="new"> d\'ista traza </a> (alternativa: asinas <a href="" class="internal">?</a>).',
'tog-justify'                 => 'Achustar parrafos',
'tog-hideminor'               => 'Amagar edicions menors en a pachina de "zaguers cambeos"',
'tog-hidepatrolled'           => 'Amagar as edicions patrullatas en os zaguers cambeos',
'tog-newpageshidepatrolled'   => "Amagar as pachinas patrulladas d'a lista de pachinas nuevas",
'tog-extendwatchlist'         => "Expandir a lista de seguimiento t'amostrar totz os cambeos, no nomás os más recients.",
'tog-usenewrc'                => 'Zaguers cambeos con presentación amillorada (cal JavaScript)',
'tog-numberheadings'          => 'Numerar automaticament os encabezaus',
'tog-showtoolbar'             => "Amostrar a barra de ferramientas d'edición (cal JavaScript)",
'tog-editondblclick'          => 'Activar edición de pachinas fendo-ie doble click (cal JavaScript)',
'tog-editsection'             => 'Activar a edición por seccions usando os vinclos [editar]',
'tog-editsectiononrightclick' => "Activar a edición de seccions punchando con o botón dreito d'o ratet <br /> en os títols de seccions (cal JavaScript)",
'tog-showtoc'                 => "Amostrar l'endice (ta pachinas con más de 3 seccions)",
'tog-rememberpassword'        => "Remerar o mío nombre d'usuario en iste navegador (como muito por $1 {{PLURAL:$1|día|días}})",
'tog-watchcreations'          => 'Cosirar as pachinas que creye',
'tog-watchdefault'            => 'Cosirar as pachinas que edite',
'tog-watchmoves'              => 'Cosirar as pachinas que treslade',
'tog-watchdeletion'           => 'Cosirar as pachinas que borre',
'tog-previewontop'            => "Amostrar l'anvista previa antes d'o quatrón d'edición",
'tog-previewonfirst'          => "Amostrar l'anvista previa de l'articlo en a primera edición",
'tog-nocache'                 => "Desactivar a ''caché'' d'o navegador",
'tog-enotifwatchlistpages'    => 'Recibir un correu quan se faigan cambios en una pachina cosirata por yo',
'tog-enotifusertalkpages'     => 'Ninviar-me un correu quan cambee a mía pachina de descusión',
'tog-enotifminoredits'        => 'Ninviar-me un correu tamién quan bi haiga edicions menors de pachinas',
'tog-enotifrevealaddr'        => 'Fer veyer a mía adreza de correu-e en os correus de notificación',
'tog-shownumberswatching'     => "Amostrar o numero d'usuarios que cosiran un articlo",
'tog-oldsig'                  => "Vista previa d'a sinyadura:",
'tog-fancysig'                => 'Tratar as sinyaduras como wikitexto (sin de vinclo automatico)',
'tog-externaleditor'          => "Fer servir l'editor externo por defecto (nomás ta espiertos, cal confegurar o suyo ordenador).",
'tog-externaldiff'            => 'Fer servir o visualizador de cambeos externo por defecto (nomás ta espiertos, cal confegurar o suyo ordenador)',
'tog-showjumplinks'           => 'Activar vinclos d\'accesibilidat "blincar enta"',
'tog-uselivepreview'          => 'Activar previsualización automatica (cal JavaScript) (Esperimental)',
'tog-forceeditsummary'        => 'Avisar-me quan o campo de resumen siga buedo.',
'tog-watchlisthideown'        => 'Amagar as mías edicions en a lista de seguimiento',
'tog-watchlisthidebots'       => 'Amagar edicions de bots en a lista de seguimiento',
'tog-watchlisthideminor'      => 'Amagar edicions menors en a lista de seguimiento',
'tog-watchlisthideliu'        => 'Amagar en a lista de seguimiento as edicions feitas por usuarios rechistratos',
'tog-watchlisthideanons'      => 'Amagar en a lista de seguimiento as edicions feitas por usuarios anonimos.',
'tog-watchlisthidepatrolled'  => 'Amagar as edicions patrullatas en a lista de seguimiento',
'tog-nolangconversion'        => 'Desautibar conversión de bariants',
'tog-ccmeonemails'            => 'Recibir copias de os correus que ninvío ta atros usuarios',
'tog-diffonly'                => "No amostrar o conteniu d'a pachina debaixo d'as esferencias",
'tog-showhiddencats'          => 'Amostrar categorías amagatas',
'tog-norollbackdiff'          => 'No amostrar as diferencias dimpués de revertir',

'underline-always'  => 'Siempre',
'underline-never'   => 'Nunca',
'underline-default' => "Confeguración por defecto d'o navegador",

# Font style option in Special:Preferences
'editfont-style'     => "Tipo de letra de l'aria d'edición:",
'editfont-default'   => "O predeterminau d'o navegador",
'editfont-monospace' => 'Tipo de letra monoespaciada',
'editfont-sansserif' => 'Tipo de letra sans-serif',
'editfont-serif'     => 'Tipo de letra Serif',

# Dates
'sunday'        => 'domingo',
'monday'        => 'luns',
'tuesday'       => 'martes',
'wednesday'     => 'miércols',
'thursday'      => 'chueves',
'friday'        => 'biernes',
'saturday'      => 'sabado',
'sun'           => 'dom',
'mon'           => 'lun',
'tue'           => 'mar',
'wed'           => 'mie',
'thu'           => 'chu',
'fri'           => 'vie',
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
'october'       => 'octubre',
'november'      => 'noviembre',
'december'      => 'aviento',
'january-gen'   => 'de chinero',
'february-gen'  => 'de febrero',
'march-gen'     => 'de marzo',
'april-gen'     => "d'abril",
'may-gen'       => 'de mayo',
'june-gen'      => 'de chunio',
'july-gen'      => 'de chulio',
'august-gen'    => "d'agosto",
'september-gen' => 'de setiembre',
'october-gen'   => "d'octubre",
'november-gen'  => 'de noviembre',
'december-gen'  => "d'aviento",
'jan'           => 'chi',
'feb'           => 'feb',
'mar'           => 'mar',
'apr'           => 'abr',
'may'           => 'may',
'jun'           => 'chun',
'jul'           => 'chul',
'aug'           => 'ago',
'sep'           => 'set',
'oct'           => 'oct',
'nov'           => 'nov',
'dec'           => 'avi',

# Categories related messages
'pagecategories'                 => '{{PLURAL:$1|Categoría|Categorías}}',
'category_header'                => 'Articlos en a categoría "$1"',
'subcategories'                  => 'Subcategorías',
'category-media-header'          => 'Contenius multimedia en a categoría "$1"',
'category-empty'                 => "''Ista categoría no tiene por agora garra articlo ni conteniu multimedia''",
'hidden-categories'              => '{{PLURAL:$1|Categoría amagata|Categorías amagatas}}',
'hidden-category-category'       => 'Categorías amagatas',
'category-subcat-count'          => "{{PLURAL:$2|Ista categoría contiene nomás a siguient subcategoría.|Ista categoría incluye {{PLURAL:$1|a siguient subcategoría|as siguients $1 subcategorías}}, d'un total de $2.}}",
'category-subcat-count-limited'  => 'Ista categoría contiene {{PLURAL:$1|a siguient subcategoría|as siguients $1 subcategorías}}.',
'category-article-count'         => "{{PLURAL:$2|Ista categoría nomás incluye a pachina siguient.|{{PLURAL:$1|A pachina siguient fa parte|As pachinas siguients fan parte}} d'esta categoría, d'un total de $2.}}",
'category-article-count-limited' => "{{PLURAL:$1|A pachina siguient fa parte|As $1 pachinas siguients fan parte}} d'ista categoría.",
'category-file-count'            => "{{PLURAL:$2|Ista categoría nomás contiene o fichero siguient.|{{PLURAL:$1|O fichero siguient fa parte|Os $1 fichers siguients fan parte}} d'ista categoría, d'un total de $2.}}",
'category-file-count-limited'    => "{{PLURAL:$1|O fichero siguient fa parte|Os $1 fichers siguients fan parte}} d'ista categoría.",
'listingcontinuesabbrev'         => 'cont.',
'index-category'                 => 'Pachinas indexadas',
'noindex-category'               => 'Pachinas sin indexar',

'mainpagetext'      => "'''O programa MediaWiki s'ha instalato correctament.'''",
'mainpagedocfooter' => "Consulta a [http://meta.wikimedia.org/wiki/Help:Contents Guía d'usuario] ta mirar información sobre cómo usar o software wiki.

== Ta prencipiar ==

* [http://www.mediawiki.org/wiki/Manual:Configuration_settings Lista de caracteristicas confegurables]
* [http://www.mediawiki.org/wiki/Manual:FAQ Preguntas cutianas sobre MediaWiki (FAQ)]
* [https://lists.wikimedia.org/mailman/listinfo/mediawiki-announce Lista de correu sobre ta anuncios de MediaWiki]",

'about'         => 'Información sobre',
'article'       => 'Articlo',
'newwindow'     => "(s'ubre en una nueva finestra)",
'cancel'        => 'Cancelar',
'moredotdotdot' => 'Más...',
'mypage'        => 'A mía pachina',
'mytalk'        => 'Pachina de descusión',
'anontalk'      => "Pachina de descusión d'ista IP",
'navigation'    => 'Navego',
'and'           => '&#32;y',

# Cologne Blue skin
'qbfind'         => 'Mirar',
'qbbrowse'       => 'Navegar',
'qbedit'         => 'Editar',
'qbpageoptions'  => 'Ista pachina',
'qbpageinfo'     => 'Contexto',
'qbmyoptions'    => 'Pachinas propias',
'qbspecialpages' => 'Pachinas especials',
'faq'            => 'Preguntas freqüents (FAQ)',
'faqpage'        => 'Project:Preguntas freqüents',

# Vector skin
'vector-action-addsection'       => 'Adhibir nueva sección',
'vector-action-delete'           => 'Borrar',
'vector-action-move'             => 'Tresladar',
'vector-action-protect'          => 'Protecher',
'vector-action-undelete'         => 'Restaurar',
'vector-action-unprotect'        => 'Desprotecher',
'vector-simplesearch-preference' => "Habilitar socherencias de busca amilloradas (nomás ta l'apariencia Vector)",
'vector-view-create'             => 'Creyar',
'vector-view-edit'               => 'Editar',
'vector-view-history'            => "Amostrar l'historial",
'vector-view-view'               => 'Leyer',
'vector-view-viewsource'         => 'Veyer o codigo fuent',
'actions'                        => 'Accions',
'namespaces'                     => 'Espacios de nombres',
'variants'                       => 'Variants',

'errorpagetitle'    => 'Error',
'returnto'          => 'Tornar ta $1.',
'tagline'           => 'De {{SITENAME}}',
'help'              => 'Aduya',
'search'            => 'Mirar',
'searchbutton'      => 'Mirar-lo',
'go'                => 'Ir-ie',
'searcharticle'     => 'Ir-ie',
'history'           => "Historial d'a pachina",
'history_short'     => 'Historial',
'updatedmarker'     => 'esviellato dende a zaguera vesita',
'info_short'        => 'Información',
'printableversion'  => 'Versión ta imprentar',
'permalink'         => 'Vinclo permanent',
'print'             => 'Imprentar',
'edit'              => 'Editar',
'create'            => 'Creyar',
'editthispage'      => 'Editar ista pachina',
'create-this-page'  => 'Creyar ista pachina',
'delete'            => 'Borrar',
'deletethispage'    => 'Borrar ista pachina',
'undelete_short'    => 'Restaurar {{PLURAL:$1|una edición|$1 edicions}}',
'protect'           => 'Protecher',
'protect_change'    => 'cambiar',
'protectthispage'   => 'Protecher ista pachina',
'unprotect'         => 'esprotecher',
'unprotectthispage' => 'Esprotecher ista pachina',
'newpage'           => 'Pachina nueva',
'talkpage'          => "Descusión d'ista pachina",
'talkpagelinktext'  => 'Descutir',
'specialpage'       => 'Pachina Especial',
'personaltools'     => 'Ferramientas personals',
'postcomment'       => 'Nueva sección',
'articlepage'       => "Veyer l'articlo",
'talk'              => 'Discusión',
'views'             => 'Visualizacions',
'toolbox'           => 'Ferramientas',
'userpage'          => "Veyer a pachina d'usuario",
'projectpage'       => "Veyer a pachina d'o prochecto",
'imagepage'         => "Veyer a pachina d'o fichero",
'mediawikipage'     => "Veyer a pachina d'o mensache",
'templatepage'      => "Veyer a pachina d'a plantilla",
'viewhelppage'      => "Veyer a pachina d'aduya",
'categorypage'      => "Veyer a pachina d'a categoría",
'viewtalkpage'      => 'Veyer a pachina de descusión',
'otherlanguages'    => 'En atras luengas',
'redirectedfrom'    => '(Reendrezato dende $1)',
'redirectpagesub'   => 'Pachina reendrezata',
'lastmodifiedat'    => "Zaguera edición d'ista pachina o $1 a las $2.",
'viewcount'         => 'Ista pachina ha tenito {{PLURAL:$1|una vesita|$1 vesitas}}.',
'protectedpage'     => 'Pachina protechita',
'jumpto'            => 'Ir ta:',
'jumptonavigation'  => 'navego',
'jumptosearch'      => 'busca',
'view-pool-error'   => "Desincuse, os servidors son agora sobrecargaus.
Masiaus usuarios son mirando d'acceder ta ista pachina.
Aguarde una mica antes de tornar a acceder ta ista pachina.

$1",
'pool-timeout'      => "S'ha pasau o tiempo d'aspera limite ta o bloqueyo",
'pool-queuefull'    => 'A coda de treballo ye plena',
'pool-errorunknown' => 'Error desconoixida',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'            => 'Información sobre {{SITENAME}}',
'aboutpage'            => 'Project:Sobre',
'copyright'            => 'O conteniu ye disponible baixo a licencia $1.',
'copyrightpage'        => "{{ns:project}}:Dreitos d'autor",
'currentevents'        => 'Actualidat',
'currentevents-url'    => 'Project:Actualidat',
'disclaimers'          => 'Alvertencias chenerals',
'disclaimerpage'       => 'Project:Alvertencias chenerals',
'edithelp'             => 'Aduya ta editar pachinas',
'edithelppage'         => "Help:Cómo s'edita una pachina",
'helppage'             => 'Help:Aduya',
'mainpage'             => 'Portalada',
'mainpage-description' => 'Portalada',
'policy-url'           => 'Project:Politicas y normas',
'portal'               => "Portal d'a comunidat",
'portal-url'           => "Project:Portal d'a comunidat",
'privacy'              => 'Politica de privacidat',
'privacypage'          => 'Project:Politica de privacidat',

'badaccess'        => 'Error de premisos',
'badaccess-group0' => "No tiene premisos ta fer l'acción que ha demandato.",
'badaccess-groups' => "L'acción que ha demandato no ye premitita que ta os usuarios {{PLURAL:$2|d'a colla|d'as collas}}: $1.",

'versionrequired'     => 'Ye precisa a versión $1 de MediaWiki',
'versionrequiredtext' => 'Ye precisa a versión $1 de MediaWiki ta fer servir ista pachina. Ta más información, consulte [[Special:Version]]',

'ok'                      => "D'alcuerdo",
'retrievedfrom'           => 'Obtenito de "$1"',
'youhavenewmessages'      => 'Tiene $1 ($2).',
'newmessageslink'         => 'mensaches nuevos',
'newmessagesdifflink'     => 'Esferencias con a versión anterior',
'youhavenewmessagesmulti' => 'Tiene nuevos mensaches en $1',
'editsection'             => 'editar',
'editold'                 => 'editar',
'viewsourceold'           => 'veyer o codigo fuent',
'editlink'                => 'editar',
'viewsourcelink'          => 'veyer o codigo fuent',
'editsectionhint'         => 'Editar a sección: $1',
'toc'                     => 'Contenius',
'showtoc'                 => 'amostrar',
'hidetoc'                 => 'amagar',
'thisisdeleted'           => 'Quiere amostrar u restaurar $1?',
'viewdeleted'             => 'Quiere amostrar $1?',
'restorelink'             => '{{PLURAL:$1|una edición borrata|$1 edicions borratas}}',
'feedlinks'               => 'Sendicación como fuent de noticias:',
'feed-invalid'            => 'Sendicación como fuent de noticias no conforme.',
'feed-unavailable'        => 'As canals de sendicación no son disponibles.',
'site-rss-feed'           => 'Canal RSS $1',
'site-atom-feed'          => 'Canal Atom $1',
'page-rss-feed'           => 'Canal RSS "$1"',
'page-atom-feed'          => 'Canal Atom "$1"',
'red-link-title'          => '$1 (a pachina encara no existe)',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'Pachina',
'nstab-user'      => "Pachina d'usuario",
'nstab-media'     => 'Pachina multimedia',
'nstab-special'   => 'Pachina especial',
'nstab-project'   => "Pachina d'o prochecto",
'nstab-image'     => 'Fichero',
'nstab-mediawiki' => 'Mensache',
'nstab-template'  => 'Plantilla',
'nstab-help'      => 'Aduya',
'nstab-category'  => 'Categoría',

# Main script and global functions
'nosuchaction'      => 'No se reconoixe ista acción',
'nosuchactiontext'  => "L'acción especificata por a URL no ye conforme.
Talment s'haiga entivocau en escribir a URL, u haiga seguiu un vinclo incorrecto.
Tamién podría marcar un bug en o software emplegato por {{SITENAME}}.",
'nosuchspecialpage' => 'No existe ixa pachina especial',
'nospecialpagetext' => '<strong>A pachina especial que ha demandato no existe.</strong>

Puede trobar una lista de pachinas especials en [[Special:SpecialPages|{{int:specialpages}}]].',

# General errors
'error'                => 'Error',
'databaseerror'        => "Error d'a base de datos",
'dberrortext'          => 'Ha sucedito una error de sintaxi en una consulta a la base de datos.
Isto podría marcar una error en o programa.
A zaguera consulta estió:
<blockquote><tt>$1</tt></blockquote>
dende adintro d\'a función "<tt>$2</tt>".
A error retornata por a base de datos estió "<tt>$3: $4</tt>".',
'dberrortextcl'        => 'S\'ha producito una error de sintaxi en una consulta a la base de datos.
A zaguera consulta estió:
"$1"
dende adintro d\'a función "$2".
A base de datos retornó a error "$3: $4"',
'laggedslavemode'      => "Pare cuenta: podrían faltar as zagueras edicions d'ista pachina.",
'readonly'             => 'Base de datos bloqueyata',
'enterlockreason'      => "Esplique a causa d'o bloqueyo, incluyendo una estimación de quán se producirá o desbloqueyo",
'readonlytext'         => "A base de datos de {{SITENAME}} ye bloqueyata temporalment, probablement por mantenimiento rutinario, dimpués d'ixo tornará a la normalidat.
L'almenistrador que la bloqueyó dió ista esplicación:
<p>$1",
'missing-article'      => "No s'ha trobato en a base de datos o texto d'una pachina que i habría d'haber trobato, clamada \"\$1\" \$2.

Cal que a razón d'isto siga que s'ha seguito un diff no esviellato u un vinclo ta l'historial d'una pachina que ya s'ha borrato.

Si no ye iste o caso, talment haiga trobato un error en o software.
Por favor, comunique-lo a un [[Special:ListUsers/sysop|almenistrador]] indicando-le l'adreza URL.",
'missingarticle-rev'   => '(versión#: $1)',
'missingarticle-diff'  => '(Esf: $1, $2)',
'readonly_lag'         => 'A base de datos ye bloqueyata temporalment entre que os servidors se sincronizan.',
'internalerror'        => 'Error interna',
'internalerror_info'   => 'Error interna: $1',
'fileappenderrorread'  => 'No s\'ha puesto leyer "$1" durant a inserción.',
'fileappenderror'      => 'No s\'ha puesto adhibir "$1" a "$2".',
'filecopyerror'        => 'No s\'ha puesto copiar o fichero "$1" ta "$2".',
'filerenameerror'      => 'No s\'ha puesto cambiar o nombre d\'o fichero "$1" a "$2".',
'filedeleteerror'      => 'No s\'ha puesto borrar o fichero "$1".',
'directorycreateerror' => 'No s\'ha puesto crear o directorio "$1".',
'filenotfound'         => 'No s\'ha puesto trobar o fichero "$1".',
'fileexistserror'      => 'No s\'ha puesto escribir o fichero "$1": o fichero ya existe',
'unexpected'           => 'Valura no prevista: "$1"="$2".',
'formerror'            => 'Error: no se podió ninviar o formulario',
'badarticleerror'      => 'Ista acción no se puede no se puede reyalizar en ista pachina.',
'cannotdelete'         => 'No s\'ha puesto borrar pachina u fichero "$1". Talment belatro usuario l\'ha borrato dinantes.',
'badtitle'             => 'Títol incorrecto',
'badtitletext'         => "O títol d'a pachina demandata ye buedo, incorrecto, u tiene un vinclo interwiki mal feito. Puede contener uno u más caracters que no se pueden fer servir en títols.",
'perfcached'           => 'Os datos que siguen son en caché, y podrían no estar esviellatos:',
'perfcachedts'         => 'Istos datos se troban en a caché, que estió esviellata por zaguer vegada o $1.',
'querypage-no-updates' => "S'han desactivato as actualizacions d'ista pachina. Por ixo, no s'esta esviellando os datos.",
'wrong_wfQuery_params' => 'Parametros incorrectos ta wfQuery()<br />
Función: $1<br />
Consulta: $2',
'viewsource'           => 'Veyer o codigo fuent',
'viewsourcefor'        => 'ta $1',
'actionthrottled'      => 'acción afogata',
'actionthrottledtext'  => 'Como mesura contra lo "spam", bi ha un limite en o numero de vegadas que puede fer ista acción en un curto espacio de tiempo, y ha brincato d\'ixe limite. Aguarde bells menutos y prebe de fer-lo de nuevas.',
'protectedpagetext'    => 'Ista pachina ha estato protechita ta aprevenir a suya edición.',
'viewsourcetext'       => "Puede veyer y copiar o codigo fuent d'ista pachina:",
'protectedinterface'   => "Ista pachina furne o texto d'a interfaz ta o software. Ye protechita ta privar o vandalismo. Si creye que bi ha bella error, contacte con un administrador.",
'editinginterface'     => "'''Pare cuenta:''' Ye editando una pachina emplegata ta furnir o texto d'a interfaz de {{SITENAME}}. Os cambeos en ista pachina tendrán efecto en l'aparencia d'a interfaz ta os atros usuarios. Ta fer traduccions d'a interfaz, puede considerar fer servir [http://translatewiki.net/wiki/Main_Page?setlang=an translatewiki.net], o prochecto de localización de MediaWiki.",
'sqlhidden'            => '(Consulta SQL amagata)',
'cascadeprotected'     => 'Ista pachina ye protechita y no se puede editar porque ye incluyita en {{PLURAL:$1|a siguient pachina|as siguients pachinas}}, que son protechitas con a opción de "cascada": $2',
'namespaceprotected'   => "No tiene premiso ta editar as pachinas d'o espacio de nombres '''$1'''.",
'customcssjsprotected' => "No tiene premiso ta editar ista pachina porque contiene a confeguración presonal d'atro usuario.",
'ns-specialprotected'  => "No ye posible editar as pachinas d'o espacio de nombres {{ns:special}}.",
'titleprotected'       => "Iste títol no puede creyar-se porque ye estato protechito por [[User:$1|$1]].
A razón data ye ''$2''.",

# Virus scanner
'virus-badscanner'     => "Confeguración incorrecta: rastriador de virus esconoixito: ''$1''",
'virus-scanfailed'     => 'o rastreyo ha fallato (codigo $1)',
'virus-unknownscanner' => 'antivirus esconoixito:',

# Login and logout pages
'logouttext'                 => "'''Ha rematato a sesión.'''

Puede continar navegando por {{SITENAME}} anonimament, u puede [[Special:UserLogin|encetar]] una nueva sesión con o mesmo nombre d'usuario u bell atro diferent. Pare cuenta que, entre que se limpia a caché d'o navegador, puet estar que bellas pachinas s'amuestren como si encara continase en a sesión anterior.",
'welcomecreation'            => "== ¡Bienveniu(da), $1! ==
S'ha creyato a suya cuenta.
No xublide de presonalizar [[Special:Preferences|as suyas preferencias en {{SITENAME}}]].",
'yourname'                   => "Nombre d'usuario:",
'yourpassword'               => 'Contrasenya:',
'yourpasswordagain'          => 'Torne a escribir a contrasenya:',
'remembermypassword'         => "Remerar o mío nombre d'usuario y contrasenya entre sesions en iste ordinador (como muito por $1 {{PLURAL:$1|día|días}})",
'yourdomainname'             => 'Dominio:',
'externaldberror'            => "Bi habió una error d'autenticación externa d'a base de datos u bien no tiene premisos ta esviellar a suya cuenta externa.",
'login'                      => 'Encetar sesión',
'nav-login-createaccount'    => 'Encetar una sesión / creyar cuenta',
'loginprompt'                => "Ta rechistrar-se en {{SITENAME}} ha d'activar as cookies en o navegador.",
'userlogin'                  => 'Encetar una sesión / creyar cuenta',
'userloginnocreate'          => 'Encetar una sesión',
'logout'                     => "Salir d'a sesión",
'userlogout'                 => 'Salir',
'notloggedin'                => 'No ha dentrato en o sistema',
'nologin'                    => "No tiene garra cuenta? '''$1'''.",
'nologinlink'                => 'Creyar una nueva cuenta',
'createaccount'              => 'Creyar una nueva cuenta',
'gotaccount'                 => "Tiene ya una cuenta? '''$1'''.",
'gotaccountlink'             => 'Identificar-se y encetar sesión',
'createaccountmail'          => 'por correu electronico',
'createaccountreason'        => 'Razón:',
'badretype'                  => 'As contrasenyas que ha escrito no son iguals.',
'userexists'                 => "Ixe nombre d'usuario ya ye en uso.
Por favor, meta-ne uno diferent.",
'loginerror'                 => 'Error en encetar a sesión',
'createaccounterror'         => "No s'ha puesto creyar a cuenta: $1",
'nocookiesnew'               => "A cuenta d'usuario s'ha creyata, pero encara no ye indentificato. {{SITENAME}} fa servir <em>cookies</em> ta identificar a os usuario rechistratos, pero pareix que las tiene desactivatas. Por favor, active-las e identifique-se con o suyo nombre d'usuario y contrasenya.",
'nocookieslogin'             => "{{SITENAME}} fa servir <em>cookies</em> ta la identificación d'usuarios. Tiene as <em>cookies</em> desactivatas en o suyo navegador. Por favor, active-las y prebe d'identificar-se de nuevas.",
'noname'                     => "No ha escrito un nombre d'usuario correcto.",
'loginsuccesstitle'          => "S'ha identificato correctament",
'loginsuccess'               => 'Ha encetato una sesión en {{SITENAME}} como "$1".',
'nosuchuser'                 => 'No bi ha garra usuario clamato "$1".
Os nombres d\'usuario son sensibles a las mayusclas.
Comprebe si ha escrito bien o nombre u [[Special:UserLogin/signup|creye una nueva cuenta d\'usuario]].',
'nosuchusershort'            => 'No bi ha garra usuario con o nombre "<nowiki>$1</nowiki>". Comprebe si o nombre ye bien escrito.',
'nouserspecified'            => "Ha d'escribir un nombre d'usuario.",
'login-userblocked'          => "Iste usuario ye bloqueyau. No se permite l'inicio de sesión.",
'wrongpassword'              => 'A contrasenya indicata no ye correcta. Prebe unatra vegada.',
'wrongpasswordempty'         => 'No ha escrito garra contrasenya. Prebe unatra vegada.',
'passwordtooshort'           => 'As contrasenyas han de tener a lo menos {{PLURAL:$1|1 carácter|$1 carácters}}.',
'password-name-match'        => "A contrasenya ha d'estar diferent d'o suyo nombre d'usuario.",
'mailmypassword'             => 'Ninviar una nueva contrasenya por correu electronico',
'passwordremindertitle'      => 'Nueva contrasenya temporal de {{SITENAME}}',
'passwordremindertext'       => 'Bell un (probablement vusté mesmo, dende l\'adreza IP $1) demandó una nueva contrasenya ta la suya cuenta en {{SITENAME}} ($4). S\'ha creyato una nueva contrasenya temporal ta l\'usuario "$2", que ye "$3".
Si isto ye o que quereba, ha d\'encetar agora una sesión y trigar una nueva contrasenya.
A suya contrasenya temporal circumducirá en {{PLURAL:$5|un día|$5 días}}

Si estió bell atro qui fació ista demanda, u ya se\'n ha alcordau d\'a contrasenya y ya no deseya cambiar-la, puet ignorar iste mensache y continar fendo servir l\'antiga contrasenya.',
'noemail'                    => 'No bi ha garra adreza de correu electronico rechistrada ta "$1".',
'noemailcreate'              => "Has d'indicar una adreza de correu electronico valida",
'passwordsent'               => 'Una nueva contrasenya plega de ninviar-se ta o correu electronico de "$1".
Por favor, identifique-se un atra vez malas que la reculla.',
'blocked-mailpassword'       => "A suya adreza IP ye bloqueyata y, ta privar abusos, no se li premite emplegar d'a función de recuperación de contrasenyas.",
'eauthentsent'               => "S'ha ninviato un correu electronico de confirmación ta l'adreza especificata. Antes que no se ninvíe garra atro correu ta ixa cuenta, ha de confirmar que ixa adreza te pertenexe. Ta ixo, cal que siga as instruccions que trobará en o mensache.",
'throttled-mailpassword'     => "Ya s'ha ninviato un correu recordatorio con a suya contrasenya fa menos de {{PLURAL:$1|1 hora|$1 horas}}. Ta escusar abusos, nomás se ninvia un recordatorio cada {{PLURAL:$1|hora|$1 horas}}.",
'mailerror'                  => 'Error en ninviar o correu: $1',
'acct_creation_throttle_hit' => "Os vesitants d'iste wiki dende a suya adreza IP han creyato ya {{PLURAL:$1|1 cuenta|$1 cuentas}} en o zaguer día, o que ye o masimo premitito en iste periodo de tiempo.
Por ixo, no se pueden creyar más cuentas por agora dende ixa adreza IP.",
'emailauthenticated'         => 'A suya adreza de correu-e estió confirmata o $2 a las $3.',
'emailnotauthenticated'      => "A suya adreza de correu-e <strong> no ye encara confirmata </strong>. No podrá recullir garra correu t'as siguients funcions.",
'noemailprefs'               => 'Escriba una adreza de correu-e ta activar istas caracteristicas.',
'emailconfirmlink'           => 'Confirme a suya adreza de correu-e',
'invalidemailaddress'        => "No se puet acceptar l'adreza de correu-e pues pareix que tien un formato no conforme. Escriba una adreza bien formateyata, u deixe buedo ixe campo.",
'accountcreated'             => 'Cuenta creyata',
'accountcreatedtext'         => "S'ha creyato a cuenta d'usuario de $1.",
'createaccount-title'        => 'Creyar una cuenta en {{SITENAME}}',
'createaccount-text'         => 'Belún ha creyato una cuenta con o nombre "$2" en {{SITENAME}} ($4), con a contrasenya "$3" y indicando a suya adreza de correu. Habría de dentrar-ie agora y cambiar a suya contrasenya.

Si a cuenta s\'ha creyato por error, simplament ignore iste mensache.',
'usernamehasherror'          => "O nombre d'usuario no puet contener simbolos hash",
'login-throttled'            => 'Ha feito masiaus intentos ta encetar una sesión. Por favor, aspere antes de prebar de fer-lo unatra vegada.',
'loginlanguagelabel'         => 'Idioma: $1',
'suspicious-userlogout'      => "S'ha denegau a suya demanda de zarrar a sesión ya que pareix que la ninvió un navegador defectuoso u bell proxy amagau.",
'ratelimit-excluded-ips'     => ' #<!-- leave this line exactly as it is --> <pre>
# A sintaxi ye asinas:
#  * Tot o que bi ha dende un carácter "#" dica a fin d\'a linia ye un comentario
#  * Qualsiquier linia que no sía en blanco corresponde a una adreza IP excluyida d\'o limite de velocidat
   #</pre> <!-- leave this line exactly as it is -->',

# JavaScript password checks
'password-strength'            => "Livel de seguranza d'a contrasenya: $1",
'password-strength-bad'        => 'MALA',
'password-strength-mediocre'   => 'mediocre',
'password-strength-acceptable' => 'acceptable',
'password-strength-good'       => 'bueno',
'password-retype'              => 'Torne a escribir aquí a contrasenya',
'password-retype-mismatch'     => 'As contrasenyas no concuerdan',

# Password reset dialog
'resetpass'                 => 'Cambiar a contrasenya',
'resetpass_announce'        => 'Ha encetato una sesión con una contrasenya temporal que se le ninvió por correu. Por favor, escriba aquí una nueva contrasenya:',
'resetpass_text'            => '<!-- Adiba aquí o testo -->',
'resetpass_header'          => "Cambiar a contrasenya d'a cuenta",
'oldpassword'               => 'Contrasenya antiga:',
'newpassword'               => 'Nueva contrasenya:',
'retypenew'                 => 'Torne a escribir a nueva contrasenya:',
'resetpass_submit'          => 'Cambiar a contrasenya e identificar-se',
'resetpass_success'         => 'A suya contrasenya ya ye cambiata. Agora ya puede dentrar-ie...',
'resetpass_forbidden'       => 'No se pueden cambiar as contrasenyas.',
'resetpass-no-info'         => 'Debe identificar-se como usuario ta poder acceder dreitament ta ista pachina.',
'resetpass-submit-loggedin' => 'Cambiar a contrasenya',
'resetpass-submit-cancel'   => 'Cancelar',
'resetpass-wrong-oldpass'   => 'A contrasenya actual u temporal no ye conforme.
Talment ya ha cambiato a suya contrasenya u ha demandato una nueva contrasenya temporal.',
'resetpass-temp-password'   => 'Contrasenya temporal:',

# Edit page toolbar
'bold_sample'     => 'Texto en negreta',
'bold_tip'        => 'Texto en negreta',
'italic_sample'   => 'Texto en cursiva',
'italic_tip'      => 'Texto en cursiva',
'link_sample'     => "Títol d'o vinclo",
'link_tip'        => 'Vinclo interno',
'extlink_sample'  => "http://www.example.com Títol d'o vinclo",
'extlink_tip'     => 'Vinclo externo  (recuerde o prefixo http://)',
'headline_sample' => 'Texto de subtítol',
'headline_tip'    => 'Soztítol de livel 2',
'math_sample'     => 'Escriba aquí a formula',
'math_tip'        => 'Formula matematica (LaTeX)',
'nowiki_sample'   => 'Escriba aquí texto sin formateyar',
'nowiki_tip'      => 'Ignorar o formato wiki',
'image_sample'    => 'Exemplo.jpg',
'image_tip'       => 'Imachen incorporada',
'media_sample'    => 'Exemplo.ogg',
'media_tip'       => 'Vinclo ta un fichero',
'sig_tip'         => 'Sinyatura, calendata y hora',
'hr_tip'          => 'Linia horizontal (faiga-ne un emplego amoderau)',

# Edit pages
'summary'                          => 'Resumen:',
'subject'                          => 'Tema/títol:',
'minoredit'                        => 'He feito una edición menor',
'watchthis'                        => 'Cosirar ista pachina',
'savearticle'                      => 'Alzar pachina',
'preview'                          => 'Previsualización',
'showpreview'                      => 'Amostrar previsualización',
'showlivepreview'                  => 'Anvista previa',
'showdiff'                         => 'Amostrar cambeos',
'anoneditwarning'                  => "''Pare cuenta:'' No s'ha identificato con un nombre d'usuario. A suya adreza IP s'alzará en l'historial d'a pachina.",
'anonpreviewwarning'               => "''No s'ha identificau con una cuenta d'usuario. A suya adreza IP quedará rechistrada en l'historial d'edicions d'ista pachina.\"",
'missingsummary'                   => "'''Pare cuenta:''' No ha escrito garra resumen d'edición. Si puncha de nuevas en «{{int:savearticle}}» a suya edición se grabará sin resumen.",
'missingcommenttext'               => 'Por favor, escriba o texto astí baixo.',
'missingcommentheader'             => "'''Recordanza:''' No ha garra títol ta iste comentario. Si puncha de nuevas en \"{{int:savearticle}}\", a suya edición se grabará sin garra títol.",
'summary-preview'                  => "Veyer anvista previa d'o resumen:",
'subject-preview'                  => "Anvista previa d'o tema/títol:",
'blockedtitle'                     => "L'usuario ye bloqueyato",
'blockedtext'                      => "'''O suyo nombre d'usuario u adreza IP ye bloqueyato.'''

O bloqueyo lo fació $1.
A razón data ye ''$2''.

* Prencipio d'o bloqueyo: $8
* Fin d'o bloqueyo: $6
* Indentificación bloqueyata: $7

Puede contautar con $1 u con atro [[{{MediaWiki:Grouppage-sysop}}|almenistrador]] ta letigar sobre o bloqueyo.
No puede fer servir o vinclo 'ninviar correu electronico ta iste usuario' si no ha rechistrato una adreza conforme de correu electronico en as suyas [[Special:Preferences|preferencias]] y si no se l'ha vedau d'emplegar-la. A suya adreza IP actual ye $3, y o identificador d'o bloqueyo ye #$5. Por favor incluiga ixos datos quan faiga qualsiquier consulta.",
'autoblockedtext'                  => "A suya adreza IP s'ha bloqueyata automaticament porque la eba feito servir un atro usuario bloqueyato por \$1.

A razón d'o bloqueyo ye ista:

:''\$2''

* Prencipio d'o bloqueyo: \$8
* Fin d'o bloqueyo: \$6
* Usuario que se prebaba de bloqueyar: \$7

Puede contautar con \$1 u con atro d'os [[{{MediaWiki:Grouppage-sysop}}|almenistradors]] ta litigar sobre o bloqueyo.

Pare cuenta que no puede emplegar a función \"Ninviar correu electronico ta iste usuario\" si no tiene garra adreza de correu electronico conforme rechistrada en as suyas [[Special:Preferences|preferencias d'usuario]] u si se l'ha vedato d'emplegar ista función.

A suya adreza IP actual ye \$3, y o identificador de bloqueyo ye #\$5. Por favor incluiga os datos anteriors quan faga qualsiquier consulta.",
'blockednoreason'                  => "No s'ha dato garra causa",
'blockedoriginalsource'            => "Contino s'amuestra o codigo fuent de  '''$1''':",
'blockededitsource'                => "Contino s'amuestra o texto d'as suyas '''edicions''' a '''$1''':",
'whitelistedittitle'               => 'Cal encetar una sesión ta poder editar.',
'whitelistedittext'                => 'Ha de $1 ta poder editar pachinas.',
'confirmedittext'                  => "Ha de confirmar a suya adreza de correu-e antis de poder editar pachinas. Por favor, estableixca y confirme una adreza de correu-e a traviés d'as suyas [[Special:Preferences|preferencias d'usuario]].",
'nosuchsectiontitle'               => 'No se puede trobar ixa sección',
'nosuchsectiontext'                => "Ha mirau d'editar una sección que no existe.
Talment bell un l'haiga moviu u borrau entre que vusté vesitaba a pachina.",
'loginreqtitle'                    => 'Cal que encete una sesión',
'loginreqlink'                     => 'encetar una sesión',
'loginreqpagetext'                 => 'Ha de $1 ta veyer atras pachinas.',
'accmailtitle'                     => 'A contrasenya ha estato ninviata.',
'accmailtext'                      => "S'ha ninviato a $2 una contrasenya ta [[User talk:$1|$1]] chenerata aliatoriament.

A contrasenya ta ista nueva cuenta la puet cambiar en a pachina ''[[Special:ChangePassword|cambiar contrasenya]]'' dimpués d'haber dentrato en ella.",
'newarticle'                       => '(Nuevo)',
'newarticletext'                   => "Ha siguito un vinclo ta una pachina que encara no existe.
Ta creyar a pachina, prencipie a escribir en a caixa d'abaixo (mire-se l'[[{{MediaWiki:Helppage}}|aduya]] ta más información).
Si ye plegau por error, punche o botón \"enta zaga\" d'o suyo navegador.",
'anontalkpagetext'                 => "----''Ista ye a pachina de descusión d'un usuario anonimo que encara no ha creyato una cuenta, u no l'ha feito servir. Por ixo, hemos d'emplegar a suya adreza IP ta identificar-lo/a.
Diferents usuarios pueden compartir una mesma adreza IP.
Si vusté ye un usuario anonimo y creye que l'han escrito comentarios no relevants, [[Special:UserLogin/signup|creye una cuenta]] u [[Special:UserLogin/signup|identifique-se]] ta privar confusions futuras con atros usuarios anonimos.''",
'noarticletext'                    => 'Por agora no bi ha garra texto en ista pachina. Puet [[Special:Search/{{PAGENAME}}|mirar o títol d\'ista pachina]] en atras pachinas, <span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} mirar os rechistros relacionatos] u [{{fullurl:{{FULLPAGENAME}}|action=edit}} escribir ista pachina].',
'noarticletext-nopermission'       => 'Por l\'inte no i hai garra texto en ista pachina.
Puet [[Special:Search/{{PAGENAME}}|mirar iste títol]] en atras páginas,
u bien <span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} mirar en os rechistros relacionatos]</span>.',
'userpage-userdoesnotexist'        => 'A cuenta d\'usuario "$1" no ye rechistrada. Piense si quiere creyar u editar ista pachina.',
'userpage-userdoesnotexist-view'   => 'A cuenta d\'usuario "$1" no ye rechistrada.',
'blocked-notice-logextract'        => "Ista cuenta d'usuario ye actualment bloqueyata.
A zaguera dentrada d'o rechistro de bloqueyos s'amuestra contino:",
'clearyourcache'                   => "'''Pare cuenta: Si quiere veyer os cambeos dimpués d'alzar o fichero, puede estar que tienga que refrescar a caché d'o suyo navegador ta veyer os cambeos.'''

*'''Mozilla / Firefox / Safari:''' prete a tecla de ''Mayusclas'' mientras puncha ''Reload,'' u prete '''Ctrl-F5''' u '''Ctrl-R''' (''Command-R'' en un Macintosh);
*'''Konqueror: ''' punche ''Reload'' u prete ''F5;''
*'''Opera:''' limpiar a caché en ''Tools → Preferences;''
*'''Internet Explorer:''' prete ''Ctrl'' mientres puncha ''Refresh,'' u prete ''Ctrl-F5.''",
'usercssyoucanpreview'             => "'''Consello:''' Faiga servir o botón «{{int:showpreview}}» ta fer una prebatina con o nuevo CSS antes de no grabar-lo.",
'userjsyoucanpreview'              => "'''Consello:''' Faiga servir o botón «{{int:showpreview}}» ta fer una prebatina con o nuevo css/js antes de no grabar-lo.",
'usercsspreview'                   => "'''Remere que isto no ye que una previsualización d'o suyo CSS d'usuario.'''
'''Encara no s'ha alzato!'''",
'userjspreview'                    => "'''Remere que sólo ye previsualizando o suyo javascript d'usuario y encara no ye grabato!'''",
'userinvalidcssjstitle'            => "'''Pare cuenta:''' No bi ha garra aparencia clamata \"\$1\". Remere que as pachinas presonalizatas .css y .js tienen un títol en minusclas, p.e. {{ns:user}}:Foo/monobook.css en cuenta de {{ns:user}}:Foo/Monobook.css.",
'updated'                          => '(Esviellato)',
'note'                             => "'''Nota:'''",
'previewnote'                      => "'''Pare cuenta que isto no ye que l'anvista previa d'a pachina; os cambeos encara no s'ha alzato!'''",
'previewconflict'                  => "L'anvista previa li amostrará l'aparencia d'o texto dimpués d'alzar os cambeos.",
'session_fail_preview'             => "'''Ya lo sentimos, pero no hemos puesto alzar a suya edición por una perda d'os datos de sesion. Por favor, prebe de fer-lo una atra vez, y si encara no funciona, [[Special:UserLogout|salga d'a sesión]] y torne a identificar-se.'''",
'session_fail_preview_html'        => "'''Ya lo sentimos, pero no s'ha puesto procesar a suya edición por haber-se trafegato os datos de sesión.'''

''Como que {{SITENAME}} tiene l'HTML puro activato, s'ha amagato l'anviesta previa ta aprevenir ataques en JavaScript.''

'''Si ye mirando d'editar lechitimament, por favor, prebe una atra vegada. Si encara no funcionase alavez, prebe de [[Special:UserLogout|zarrar a sesión]] y dentrar-ie identificando-se de nuevas.'''",
'token_suffix_mismatch'            => "'''S'ha refusato a suya edición porque o suyo client ha esbarafundiato os carácters de puntuación en o editor. A edición s'ha refusata ta privar a corrompición d'a pachina de texto. Isto gosa escaixer quan se fa servir un servicio de proxy defectuoso alazetato en a web.'''",
'editing'                          => 'Editando $1',
'editingsection'                   => 'Editando $1 (sección)',
'editingcomment'                   => 'Editando $1 (nueva sección)',
'editconflict'                     => "Conflicto d'edición: $1",
'explainconflict'                  => "Bel atro usuario ha cambiato ista pachina dende que vusté prencipió a editar-la.
O quatrón de texto superior contiene o texto d'a pachina como ye actualment.
Os suyos cambeos s'amuestran en o quatrón de texto inferior.
Habrá d'incorporar os suyos cambeos en o texto existent.
'''Nomás''' o texto en o quatrón superior s'alzará quan prete o botón \"Alzar a pachina\".",
'yourtext'                         => 'O texto suyo',
'storedversion'                    => 'Versión almadazenata',
'nonunicodebrowser'                => "'''Pare cuenta: O suyo navegador no cumple a norma Unicode. S'ha activato un sistema d'edición alternativo que li premitirá d'editar articlos con seguridat: os carácters no ASCII aparixerán en a caixa d'edición como codigos hexadecimals.'''",
'editingold'                       => "'''Pare cuenta: Ye editando una versión antiga d'ista pachina. Si alza a pachina, totz os cambios feitos dende ixa revisión se perderán.'''",
'yourdiff'                         => 'Esferencias',
'copyrightwarning'                 => "Por favor, pare cuenta en que todas as contrebucions a {{SITENAME}} se consideran publicatas baixo a licencia $2 (se veigan os detalles en $1). Si no deseya que atra chent corricha os suyos escritos sin piedat y los distribuiga librement, alavez, no habría de meter-los aquí. En publicar aquí, tamién ye declarando que vusté mesmo escribió iste texto y ye l'amo d'os dreitos d'autor, u bien lo copió dende o dominio publico u de qualsiquier atra fuent libre.
'''NO COPIE SIN PREMISO ESCRITOS CON DREITOS D'AUTOR!'''<br />",
'copyrightwarning2'                => "Por favor, pare cuenta que todas as contrebucions a {{SITENAME}} pueden estar editatas, cambiatas u borratas por atros colaboradors. Si no deseya que atra chent corricha os suyos escritos sin piedat y los destribuiga librement, alavez, no debería meter-los aquí. <br /> En publicar aquí, tamién ye declarando que vusté mesmo escribió iste texto y ye o duenyo d'os dreitos d'autor, u bien lo copió dende o dominio publico u qualsiquier atra fuent libre (veyer $1 ta más información). <br />
'''NO COPIE SIN PREMISO ESCRITOS CON DREITOS D'AUTOR!'''",
'longpagewarning'                  => "'''Pare cuenta: Ista pachina tiene ya $1 kilobytes; bells navegadors pueden tener problemas en editar pachinas de 32 kB o más.
Considere, por favor, a posibilidat de troxar ista pachina en trestallos más chicotz.'''",
'longpageerror'                    => "'''ERROR: O texto que ha escrito ye de $1 kilobytes, que ye mayor que a grandaria maxima de $2 kilobytes. No se puede alzar.'''",
'readonlywarning'                  => "'''Pare cuenta: A base de datos ye bloqueyata por custions de mantenimiento. Por ixo, en iste inte ye imposible d'alzar as suyas edicions. Puede copiar y apegar o texto en un fichero y alzar-lo ta dimpués.'''

A esplicación ufierta por l'almenistrador que bloqueyó a base de datos ye ista: $1",
'protectedpagewarning'             => "'''Pare cuenta: Ista pachina s'ha protechito ta que nomás os usuarios con premisos d'almenistrador puedan editar-la.'''",
'semiprotectedpagewarning'         => "'''Pare cuenta:''' Ista pachina s'ha protechito ta que nomás os usuarios rechistratos puedan editar-la.",
'cascadeprotectedwarning'          => "'''Pare cuenta:''' Ista pachina ye protechita ta que nomás os almenistrador puedan editar-la, porque ye incluyita en {{PLURAL:$1|a siguient pachina, protechita|as siguients pachinas, protechitas}} con a opción de ''cascada'' :",
'titleprotectedwarning'            => "'''PARE CUENTA:  Ista pachina s'ha bloqueyato de traza que s'aprecisan [[Special:ListGroupRights|dreitos especificos]] ta creyar-la.'''
Como información adicional s'amuestra contino a zaguera dentrada en o rechistro.",
'templatesused'                    => '{{PLURAL:$1|Plantilla|Planillas}} emplegatas en ista pachina:',
'templatesusedpreview'             => '{{PLURAL:$1|Plantilla|Plantillas}} emplegadas en ista anvista previa:',
'templatesusedsection'             => '{{PLURAL:$1|Plantilla|Plantillas}} emplegatas en ista sección:',
'template-protected'               => '(protechita)',
'template-semiprotected'           => '(semiprotechita)',
'hiddencategories'                 => 'Ista pachina fa parte de {{PLURAL:$1|1 categoría amagata|$1 categorías amagatas}}:',
'edittools'                        => "<!-- Iste testo amanixerá baxo os formularios d'edizión y carga. -->",
'nocreatetitle'                    => "S'ha restrinchito a creyación de pachinas",
'nocreatetext'                     => '{{SITENAME}} ha restrinchito a creyación de nuevas pachinas. Puede tornar entazaga y editar una pachina ya existent, [[Special:UserLogin|identificarse u creyar una cuenta]].',
'nocreate-loggedin'                => 'No tiene premiso ta creyar nuevas pachinas.',
'sectioneditnotsupported-title'    => 'A edición por seccions no ye suportada',
'sectioneditnotsupported-text'     => 'A edición por seccions no ye suportada en ista pachina',
'permissionserrors'                => 'Errors de premisos',
'permissionserrorstext'            => 'No tiene premisos ta fer-lo, por {{PLURAL:$1|ista razón|istas razons}}:',
'permissionserrorstext-withaction' => 'No tiene premisos ta $2, por {{PLURAL:$1|ista razón|istas razons}}:',
'recreate-moveddeleted-warn'       => "Pare cuenta: ye creyando de nuevas una pachina que ya ha s'heba borrada denantes.'''

Considere si ye preciso continar editando ista pachina.
Contino s'amuestra o rechistro de borraus y treslaus ta ista pachina:",
'moveddeleted-notice'              => "Ista pachina s'ha borrato.
Contino s'amuestra o rechistro de borraus y treslaus como referencia.",
'log-fulllog'                      => 'Veyer o rechistro completo',
'edit-hook-aborted'                => 'Edición albortada por o grifio (hook).
No dio garra esplicación.',
'edit-gone-missing'                => "No s'ha puesto esviellar a pachina.
Pareix que la hesen borrau.",
'edit-conflict'                    => "Conflicto d'edición.",
'edit-no-change'                   => "S'ha ignorato a suya edición, pos no s'ha feito garra cambeo ta o texto.",
'edit-already-exists'              => "No s'ha puesto creyar una pachina nueva.
Ya existe.",

# Parser/template warnings
'expensive-parserfunction-warning'        => 'Pare cuenta: Ista pachina tiene masiadas cridas ta funcions de preproceso (parser functions) costosas.

Habría de tener-ne menos de {{PLURAL:$2|$2|$2}}, y por agora en tiene {{PLURAL:$1|$1|$1}}.',
'expensive-parserfunction-category'       => 'Pachinas con masiadas cridas a funcions de preproceso (parser functions) costosas',
'post-expand-template-inclusion-warning'  => "Pare cuenta: A mida d'inclusión d'a plantilla ye masiau gran.
Bellas plantillas no se bi incluyen.",
'post-expand-template-inclusion-category' => "Pachinas an que se brinca a mida d'inclusión d'as plantillas",
'post-expand-template-argument-warning'   => "Pare cuenta: Ista pachina contiene a lo menos un argumento de plantilla con una mida d'espansión masiau gran. S'han omeso estos argumentos.",
'post-expand-template-argument-category'  => 'Pachinas con argumentos de plantilla omesos',
'parser-template-loop-warning'            => "S'ha detectato un bucle de plantillas: [[$1]]",
'parser-template-recursion-depth-warning' => "S'ha brincato o limite de recursión de plantillas ($1)",
'language-converter-depth-warning'        => "S'ha blincau o limite de profundidat d'o conversor d'idiomas ($1)",

# "Undo" feature
'undo-success' => 'A edición se puet desfer.
Antes de desfer a edición, mire-se a siguient comparanza ta comprebar que ye ixo o que quiere fer, y alce alavez os cambios ta desfer asinas a edición.',
'undo-failure' => 'No se puet desfer a edición pues un atro usuario ha feito una edición intermeya.',
'undo-norev'   => "No s'ha puesto desfer a edición porque no existiba u ya s'heba borrato.",
'undo-summary' => 'Desfeita a edición $1 de [[Special:Contributions/$2|$2]] ([[User talk:$2|desc.]])',

# Account creation failure
'cantcreateaccounttitle' => 'No se puede creyar a cuenta',
'cantcreateaccount-text' => "A creyación de cuentas dende ixa adreza IP ('''$1''') estió bloqueyata por [[User:$3|$3]].

A razón indicada por $3 ye ''$2''",

# History pages
'viewpagelogs'           => "Veyer os rechistros d'ista pachina",
'nohistory'              => "Ista pachina no tiene un historial d'edicions.",
'currentrev'             => 'Versión actual',
'currentrev-asof'        => "Zaguera versión d'o $1",
'revisionasof'           => "Versión d'o $1",
'revision-info'          => "Versión d'o $1 feita por $2",
'previousrevision'       => '← Versión anterior',
'nextrevision'           => 'Versión siguient →',
'currentrevisionlink'    => 'Versión actual',
'cur'                    => 'act',
'next'                   => 'siguient',
'last'                   => 'ant',
'page_first'             => 'primeras',
'page_last'              => 'zagueras',
'histlegend'             => "Selección de diferencias: sinyale as versions a comparar y prete \"enter\" u o botón d'o cobaixo.<br />
Leyenda: '''({{int:cur}})''' = esferencias con a versión actual, '''({{int:last}})''' = esferencias con a versión anterior, '''{{int:minoreditletter}}''' = edición menor",
'history-fieldset-title' => 'Mirar en o historial',
'history-show-deleted'   => 'Nomás os borratos',
'histfirst'              => 'Primeras contrebucions',
'histlast'               => 'Zagueras',
'historysize'            => '({{PLURAL:$1|1 byte|$1 bytes}})',
'historyempty'           => '(buedo)',

# Revision feed
'history-feed-title'          => 'Historial de versions',
'history-feed-description'    => "Historial de versions d'ista pachina en o wiki",
'history-feed-item-nocomment' => '$1 en $2',
'history-feed-empty'          => "A pachina demandata no existe.
Puede que belún l'haiga borrata d'o wiki u renombrata.
Prebe de [[Special:Search|mirar en o wiki]] atras pachinas relevants.",

# Revision deletion
'rev-deleted-comment'         => "(s'ha sacato iste comentario)",
'rev-deleted-user'            => "(s'ha sacato iste nombre d'usuario)",
'rev-deleted-event'           => "(acción borrata d'o rechistro)",
'rev-deleted-user-contribs'   => "[nombre d'usuario u adreza IP elminada - edición amagada d'as contribucions]",
'rev-deleted-text-permission' => "Ista versión d'a pachina s'ha '''borrato'''.
Talment pueda trobe más detalles en o [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} rechistro de borraus].",
'rev-deleted-text-unhide'     => "Ista versión d'a pachina ha estau '''borrada'''.
Puede trobar más detalles en o [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} rechistro de borraus].
Como administrador encara puet [$1 veyer ista versión ] si lo deseya.",
'rev-suppressed-text-unhide'  => "Ista versión d'a pachina ha estau '''borrada'''.
Puet trobar más detalles en o [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} rechistro de borraus].
Como administrador encara puet [$1 veyer ista versión ] si lo deseya.",
'rev-deleted-text-view'       => "Ista versión d'a pachina s'ha '''borrato'''.
Como admenistrador, la puet veyer; talment trobe más detalles en o [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} rechistro de borraus].",
'rev-suppressed-text-view'    => "Ista versión d'a pachina s'ha '''borrato'''.
Como administrador, la puet veyer; puet trobar más detalles en o [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} rechistro de borraus].",
'rev-deleted-no-diff'         => "No puede veyer ista comparanza de pachinas porque una d'as versions s'ha '''borrato'''.
Puet trobar más detalles en o [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} rechistro de borraus].",
'rev-suppressed-no-diff'      => "Nop puet veyer ista diferencia porque una d'as versions ha estau '''borrata'''.",
'rev-deleted-unhide-diff'     => "Una d'as versions d'ista comparanza s'ha '''borrato'''.
Puet trobar más detalles en o [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} rechistro de borraus].
Como administrador podrá seguir [$1 veyendo ista comparanza] si lo deseya.",
'rev-suppressed-unhide-diff'  => "Una d'as versions d'ista comparanza s'ha '''borrato'''.
Puet trobar más detalles en o [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} rechistro de borraus].
Como administrador encara puet seguir [$1 veyendo ista comparanza] si lo deseya.",
'rev-deleted-diff-view'       => "Una d'as versions d'ista comparanza s'ha '''borrato'''.
Como administrador puede veyer o conteniu; puet trobar más detalles en o [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} rechistro de borraus].",
'rev-suppressed-diff-view'    => "Una d'as versions d'ista comparanza s'ha '''borrato'''.
Como administrador encara puet veyer o conteniu; puet trobar más detalles en o [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} rechistro de borraus].",
'rev-delundel'                => 'amostrar/amagar',
'rev-showdeleted'             => 'amostrar',
'revisiondelete'              => 'Borrar/restaurar versions',
'revdelete-nooldid-title'     => 'A versión de destino no ye conforme',
'revdelete-nooldid-text'      => "No ha indicato sobre qué versión u versions de destino s'ha d'aplicar ista función, a versión especificata no existe u ye mirando d'amagar a versión actual.",
'revdelete-nologtype-title'   => "No s'ha indicau garra mena de rechistro",
'revdelete-nologtype-text'    => 'No ha indicato sobre qué tipo de rechistro quiere fer ista acción.',
'revdelete-nologid-title'     => 'Dentrada de rechistro invalida',
'revdelete-nologid-text'      => 'No ha indicau sobre qué evento rechistrau quiere fer servir ista función u bien no existe a dentrada de rechistro que ha indicau.',
'revdelete-no-file'           => 'O fichero especificato no existe.',
'revdelete-show-file-confirm' => 'Seguro que quiere veyer una versión borrata d\'o fichero "<nowiki>$1</nowiki>" d\'o $2 a las $3?',
'revdelete-show-file-submit'  => 'Sí',
'revdelete-selected'          => "'''{{PLURAL:$2|Versión trigata|Versions trigatas}} de [[:$1]]:'''",
'logdelete-selected'          => "'''{{PLURAL:$1|Escaicimiento d'o rechistro trigato|Escaicimientos d'o rechistro trigatos}}:'''",
'revdelete-text'              => "'''As versions y esdevenimientos borratos encara apareixerán en o historial d'a pachina y en os rechistros, pero bellas partes d'o suyo conteniu serán inaccesibles ta o publico.'''
Atros admenistradors de {{SITENAME}} encara podrán acceder t'o conteniu amagato y podrán desfer o borrau a traviés d'ista mesma interfaz, fueras de si s'estableixen restriccions adicionals.",
'revdelete-confirm'           => "Por favor confirme que ye mirando de fer ísto, que entiende as conseqüencias, y que lo ye fendo d'alcuerdo con [[{{MediaWiki:Policy-url}}|as politicas]].",
'revdelete-suppress-text'     => "Os borraus de versions '''nomás''' s'habrían de fer en os siguients casos:
* Información potencialment difamatoria o libelo grieu.
* Información personal inapropiada
*: ''domicilios y números de telefono, numeros d'afiliación a la seguridat social, etc.''",
'revdelete-legend'            => 'Establir as restriccions de visibilidat:',
'revdelete-hide-text'         => "Amagar o texto d'a versión",
'revdelete-hide-image'        => "Amagar o conteniu d'o fichero",
'revdelete-hide-name'         => 'Amagar acción y obchectivo',
'revdelete-hide-comment'      => "Amagar comentario d'edición",
'revdelete-hide-user'         => "Amagar o nombre/l'adreza IP d'o editor",
'revdelete-hide-restricted'   => "Suprimir os datos d'os almenistradors igual como os d'a resta",
'revdelete-radio-same'        => '(no cambiar)',
'revdelete-radio-set'         => 'Sí',
'revdelete-radio-unset'       => 'No',
'revdelete-suppress'          => "Sacar os datos d'os almenistradors igual como os d'a resta d'usuarios",
'revdelete-unsuppress'        => "Sacar restriccions d'as versions restauradas",
'revdelete-log'               => 'Razón:',
'revdelete-submit'            => 'Aplicar a {{PLURAL:$1|la versión trigata|las versions trigatas}}',
'revdelete-logentry'          => "S'ha cambiato a visibilidat d'a versión de [[$1]]",
'logdelete-logentry'          => "S'ha cambiato a visibilidat d'escaicimientos de [[$1]]",
'revdelete-success'           => "'''S'ha cambiato correctament a visibilidat d'as versions.'''",
'revdelete-failure'           => "'''La visibilidat d'a versión no s'ha puesto esviellar:'''
$1",
'logdelete-success'           => "'''S'ha cambiato correctament a visibilidat d'os escaicimientos.'''",
'logdelete-failure'           => "'''A visibilidat d'o rechistro no s'ha puesto achustar:'''
$1",
'revdel-restore'              => 'Cambiar a visibilidat',
'revdel-restore-deleted'      => 'versions borradas',
'revdel-restore-visible'      => 'versions visibles',
'pagehist'                    => 'Historial',
'deletedhist'                 => 'Historial de borrau',
'revdelete-content'           => 'conteniu',
'revdelete-summary'           => 'editar resumen',
'revdelete-uname'             => "nombre d'usuario",
'revdelete-restricted'        => "S'han aplicato as restriccions ta almenistradors",
'revdelete-unrestricted'      => "S'han borrato as restriccions ta almenistradors",
'revdelete-hid'               => 'amagar $1',
'revdelete-unhid'             => 'amostrar $1',
'revdelete-log-message'       => '$1 ta $2 {{PLURAL:$2|versión|versions}}',
'logdelete-log-message'       => '$1 ta $2 {{PLURAL:$2|esdevenimiento|esdevenimientos}}',
'revdelete-hide-current'      => "Error en amagar l'obchecto de calendata $2 y $1: ista ye a versión actual.
No se puet amagar.",
'revdelete-show-no-access'    => 'Error amostrando l\'obchecto de calendata $2, $1: iste obchecto s\'ha marcau como "restrinchiu".
 No tien acceso a tot.',
'revdelete-modify-no-access'  => 'Error en modificar l\'obchecto de calendata $2, $1: este obchecto s\'ha marcau como "restrinchiu".
No tien acceso a ell.',
'revdelete-modify-missing'    => "Error en modificar l'obchecto ID $1: no se troba en base de datos!",
'revdelete-no-change'         => "'''Pare cuenta:''' a versión d'o $1 a las $2 ya tien as restriccions de visibilidat solicitadas.",
'revdelete-concurrent-change' => "Error en modificar l'elemento d'o $1 a las $2: pareix que o suyo estau ha estau cambiau por belatro usuario quan vusté miraba de modificar-lo. Por favor, verifique os rechistros.",
'revdelete-only-restricted'   => "Error en amagar l'elemento d'o $1 a las $2: no puet eliminar elementos d'a vista d'os administradors sin trigar alavez una d'as atras opcions de visibilidat.",
'revdelete-reason-dropdown'   => '*Razons comuns de borraus
** Violación de Copyright
** Información personal inapropiada
** Difamación u calumnias grieus',
'revdelete-otherreason'       => 'Atras/más razons:',
'revdelete-reasonotherlist'   => 'Atra razón',
'revdelete-edit-reasonlist'   => "Editar as razons d'o borrau",
'revdelete-offender'          => "Autor d'a edición:",

# Suppression log
'suppressionlog'     => 'Rechistro de supresions',
'suppressionlogtext' => "En o cobaixo bi ye una lista de borraus y bloqueyos referitos a contenius amagaus ta os almenistradors. Mire-se a [[Special:IPBlockList|lista d'adrezas IP bloqueyatas]] ta veyer a lista de bloqueyos y vedas bichents.",

# Revision move
'moverevlogentry'              => "S'ha tresladato {{PLURAL:$3|una versión|$3 versions}} dende $1 ta $2",
'revisionmove'                 => 'Tresladar versions dende "$1"',
'revmove-explain'              => "As siguients versions se tresladarán dende $1 t'a pachina destín especificata. Si o destín no existe, se creyará. D'atra traza, istas versions se fusionarán en o historial d'a pachina.",
'revmove-legend'               => 'Establir a pachina de destín y resumen',
'revmove-submit'               => "Tresladar versions t'a pachina trigada",
'revisionmoveselectedversions' => 'Tresladar as versions trigadas',
'revmove-reasonfield'          => 'Razón:',
'revmove-titlefield'           => 'Pachina de destín:',
'revmove-badparam-title'       => 'Parametros no conformes',
'revmove-badparam'             => 'A requesta contién parametros insuficients u erronios. Torne enta zaga y mire de fer-lo de nuevas.',
'revmove-norevisions-title'    => 'A versión de destino no ye conforme',
'revmove-norevisions'          => 'No ha especificato una u más version a on aplicar ista función u bien as versions especificatas no existen.',
'revmove-nullmove-title'       => 'Títol no conforme',
'revmove-nullmove'             => "As pachinas d'orichen y destín son a mesma. Torne enta zaga y escriba una pachina diferent de «[[$1]]».",
'revmove-success-existing'     => "S'ha tresladato {{PLURAL:$1|una versión de [[$2]]|$1 versions de [[$2]]}} t'a pachina existent [[$3]].",
'revmove-success-created'      => "S'ha tresladato {{PLURAL:$1|una versión de [[$2]]|$1 versions de [[$2]]}} t'a pachina recient creyata [[$3]].",

# History merging
'mergehistory'                     => 'Fusionar historials',
'mergehistory-header'              => "Ista pachina li premite de fusionar versions d'o historial d'una pachina d'orichen con una nueva pachina.
Asegure-se que iste cambio no trencará a continidat de l'historial d'a pachina.",
'mergehistory-box'                 => 'Fusionar as versions de dos pachinas:',
'mergehistory-from'                => "Pachina d'orichen:",
'mergehistory-into'                => 'Pachina de destino:',
'mergehistory-list'                => "Historial d'edicions fusionable",
'mergehistory-merge'               => "As siguients versions de [[:$1]] se pueden fundir con [[:$2]]. Faiga servir a columna de botons d'opciónradio ta fusionar nomás as versions creyadas antis d'un tiempo especificato. Pare cuenta que si emplega os vinclos de navegación meterá os botons en o suyo estau orichinal.",
'mergehistory-go'                  => 'Amostrar edicions fusionables',
'mergehistory-submit'              => 'Fusionar versions',
'mergehistory-empty'               => 'No puede fusionar-se garra revisión.',
'mergehistory-success'             => '$3 {{PLURAL:$3|revisión|revisions}} de [[:$1]] {{PLURAL:$3|fusionata|fusionatas}} correctament con [[:$2]].',
'mergehistory-fail'                => "No s'ha puesto fusionar os dos historials, por favor comprebe a pachina y os parametros de tiempo.",
'mergehistory-no-source'           => "A pachina d'orichen $1 no existe.",
'mergehistory-no-destination'      => 'A pachina de destino $1 no existe.',
'mergehistory-invalid-source'      => "A pachina d'orichen ha de tener un títol correcto.",
'mergehistory-invalid-destination' => 'A pachina de destino ha de tener un títol correcto.',
'mergehistory-autocomment'         => "S'ha fusionato [[:$1]] en [[:$2]]",
'mergehistory-comment'             => "S'ha fusionato [[:$1]] en [[:$2]]: $3",
'mergehistory-same-destination'    => "As pachinas d'orichen y destín han d'estar diferents",
'mergehistory-reason'              => 'Razón:',

# Merge log
'mergelog'           => "Rechistro d'unions",
'pagemerge-logentry' => "s'ha fusionato [[$1]] con [[$2]] (revisions dica o $3)",
'revertmerge'        => 'Desfer a fusión',
'mergelogpagetext'   => "Contino s'amuestra una lista d'as pachinas más recients que os suyos historials s'han fusionato con o d'atra pachina.",

# Diffs
'history-title'            => 'Historial de versions de "$1"',
'difference'               => '(Esferencias entre versions)',
'difference-multipage'     => '(Diferencia entre pachinas)',
'lineno'                   => 'Linia $1:',
'compareselectedversions'  => 'Confrontar as versions trigatas',
'showhideselectedversions' => 'Amostrar/amagar as versions trigadas',
'editundo'                 => 'desfer',
'diff-multi'               => "(No s'amuestra {{PLURAL:$1|una edición entremeya feita|$1 edicions entremeyas feitas}} por {{PLURAL:$2|un usuario|$2 usuarios}}).",
'diff-multi-manyusers'     => "(No s'amuestra {{PLURAL:$1|una edición entremeya|$1 edicions entremeyas}} feitas por más {{PLURAL:$2|d'un usuario|de $2 usuarios}})",

# Search results
'searchresults'                    => "Resultau d'a busca",
'searchresults-title'              => 'Resultaus de mirar "$1"',
'searchresulttext'                 => "Ta más información sobre cómo mirar pachinas en {{SITENAME}}, consulte l'[[{{MediaWiki:Helppage}}|{{int:help}}]].",
'searchsubtitle'                   => 'Ha mirato \'\'\'[[:$1]]\'\'\' ([[Special:Prefixindex/$1|todas as pachinas que prencipian con "$1"]]{{int:pipe-separator}}[[Special:WhatLinksHere/$1|todas as pachinas con vinclos enta "$1"]])',
'searchsubtitleinvalid'            => 'Ha mirato "$1"',
'toomanymatches'                   => "S'ha retornato masiadas coincidencias, por favor, torne a prebar con una consulta diferent",
'titlematches'                     => 'Consonancias de títols de pachina',
'notitlematches'                   => "No bi ha garra consonancia en os títols d'as pachinas",
'textmatches'                      => "Consonancias en o texto d'as pachinas",
'notextmatches'                    => "No bi ha garra consonancia en os textos d'as pachinas",
'prevn'                            => '{{PLURAL:$1|$1}} anteriors',
'nextn'                            => '{{PLURAL:$1|$1}} siguients',
'prevn-title'                      => '$1 {{PLURAL:$1|resultau anterior|resultaus anteriors}}',
'nextn-title'                      => 'Siguients $1 {{PLURAL:$1|resultau|resultaus}}',
'shown-title'                      => 'Amostrar $1 {{PLURAL:$1|resultau|resultaus}} por pachina',
'viewprevnext'                     => 'Veyer ($1 {{int:pipe-separator}} $2) ($3)',
'searchmenu-legend'                => 'Opcions de busca',
'searchmenu-exists'                => "'''Bi ha una pachina clamada \"[[\$1]]\" en ista wiki'''",
'searchmenu-new'                   => "'''Creyar a pachina \"[[:\$1]]\" en ista wiki!'''",
'searchhelp-url'                   => 'Help:Aduya',
'searchmenu-prefix'                => '[[Special:PrefixIndex/$1|Veyer pachinas con iste prefixo]]',
'searchprofile-articles'           => 'Pachinas de conteniu',
'searchprofile-project'            => "Pachinas d'aduya y d'o prochecto",
'searchprofile-images'             => 'Multimedia',
'searchprofile-everything'         => 'Tot',
'searchprofile-advanced'           => 'Avanzato',
'searchprofile-articles-tooltip'   => 'Mirar en $1',
'searchprofile-project-tooltip'    => 'Mirar en $1',
'searchprofile-images-tooltip'     => 'Mirar fichers',
'searchprofile-everything-tooltip' => 'Mirar en totz os contenius (tamién en as pachinas de descusión)',
'searchprofile-advanced-tooltip'   => 'Mirar en os siguients espacios de nombres',
'search-result-size'               => '$1 ({{PLURAL:$2|1 parola|$2 parolas}})',
'search-result-category-size'      => '{{PLURAL:$1|1 miembro|$1 miembros}} ({{PLURAL:$2|1 subcategoría|$2 subcategorías}}, {{PLURAL:$3|1 fichero|$3 fichers}})',
'search-result-score'              => 'Relevancia: $1%',
'search-redirect'                  => '(endrecera dende $1)',
'search-section'                   => '(sección $1)',
'search-suggest'                   => 'Quereba dicir $1?',
'search-interwiki-caption'         => 'Prochectos chermans',
'search-interwiki-default'         => '$1 resultaus:',
'search-interwiki-more'            => '(más)',
'search-mwsuggest-enabled'         => 'con socherencias',
'search-mwsuggest-disabled'        => 'garra socherencia',
'search-relatedarticle'            => 'Relacionato',
'mwsuggest-disable'                => "Desactivar as socherencias d'AJAX",
'searcheverything-enable'          => 'Mirar en totz os espacios de nombres',
'searchrelated'                    => 'relacionato',
'searchall'                        => 'totz',
'showingresults'                   => "Contino se bi {{PLURAL:$1|amuestra '''1''' resultau|amuestran '''$1''' resultaus}} prencipiando por o numero '''$2'''.",
'showingresultsnum'                => "Contino se bi {{PLURAL:$3|amuestra '''1''' resultau|amuestran os '''$3''' resultaus}} prencipiando por o numero '''$2'''.",
'showingresultsheader'             => "{{PLURAL:$5|Resultau '''$1''' de '''$3'''|Resultaus '''$1-$2''' de '''$3'''}} ta '''$4'''",
'nonefound'                        => "'''Pare cuenta''': Por defecto nomás se mira en bells espacios de nombres. Si quiere mirar en totz os contenius (incluyendo-ie pachinas de descusión, plantillas, etc), mire d'emplegar o prefixo ''all:'' u clave como prefixo o espacio de nombres deseyau.",
'search-nonefound'                 => "No s'ha trobato garra resultau que cumpla os criterios.",
'powersearch'                      => 'Busca avanzata',
'powersearch-legend'               => 'Busca avanzata',
'powersearch-ns'                   => 'Mirar en os espacios de nombres:',
'powersearch-redir'                => 'Listar reendreceras',
'powersearch-field'                => 'Mirar',
'powersearch-togglelabel'          => 'Marcar:',
'powersearch-toggleall'            => 'Totz',
'powersearch-togglenone'           => 'Garra',
'search-external'                  => 'Busca externa',
'searchdisabled'                   => 'A busca en {{SITENAME}} ye temporalment desactivata. Entremistanto, puede mirar en {{SITENAME}} fendo servir buscadors externos, pero pare cuenta que os suyos endices de {{SITENAME}} puede no estar esviellatos.',

# Quickbar
'qbsettings'               => 'Preferencias de "Quickbar"',
'qbsettings-none'          => 'Garra',
'qbsettings-fixedleft'     => 'Fixa a la zurda',
'qbsettings-fixedright'    => 'Fixa a la dreita',
'qbsettings-floatingleft'  => 'Flotant a la zurda',
'qbsettings-floatingright' => 'Flotant a la dreita',

# Preferences page
'preferences'                   => 'Preferencias',
'mypreferences'                 => 'Preferencias',
'prefs-edits'                   => "Numero d'edicions:",
'prefsnologin'                  => 'No ye identificato',
'prefsnologintext'              => 'Ha d\'haber <span class="plainlinks">[{{fullurl:{{#Special:UserLogin}}|returnto=$1}} encetato una sesión] </span> ta cambiar as preferencias d\'usuario.',
'changepassword'                => 'Cambiar a contrasenya',
'prefs-skin'                    => 'Aparencia',
'skin-preview'                  => 'Fer una prebatina',
'prefs-math'                    => 'Esprisions matematicas',
'datedefault'                   => 'Sin de preferencias',
'prefs-datetime'                => 'Calendata y hora',
'prefs-personal'                => 'Datos presonals',
'prefs-rc'                      => 'Zaguers cambeos',
'prefs-watchlist'               => 'Lista de seguimiento',
'prefs-watchlist-days'          => "Numero de días que s'amostrarán en a lista de seguimiento:",
'prefs-watchlist-days-max'      => '(masimo 7 diyas)',
'prefs-watchlist-edits'         => "Numero d'edicions que s'amostrarán en a lista ixamplata:",
'prefs-watchlist-edits-max'     => '(numero masimo: 1000)',
'prefs-watchlist-token'         => 'Ficha de lista de seguimiento:',
'prefs-misc'                    => 'Atras preferencias',
'prefs-resetpass'               => 'Cambear a contrasenya',
'prefs-email'                   => 'Opcions de correu electronico',
'prefs-rendering'               => 'Apariencia',
'saveprefs'                     => 'Alzar preferencias',
'resetprefs'                    => "Tornar t'as preferencias por defecto",
'restoreprefs'                  => 'Restaure todas as confeguracions por defecto',
'prefs-editing'                 => 'Edición',
'prefs-edit-boxsize'            => "Grandaria d'a finestra d'edición.",
'rows'                          => 'Ringleras:',
'columns'                       => 'Columnas:',
'searchresultshead'             => 'Mirar',
'resultsperpage'                => "Resultaus que s'amostrarán por pachina:",
'contextlines'                  => "Linias de contexto que s'amostrarán por resultau",
'contextchars'                  => 'Carácters de contexto por linia',
'stub-threshold'                => 'Branquil superior ta o formateyo de <a href="#" class="stub">vinclos ta borradors</a> (en bytes):',
'stub-threshold-disabled'       => 'Desactivato',
'recentchangesdays'             => "Días que s'amostrarán en ''zaguers cambeos'':",
'recentchangesdays-max'         => '(masimo $1 {{PLURAL:$1|día|días}})',
'recentchangescount'            => "Numero d'edicions a amostrar, por defecto:",
'prefs-help-recentchangescount' => 'Inclui os zaguers cambeos, historials de pachina y rechistros.',
'prefs-help-watchlist-token'    => "Si plena iste campo con una clau secreta se chenerará n filo RSS t'a suya lista de seguimeinto.
Qui conoixca ista clau podrá leyer a suya lista de seguimiento, asinas que esliya una clau segura.
Contino se i amuestra una calu chenerata de traza aleatoria que puede fer servir si quiere: $1",
'savedprefs'                    => "S'han alzato as suyas preferencias.",
'timezonelegend'                => 'Fuso horario:',
'localtime'                     => 'Hora local:',
'timezoneuseserverdefault'      => "Usar a zona d'o servidor",
'timezoneuseoffset'             => 'Atra (especifica a esferencia)',
'timezoneoffset'                => 'Esferencia¹:',
'servertime'                    => 'A hora en o servidor ye:',
'guesstimezone'                 => "Emplir-lo con a hora d'o navegador",
'timezoneregion-africa'         => 'Africa',
'timezoneregion-america'        => 'America',
'timezoneregion-antarctica'     => 'Antarctica',
'timezoneregion-arctic'         => 'Arctico',
'timezoneregion-asia'           => 'Asia',
'timezoneregion-atlantic'       => 'Ociano Atlantico',
'timezoneregion-australia'      => 'Australia',
'timezoneregion-europe'         => 'Europa',
'timezoneregion-indian'         => 'Ociano Indico',
'timezoneregion-pacific'        => 'Ociano Pacifico',
'allowemail'                    => "Activar a recepción de correu d'atros usuarios",
'prefs-searchoptions'           => 'Opcions de busca',
'prefs-namespaces'              => 'Espacios de nombres',
'defaultns'                     => 'Si no, mirar en istos espacios de nombres:',
'default'                       => 'por defecto',
'prefs-files'                   => 'fichers',
'prefs-custom-css'              => 'CSS presonalizato',
'prefs-custom-js'               => 'JS presonalizato',
'prefs-common-css-js'           => 'CSS/JS compartito ta todas as apariencias:',
'prefs-reset-intro'             => "Puet emplegar ista pachina ta restaurar as suyas preferencias a las valuras por defecto d'o sitio.
No se podrá desfer iste cambio.",
'prefs-emailconfirm-label'      => 'Confirmación de correu electronico:',
'prefs-textboxsize'             => "Mida d'a pachina d'edición",
'youremail'                     => 'Adreza de correu electronico:',
'username'                      => "Nombre d'usuario:",
'uid'                           => "ID d'usuario:",
'prefs-memberingroups'          => "Miembro {{PLURAL:$1|d'a colla|d'as collas}}:",
'prefs-registration'            => 'Tiempo de rechistro:',
'yourrealname'                  => 'Nombre reyal:',
'yourlanguage'                  => 'Luenga:',
'yourvariant'                   => 'Modalidat linguistica:',
'yournick'                      => 'Sinyatura:',
'prefs-help-signature'          => 'Os comentarios en pachina de discusión s\'han de sinyar con "<nowiki>~~~~</nowiki>", que se tornará en a suya sinyatura y calendata.',
'badsig'                        => 'A suya sinyadura no ye conforme; comprebe as etiquetas HTML.',
'badsiglength'                  => 'A sinyadura ye masiau larga.
Habría de tener menos de $1 {{PLURAL:$1|carácter|carácters}}.',
'yourgender'                    => 'Sexo:',
'gender-unknown'                => 'No especificato',
'gender-male'                   => 'Hombre',
'gender-female'                 => 'Muller',
'prefs-help-gender'             => 'Opcional: Emplegada ta corrección de chenero por o software. Ista información será publica.',
'email'                         => 'Adreza de correu-e',
'prefs-help-realname'           => "* Nombre reyal (opcional): si esliche escribir-lo, se ferá servir ta l'atribución d'a suya faina.",
'prefs-help-email'              => "L'adreza de correu-e ye opcional, pero ye precisa ta que le ninviemos una nueva contrasenya si nunc la xublidase. Tamién puede fer que atros usuarios puedan contactar con vusté dende a suya pachina d'usuario u de descusión d'usuario sin haber de revelar a suya identidat.",
'prefs-help-email-required'     => 'Cal una adreza de correu-e.',
'prefs-info'                    => 'Información basica',
'prefs-i18n'                    => 'Internacionalización',
'prefs-signature'               => 'Sinyatura',
'prefs-dateformat'              => 'Formato de calendata',
'prefs-timeoffset'              => 'Diferencia horaria',
'prefs-advancedediting'         => 'Opcions avanzadas',
'prefs-advancedrc'              => 'Opcions avanzadas',
'prefs-advancedrendering'       => 'Opcions avanzadas',
'prefs-advancedsearchoptions'   => 'Opcions avanzadas',
'prefs-advancedwatchlist'       => 'Opcions avanzadas',
'prefs-displayrc'               => 'Opcions de visualización',
'prefs-displaysearchoptions'    => 'Opcions de visualización',
'prefs-displaywatchlist'        => 'Opcions de visualización',
'prefs-diffs'                   => 'Diferencias',

# User rights
'userrights'                   => "Confeguración d'os dreitos d'os usuarios",
'userrights-lookup-user'       => "Confegurar collas d'usuarios",
'userrights-user-editname'     => "Escriba un nombre d'usuario:",
'editusergroup'                => "Editar as collas d'usuarios",
'editinguser'                  => "S'esta cambiando os dreitos de l'usuario  '''[[User:$1|$1]]''' ([[User talk:$1|{{int:talkpagelinktext}}]]{{int:pipe-separator}}[[Special:Contributions/$1|{{int:contribslink}}]])",
'userrights-editusergroup'     => "Editar as collas d'usuarios",
'saveusergroups'               => "Alzar as collas d'usuarios",
'userrights-groupsmember'      => 'Miembro de:',
'userrights-groupsmember-auto' => 'Miembro implicito de:',
'userrights-groups-help'       => "Puede cambiar as collas a on que bi ye iste usuario.
* Un caixa sinyalata significa que l'usuario ye en ixa colla.
* Una caixa no sinyalata significa que l'usuario no ye en ixa colla.
* Un * indica que vusté no puet sacar a colla dimpués d'adhibir-la, u vice-versa.",
'userrights-reason'            => 'Razón:',
'userrights-no-interwiki'      => "No tiene premiso ta editar os dreitos d'usuario en atras wikis.",
'userrights-nodatabase'        => 'A base de datos $1 no existe u no ye local.',
'userrights-nologin'           => "Ha d'[[Special:UserLogin|encetar una sesión]] con una cuenta d'almenistrador ta poder dar dreitos d'usuario.",
'userrights-notallowed'        => "A suya cuenta no tiene premisos ta dar dreitos d'usuario.",
'userrights-changeable-col'    => 'Grupos que puede cambiar',
'userrights-unchangeable-col'  => 'Collas que no puede cambiar',

# Groups
'group'               => 'Colla:',
'group-user'          => 'Usuarios',
'group-autoconfirmed' => 'Usuarios Autoconfirmatos',
'group-bot'           => 'Bots',
'group-sysop'         => 'Almenistradors',
'group-bureaucrat'    => 'Burocratas',
'group-suppress'      => 'Supervisors',
'group-all'           => '(totz)',

'group-user-member'          => 'Usuario',
'group-autoconfirmed-member' => 'Usuario autoconfirmato',
'group-bot-member'           => 'Bot',
'group-sysop-member'         => 'Almenistrador',
'group-bureaucrat-member'    => 'Burocrata',
'group-suppress-member'      => 'Supervisor',

'grouppage-user'          => '{{ns:project}}:Usuarios',
'grouppage-autoconfirmed' => '{{ns:project}}:Usuarios autoconfirmatos',
'grouppage-bot'           => '{{ns:project}}:Bots',
'grouppage-sysop'         => '{{ns:project}}:Almenistradors',
'grouppage-bureaucrat'    => '{{ns:project}}:Burocratas',
'grouppage-suppress'      => '{{ns:project}}:Supervisors',

# Rights
'right-read'                  => 'Leyer pachinas',
'right-edit'                  => 'Editar pachinas',
'right-createpage'            => 'Creyar pachinas (que no sían pachinas de descusión)',
'right-createtalk'            => 'Creyar pachinas de descusión',
'right-createaccount'         => "Creyar nuevas cuentas d'usuario",
'right-minoredit'             => 'Sinyalar como edicions menors',
'right-move'                  => 'Tresladar pachinas',
'right-move-subpages'         => 'Tresladar as pachinas con a suyas sozpachinas',
'right-move-rootuserpages'    => "Tresladar pachinas de l'usuario radiz",
'right-movefile'              => 'Tresladar fichers',
'right-suppressredirect'      => 'No creyar una reendrecera dende o nombre antigo quan se treslade una pachina',
'right-upload'                => 'Cargar fichers',
'right-reupload'              => "Cargar dencima d'un fichero existent",
'right-reupload-own'          => "Cargar dencima d'un fichero que ya eba cargau o mesmo usuario",
'right-reupload-shared'       => 'Sobreescribir localment fichers en o reposte multimedia compartito',
'right-upload_by_url'         => 'Cargar un fichero dende una adreza URL',
'right-purge'                 => 'Porgar a memoria caché ta una pachina sin necesidat de confirmar-la',
'right-autoconfirmed'         => 'Editar pachinas semiprotechitas',
'right-bot'                   => 'Ser tractato como un proceso automatico (bot)',
'right-nominornewtalk'        => 'Fer que as edicions menors en pachinas de descusión no cheneren l\'aviso de "nuevos mensaches"',
'right-apihighlimits'         => 'Usar limites más altos en consultas API',
'right-writeapi'              => "Emplego de l'API d'escritura",
'right-delete'                => 'Borrar pachinas',
'right-bigdelete'             => 'Borrar pachinas con historials largos',
'right-deleterevision'        => "Borrar y recuperar versions especificas d'una pachina",
'right-deletedhistory'        => "Veyer as dentradas borratas de l'historial, sin o suyo texto asociato",
'right-deletedtext'           => 'Veyer o texto borrato y os cambios entre versions borratas',
'right-browsearchive'         => 'Mirar pachinas borratas',
'right-undelete'              => 'Recuperar una pachina',
'right-suppressrevision'      => 'Revisar y recuperar versions amagatas ta os Admenistradors',
'right-suppressionlog'        => 'Veyer os rechistro privatos',
'right-block'                 => "Bloqueyar a atros usuarios ta privar-les d'editar",
'right-blockemail'            => 'Bloqueyar a un usuario ta privar-le de ninviar correus',
'right-hideuser'              => "Bloqueyar un nombre d'usuario, amagando-lo d'o publico",
'right-ipblock-exempt'        => "Ignorar os bloqueyos d'adrezas IP, os autobloqueyos y os bloqueyos de rangos de IPs.",
'right-proxyunbannable'       => 'Ignorar os bloqueyos automaticos de proxies',
'right-unblockself'           => 'Desbloqueyar-se ells mesmos',
'right-protect'               => 'Cambiar os livels de protección y editar pachinas protechitas',
'right-editprotected'         => 'Editar pachinas protechitas (sin de protección en cascada)',
'right-editinterface'         => "Editar a interficie d'usuario",
'right-editusercssjs'         => "Editar os fichers CSS y JS d'atros usuarios",
'right-editusercss'           => "Editar os fichers CSS d'atros usuarios",
'right-edituserjs'            => "Editar os fichers JS d'atros usuarios",
'right-rollback'              => "Desfer a escape as edicions d'o zaguer usuario que cambió una pachina",
'right-markbotedits'          => 'Sinyalar as edicions esfeitas como edicions de bot',
'right-noratelimit'           => 'No se vei afectato por os limites maximos',
'right-import'                => 'Importar pachinas dende atros wikis',
'right-importupload'          => 'Importar pachinas de fichers cargatos',
'right-patrol'                => 'Sinyalar edicions como patrullatas',
'right-autopatrol'            => 'Sinyalar automaticament as edicions como patrullatas',
'right-patrolmarks'           => 'Amostrar os sinyals de patrullache en os zaguers cambeos',
'right-unwatchedpages'        => 'Amostrar una lista de pachinas sin cosirar',
'right-trackback'             => 'Adhibir un trackback',
'right-mergehistory'          => "Fusionar l'historial d'as pachinas",
'right-userrights'            => "Editar totz os dreitos d'usuario",
'right-userrights-interwiki'  => "Editar os dreitos d'usuario d'os usuarios d'atros wikis",
'right-siteadmin'             => 'Trancar y destrancar a base de datos',
'right-reset-passwords'       => "Reiniciar a contrasenya d'atros usuarios",
'right-override-export-depth' => 'Exporta pachinas que incluigan as enlazadas dica un fundaria de 5',
'right-sendemail'             => 'Ninviar un correu electronico a atros usuarios',
'right-revisionmove'          => 'Tresladar versions',

# User rights log
'rightslog'      => "Rechistro de cambios en os dreitos d'os usuarios",
'rightslogtext'  => "Iste ye un rechistro d'os cambios en os dreitos d'os usuarios",
'rightslogentry' => "ha cambiato os dreitos d'usuario de $1: de $2 a $3",
'rightsnone'     => '(garra)',

# Associated actions - in the sentence "You do not have permission to X"
'action-read'                 => 'leyer ista pachina',
'action-edit'                 => 'editar ista pachina',
'action-createpage'           => 'creyar pachinas',
'action-createtalk'           => 'creyar pachinas de descusión',
'action-createaccount'        => "creyar ista cuenta d'usuario",
'action-minoredit'            => 'sinyalar iste cambeo como menor',
'action-move'                 => 'tresladar ista pachina',
'action-move-subpages'        => 'tresladar ista pachina y as suyas subpachinas',
'action-move-rootuserpages'   => "tresladar as pachinas de l'usuario radiz",
'action-movefile'             => 'tresladar iste fichero',
'action-upload'               => 'cargar iste fichero',
'action-reupload'             => "cargar dencima d'un fichero existent",
'action-reupload-shared'      => "cargar dencima d'iste fichero en un reposte compartito",
'action-upload_by_url'        => 'cargar iste fichero dende una adreza URL',
'action-writeapi'             => "fer servir l'API d'escritura",
'action-delete'               => 'borrar ista pachina',
'action-deleterevision'       => 'borrar ista versión',
'action-deletedhistory'       => "veyer o historial borrato d'ista pachina",
'action-browsearchive'        => 'mirar pachinas borratas',
'action-undelete'             => 'recuperar ista pachina',
'action-suppressrevision'     => 'revisar y restaurar ista versión amagata',
'action-suppressionlog'       => 'veyer iste rechistro privato',
'action-block'                => 'bloqueyar iste usuario ta que no pueda editar',
'action-protect'              => "cambiar os livels de protección d'ista pachina",
'action-import'               => 'importar ista pachina dende atro wiki',
'action-importupload'         => 'importar ista pachina dende un fichero cargato',
'action-patrol'               => "sinyalar as edicions d'atros como patrulladas",
'action-autopatrol'           => 'sinyalar as edicions propias como patrulladas',
'action-unwatchedpages'       => 'veyer a lista de pachinas no cosiratas',
'action-trackback'            => "ninviar información d'una referencia",
'action-mergehistory'         => "fusionar l'historial d'ista pachina",
'action-userrights'           => "cambiar totz os dreitos d'usuario",
'action-userrights-interwiki' => "cambiar os dreitos d'usuario en atros wikis",
'action-siteadmin'            => 'bloqueyar u desbloqueyar a base de datos',
'action-revisionmove'         => 'tresladar versions',

# Recent changes
'nchanges'                          => '$1 {{PLURAL:$1|cambeo|cambeos}}',
'recentchanges'                     => 'Zaguers cambeos',
'recentchanges-legend'              => 'Opcions sobre a pachina de zaguers cambeos',
'recentchangestext'                 => "Siga os cambeos más recients d'a wiki en ista pachina.",
'recentchanges-feed-description'    => "Seguir os cambios más recients d'o wiki en ista fuent de noticias.",
'recentchanges-label-newpage'       => 'Ista edición ha creyau una nueva pachina',
'recentchanges-label-minor'         => 'Ista ye una edición menor',
'recentchanges-label-bot'           => 'Ista edición fue feita por un bot',
'recentchanges-label-unpatrolled'   => "Esta edición encara no s'ha controlato",
'rcnote'                            => "Contino {{PLURAL:$1|s'amuestra o unico cambeo feito|s'amuestran os zaguers '''$1''' cambeos feitos}} en {{PLURAL:$2|o zaguer día|os zaguers '''$2''' días}}, dica o $5, $4.",
'rcnotefrom'                        => "Contino s'amuestran os cambeos dende '''$2''' (dica '''$1''').",
'rclistfrom'                        => 'Amostrar cambeos recients dende $1',
'rcshowhideminor'                   => '$1 as edicions menors',
'rcshowhidebots'                    => '$1 bots',
'rcshowhideliu'                     => '$1 usuarios rechistraus',
'rcshowhideanons'                   => '$1 usuarios anonimos',
'rcshowhidepatr'                    => '$1 edicions controlatas',
'rcshowhidemine'                    => '$1 as mías edicions',
'rclinks'                           => 'Amostrar os zaguers $1 cambeos en os zaguers $2 días.<br />$3',
'diff'                              => 'dif',
'hist'                              => 'hist',
'hide'                              => 'Amagar',
'show'                              => 'Amostrar',
'minoreditletter'                   => 'm',
'newpageletter'                     => 'N',
'boteditletter'                     => 'b',
'number_of_watching_users_pageview' => '[$1 {{PLURAL:$1|usuario|usuarios}} cosirando]',
'rc_categories'                     => 'Limite d\'as categorías (deseparatas por "|")',
'rc_categories_any'                 => 'Todas',
'newsectionsummary'                 => 'Nueva sección: /* $1 */',
'rc-enhanced-expand'                => 'Amostrar detalles (cal JavaScript)',
'rc-enhanced-hide'                  => 'Amagar detalles',

# Recent changes linked
'recentchangeslinked'          => 'Cambeos relacionatos',
'recentchangeslinked-feed'     => 'Cambeos relacionatos',
'recentchangeslinked-toolbox'  => 'Cambios relacionatos',
'recentchangeslinked-title'    => 'Cambeos relacionatos con "$1"',
'recentchangeslinked-noresult' => 'No bi habió cambeos en as pachinas vinculatas en o intervalo de tiempo indicato.',
'recentchangeslinked-summary'  => "Ista ye una lista d'os zaguers cambios feitos en pachinas con vinclos dende una pachina especifica (u ta miembros d'una categoría especificata).  S'amuestran en '''negreta''' as pachinas d'a suya [[Special:Watchlist|lista de seguimiento]].",
'recentchangeslinked-page'     => "Nombre d'a pachina:",
'recentchangeslinked-to'       => "En cuentas d'ixo, amostrar os cambios en pachinas con vinclos enta a pachina data",

# Upload
'upload'                      => 'Cargar fichero',
'uploadbtn'                   => 'Cargar un fichero',
'reuploaddesc'                => 'Anular a carga y tornar ta o formulario de carga de fichers.',
'upload-tryagain'             => "Ninviar a descripción de l'archivo modificau",
'uploadnologin'               => 'No ha encetato una sesión',
'uploadnologintext'           => "Ha d'estar [[Special:UserLogin|rechistrau]] ta cargar fichers.",
'upload_directory_missing'    => 'O directorio de carga ($1) no existe y no lo puede creyar o servidor web.',
'upload_directory_read_only'  => 'O servidor web no puede escribir en o directorio de carga de fichers ($1).',
'uploaderror'                 => "S'ha producito una error en cargar o fichero",
'upload-recreate-warning'     => "''Pare cuenta: T'a suya conveniencia s'ha muestra aquí o fichero con ixe nombre ha sido eliminado o renombrado.'''

T'a suya conveniencia s'su conveniencia se muestra aquí el registro de supresiones y traslados de esta página:",
'uploadtext'                  => "Faiga servir o formulario d'o cobaixo ta cargar fichers.
Ta veyer u mirar fichers cargatas denantes vaiga t'a [[Special:FileList|lista de fichers cargatos]]. As cargas y recargas tamién se rechistran en o [[Special:Log/upload|rechistro de cargas]], y os borraus en o [[Special:Log/delete|rechistro de borraus]].

Ta incluyir un fichero en una pachina, emplegue un vinclo d'una d'istas trazas
*'''<tt><nowiki>[[</nowiki>{{ns:file}}<nowiki>:Fichero.jpg]]</nowiki></tt>''' ta fer servir a version completa d'o fichero,
*'''<tt><nowiki>[[</nowiki>{{ns:file}}<nowiki>:Fichero.png|200px|thumb|left|texto alternativo]]</nowiki></tt>''' ta fer serivr una versión de 200 píxels d'amplaria en una caixa a la marguin cucha con 'texto alternativo' como descripción
*'''<tt><nowiki>[[</nowiki>{{ns:media}}<nowiki>:Fichero.ogg]]</nowiki></tt>''' ta fer un vinclo dreitament ta o fichero sin amostrar-lo.",
'upload-permitted'            => 'Tipos de fichero premititos: $1.',
'upload-preferred'            => 'Tipos de fichero preferitos: $1.',
'upload-prohibited'           => 'Tipos de fichero vedatos: $1.',
'uploadlog'                   => 'rechistro de cargas',
'uploadlogpage'               => 'Rechistro de cargas de fichers',
'uploadlogpagetext'           => "Contino s'amuestra una lista d'os zaguers fichers cargatos. Mire-se a [[Special:NewFiles|galería de nuevos fichers]] ta tener una anvista más visual.",
'filename'                    => "Nombre d'o fichero",
'filedesc'                    => 'Resumen',
'fileuploadsummary'           => 'Resumen:',
'filereuploadsummary'         => "Cambios d'o fichero:",
'filestatus'                  => "Estau d'os dreitos d'autor (copyright):",
'filesource'                  => 'Fuent:',
'uploadedfiles'               => 'Fichers cargatos',
'ignorewarning'               => "Ignorar l'aviso y alzar o fichero en qualsiquier caso",
'ignorewarnings'              => 'Ignorar qualsiquier aviso',
'minlength1'                  => 'Os nombres de fichero han de tener a lo menos una letra.',
'illegalfilename'             => "O nombre de fichero «$1» tiene carácters no premititos en títols de pachinas. Por favor, cambee o nombre d'o fichero y mire de tornar a cargarlo.",
'badfilename'                 => 'O nombre d\'a imachen s\'ha cambiato por "$1".',
'filetype-mime-mismatch'      => "A extensión d'o fichero no coincide con o suyo tipo MIME.",
'filetype-badmime'            => 'No se premite cargar fichers de tipo MIME "$1".',
'filetype-bad-ie-mime'        => 'No puet cargar iste fichero porque o Internet Explorer lo consideraría como "$1", que ye un tipo de fichero no premitito y potencialment perigloso.',
'filetype-unwanted-type'      => "Os '''\".\$1\"''' son un tipo de fichero no deseyato.  Se prefieren os fichers {{PLURAL:\$3|de tipo|d'os tipos}} \$2.",
'filetype-banned-type'        => "No se premiten os fichers de tipo '''\".\$1\"'''. {{PLURAL:\$3|O tipo premitito ye|Os tipos premititos son}} \$2.",
'filetype-missing'            => 'O fichero no tiene garra estensión (como ".jpg").',
'empty-file'                  => 'O fichero que ninvió yera buedo.',
'file-too-large'              => 'O fichero que ninvió ye masiau gran.',
'filename-tooshort'           => 'O nombre de fichero ye masiau curto.',
'filetype-banned'             => 'Iste tipo de fichero ye vedau.',
'verification-error'          => 'Iste fichero no pasó a verificación de fichers.',
'hookaborted'                 => "A modificación que ha mirau de fer l'ha cancelau un hook d'extensión.",
'illegal-filename'            => 'O nombre de fichero no ye premitiu.',
'overwrite'                   => 'No se premite de sobrescribir un fichero existent.',
'unknown-error'               => 'Ha ocurriu una error desconoixida.',
'tmp-create-error'            => "No s'ha puesto creyar o fichero temporal.",
'tmp-write-error'             => 'Error en escribir o fichero temporal.',
'large-file'                  => 'Se consella que os fichers no sigan mayors de $1; iste fichero ocupa $2.',
'largefileserver'             => "A grandaria d'iste fichero ye mayor d'a que a confeguración d'iste servidor premite.",
'emptyfile'                   => 'Parixe que o fichero que se miraba de cargar ye buedo; por favor, comprebe que ixe ye reyalment o fichero que quereba cargar.',
'fileexists'                  => "Ya bi ha un fichero con ixe nombre.
Por favor, Por favor mire-se o fichero existent '''<tt>[[:$1]]</tt>''' si no ye seguro de querer sustituyir-lo.
[[$1|thumb]]",
'filepageexists'              => "A pachina de descripción d'iste fichero ya s'ha creyau en '''<tt>[[:$1]]</tt>''', pero no i hai garra fichero con iste nombre. O resumen que escriba no amaneixerá en a pachina de descripción.
Si quiere que o suyo resumen amaneixca aquí, habrá d'editar-lo manualment.
[[$1|thumb]]",
'fileexists-extension'        => "Ya bi ha un fichero con un nombre pareixiu: [[$2|thumb]]
* Nombre d'o fichero que ye cargando: '''<tt>[[:$1]]</tt>'''
* Nombre d'o fichero ya existent: '''<tt>[[:$2]]</tt>'''
Por favor, trigue un nombre diferent.",
'fileexists-thumbnail-yes'    => "Pareix que o fichero ye una imachen chicota ''(miniatura)''. [[$1|thumb]]
Comprebe por favor o fichero '''<tt>[[:$1]]</tt>'''.
Si o fichero comprebato ye a mesma imachen en tamanyo orichinal no cal cargar una nueva miniatura.",
'file-thumbnail-no'           => "O nombre d'o fichero prencipia con '''<tt>$1</tt>'''.
Pareix que estase una imachen achiquida ''(thumbnail)''.
Si tiene ista imachen a toda resolución, cargue-la, si no, por favor, cambee o nombre d'o fichero.",
'fileexists-forbidden'        => 'Ya bi ha un fichero con iste nombre, y no se puet sobrescribir.
Si encara quiere cargar ixe fichero, torne y faiga servir un nuevo nombre. [[File:$1|thumb|center|$1]]',
'fileexists-shared-forbidden' => 'Ya bi ha un fichero con ixe nombre en o reposte compartito. Si encara quiere cargar o fichero, por favor, torne entazaga y faiga servir un nuevo nombre. [[File:$1|thumb|center|$1]]',
'file-exists-duplicate'       => "Iste fichero ye un duplicau {{PLURAL:$1|d'o siguient fichero|d'os siguients fichers}}:",
'file-deleted-duplicate'      => "Un fichero igual que iste ([[$1]]) s'ha borrato enantes. Debería mirar-se o historial de borraus d'o fichero antes de continar cargando-lo atra vegada.",
'uploadwarning'               => 'Alvertencia de carga de fichero',
'uploadwarning-text'          => "Por favor, modifique a descripción d'o fichero d'abaixo y torne a intentar-lo.",
'savefile'                    => 'Alzar fichero',
'uploadedimage'               => '«[[$1]]» cargato.',
'overwroteimage'              => 's\'ha cargato una nueva versión de "[[$1]]"',
'uploaddisabled'              => 'A carga de fichers ye desactivata',
'copyuploaddisabled'          => 'Carga por URL desactivada.',
'uploadfromurl-queued'        => "S'ha metiu en a ringlera a suya carga.",
'uploaddisabledtext'          => 'A carga de fichers ye desactivata.',
'php-uploaddisabledtext'      => 'A carga de fichers PHP ye desactivata. Por favor, verfique a confeguración de file_uploads.',
'uploadscripted'              => 'Iste fichero contiene codigo de script u HTML que puede estar interpretado incorrectament por un navegador.',
'uploadvirus'                 => 'Iste fichero tiene un virus! Detalles: $1',
'upload-source'               => 'Fichero fuent',
'sourcefilename'              => "Nombre d'o fichero d'orichen:",
'sourceurl'                   => "URL d'orichen",
'destfilename'                => "Nombre d'o fichero de destín:",
'upload-maxfilesize'          => "Masima grandaria d'o fichero: $1",
'upload-description'          => "Descripción d'o fichero",
'upload-options'              => 'Opcions de carga',
'watchthisupload'             => 'Cosirar iste fichero',
'filewasdeleted'              => 'Una fichero con iste mesmo nombre ya se cargó denantes y estió borrato dimpués. Habría de comprebar $1 antes de tornar a cargar-lo una atra vegada.',
'upload-wasdeleted'           => "'''Pare cuenta: Ye cargando un fichero que ya estió borrato d'antes más.'''

Habría de repensar si ye apropiato continar con a carga d'iste fichero. Aquí tiene o rechistro de borrau d'iste fichero ta que pueda comprebar a razón que se dio ta borrar-lo:",
'filename-bad-prefix'         => "O nombre d'o fichero que ye cargando prencipia por '''\"\$1\"''', que ye un nombre no descriptivo que gosa clabar automaticament as camaras dichitals. Por favor, trigue un nombre más descriptivo ta iste fichero.",
'filename-prefix-blacklist'   => ' #<!-- dixe ista linia esautament igual como ye --> <pre>
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
'upload-success-subj'         => 'Cargata correctament',
'upload-success-msg'          => 'A carga de [$2] ha surtiu con exito. Ye disponible aquí: [[:{{ns:file}}:$1]]',
'upload-failure-subj'         => 'Problema en a carga',
'upload-failure-msg'          => 'I ha habiu un problema con o a suya carga dende [$2]:

$1',
'upload-warning-subj'         => 'Alvertencia de carga',
'upload-warning-msg'          => 'I habió un problea con a carga de [$2]. Puede tornar ta [[Special:Upload/stash/$1|upload form]] pa correchir iste problema.',

'upload-proto-error'        => 'Protocolo incorrecto',
'upload-proto-error-text'   => 'Si quiere cargar fichers dende atra pachina, a URL ha de prencipiar por <code>http://</code> u <code>ftp://</code>.',
'upload-file-error'         => 'Error interna',
'upload-file-error-text'    => "Ha escaicito una error interna entre que se prebaba de creyar un fichero temporal en o servidor. Por favor, contaute con un [[Special:ListUsers/sysop|almenistrador]] d'o sistema.",
'upload-misc-error'         => 'Error esconoixita en a carga',
'upload-misc-error-text'    => "Ha escaixito una error entre que se cargaba o fichero. Por favor, comprebe que a URL ye conforme y accesible y dimpués prebe de fer-lo una atra vegada. Si o problema contina, contaute con un [[Special:ListUsers/sysop|almenistrador]] d'o sistema.",
'upload-too-many-redirects' => 'A URL contien masiadas endreceras',
'upload-unknown-size'       => 'Grandaria desconoixid',
'upload-http-error'         => 'Ha ocorriu una error HTTP: $1',

# img_auth script messages
'img-auth-accessdenied' => 'Acceso refusau',
'img-auth-nopathinfo'   => 'Falta PATH_INFO.
O suyo servidor no ye configurau ta pasar ista información.
Puet que siga basau en CGI y no siga compatible con img_auth.
Se veiga http://www.mediawiki.org/wiki/Manual:Image_Authorization.',
'img-auth-notindir'     => 'A rota solicitada no ye en o directorio de cargas configurau',
'img-auth-badtitle'     => 'No s\'ha puesto construyir un títol valito dende "$1".',
'img-auth-nologinnWL'   => 'No ha encetau sesión y "$1" no ye en a lista blanca.',
'img-auth-nofile'       => 'No existe l\'archivo "$1".',
'img-auth-isdir'        => 'Ye mirando d\'acceder ta un directorio "$1".
Nomás ye premitito l\'acceso ta os fichers.',
'img-auth-streaming'    => 'Streaming (lectura contina) "$1".',
'img-auth-public'       => "A función de img_auth.php ye amostrar archivos dende una wiki privada.
Ista wiki ye configurada como wiki publica.
Por seguridat, s'ha desactivau img_auth.php.",
'img-auth-noread'       => 'L\'usuario no tien acceso de lectura ta "$1".',

# HTTP errors
'http-invalid-url'      => 'URL incorrecta: $1',
'http-invalid-scheme'   => 'As URLs con con prefixo "$1" no son compatibles',
'http-request-error'    => 'A demanda HTTP ha fallau por una error desconoixida.',
'http-read-error'       => 'Error de lectura HTTP.',
'http-timed-out'        => 'A requesta HTTP ha circumducito.',
'http-curl-error'       => 'Error en recuperar a URL: $1',
'http-host-unreachable' => "No s'ha puesto acceder t'a URL.",
'http-bad-status'       => 'Ha habiu un problema en a requesta HTTP: $1 $2',

# Some likely curl errors. More could be added from <http://curl.haxx.se/libcurl/c/libcurl-errors.html>
'upload-curl-error6'       => "No s'ha puesto acceder t'a URL",
'upload-curl-error6-text'  => "No s'ha puesto plegar en a URL. Por favor, comprebe que a URL sía correcta y o sitio web sía funcionando.",
'upload-curl-error28'      => "Tiempo d'aspera sobrexito",
'upload-curl-error28-text' => "O tiempo de respuesta d'a pachina ye masiau gran. Por favor, comprebe si o servidor ye funcionando, aguarde bell ratet y mire de tornar a fer-lo.  Talment deseye prebar de nuevas quan o ret tienga menos carga.",

'license'            => 'Licencia:',
'license-header'     => 'Licenciando',
'nolicense'          => "No s'en ha trigato garra",
'license-nopreview'  => '(Anvista previa no disponible)',
'upload_source_url'  => ' (una URL conforme y publicament accesible)',
'upload_source_file' => ' (un fichero en o suyo ordenador)',

# Special:ListFiles
'listfiles-summary'     => "Ista pachina especial amuestra totz os fichers cargatos.
Por defecto os zaguers fichers cargatos s'amuestran en o cobalto d'a lista.
Fendo click en un encabezau de columna se cambia o criterio d'ordenación.",
'listfiles_search_for'  => "Mirar por nombre d'o fichero:",
'imgfile'               => 'fichero',
'listfiles'             => 'Lista de imachens',
'listfiles_date'        => 'Calendata:',
'listfiles_name'        => 'Nombre',
'listfiles_user'        => 'Usuario',
'listfiles_size'        => 'Grandaria (bytes)',
'listfiles_description' => 'Descripción',
'listfiles_count'       => 'Versions',

# File description page
'file-anchor-link'          => 'Fichero',
'filehist'                  => "Historial d'o fichero",
'filehist-help'             => 'Punche en una calendata/hora ta veyer o fichero como amaneixeba por ixas envueltas.',
'filehist-deleteall'        => 'borrar-lo tot',
'filehist-deleteone'        => 'borrar',
'filehist-revert'           => 'revertir',
'filehist-current'          => 'actual',
'filehist-datetime'         => 'Calendata/Hora',
'filehist-thumb'            => 'Miniatura',
'filehist-thumbtext'        => "Miniatura d'a versión de $1",
'filehist-nothumb'          => 'Sin de miniatura',
'filehist-user'             => 'Usuario',
'filehist-dimensions'       => 'Dimensions',
'filehist-filesize'         => "Grandaria d'o fichero",
'filehist-comment'          => 'Comentario',
'filehist-missing'          => 'No se troba o fichero',
'imagelinks'                => 'Vinclos ta o fichero',
'linkstoimage'              => "{{PLURAL:$1|A pachina siguient tiene|Contino s'amuestran $1 pachinas que tienen}} vinclos ta iste fichero:",
'linkstoimage-more'         => 'Bi ha más de {{PLURAL:$1|una pachina con vinclos|$1 pachinas con vinclos}} enta iste fichero.

A lista siguient nomás amuestra {{PLURAL:$1|a primer pachina con vinclos|as primeras $1 pachinas con vinclos}} enta iste fichero.
Tamién puetz consultar a [[Special:WhatLinksHere/$2|lista completa]].',
'nolinkstoimage'            => 'Garra pachina tiene un vinclo ta ista imachen.',
'morelinkstoimage'          => 'Amostrar [[Special:WhatLinksHere/$1|más vinclos]] ta iste fichero.',
'redirectstofile'           => '{{PLURAL:$1|O siguient fichero reendreza|Os siguients $1 fichers reendrezan}} enta iste fichero:',
'duplicatesoffile'          => "{{PLURAL:$1|O siguient fichero ye un duplicato|Os siguients $1 fichers son duplicatos}} d'iste fichero ([[Special:FileDuplicateSearch/$2|más detalles]]):",
'sharedupload'              => 'Iste fichero provién de $1 y talment siga emplegato en atros prochectos.',
'sharedupload-desc-there'   => "Iste fichero ye de $1 y puet estar emplegau por atros prochectos.
Por favor mire-se a [$2 pachina de descripción d'o fichero] ta trobar más información.",
'sharedupload-desc-here'    => "Iste fichero ye de $1 y pueden emplegar-lo atros prochectos.
Debaixo s'amuestra a descripción d'a suya [$2 pachina de descripción].",
'filepage-nofile'           => 'No existe garra fichero con ixe nombre.',
'filepage-nofile-link'      => 'No existe garra fichero con ixe nombre, pero puet [$1 puyar-lo].',
'uploadnewversion-linktext' => "Cargar una nueva versión d'iste fichero",
'shared-repo-from'          => 'dende $1',
'shared-repo'               => 'un reposte compartito',

# File reversion
'filerevert'                => 'Revertir $1',
'filerevert-legend'         => 'Revertir fichero',
'filerevert-intro'          => "Ye revertindo '''[[Media:$1|$1]]''' a la [$4 versión de $3, $2].",
'filerevert-comment'        => 'Razón:',
'filerevert-defaultcomment' => "Revertito t'a versión de $1, $2",
'filerevert-submit'         => 'Revertir',
'filerevert-success'        => "S'ha revertito '''[[Media:$1|$1]]''' a la [$4 versión de $3, $2].",
'filerevert-badversion'     => "No bi ha garra versión antiga d'o fichero con ixa calendata y hora.",

# File deletion
'filedelete'                  => 'Borrar $1',
'filedelete-legend'           => 'Borrar fichero',
'filedelete-intro'            => "Ye en momentos de borrar o fichero '''[[Media:$1|$1]]''' chunto con toda a suya historia.",
'filedelete-intro-old'        => "Ye en momentos de borrar a versión de '''[[Media:$1|$1]]''' de [$4 $3, $2].",
'filedelete-comment'          => 'Razón:',
'filedelete-submit'           => 'Borrar',
'filedelete-success'          => "S'ha borrato '''$1'''.",
'filedelete-success-old'      => "S'ha borrato a versión de '''[[Media:$1|$1]]''' de $2 a las $3.",
'filedelete-nofile'           => "'''$1''' no existe.",
'filedelete-nofile-old'       => "No bi ha garra versión alzata de '''$1''' con os atributos especificatos.",
'filedelete-otherreason'      => 'Atras razons:',
'filedelete-reason-otherlist' => 'Atra razón',
'filedelete-reason-dropdown'  => "*Razons comuns ta borrar fichers
** Dreitos d'autor no respetatos
** Archivo duplicato",
'filedelete-edit-reasonlist'  => "Editar as razons d'o borrau",
'filedelete-maintenance'      => 'O borramiento y recuperación de fichers ye desactivau temporalment entre que dura o mantenimiento.',

# MIME search
'mimesearch'         => 'Mirar por tipo MIME',
'mimesearch-summary' => 'Ista pachina premite filtrar fichers seguntes o suyo tipo MIME. Escribir: tipodeconteniu/subtipo, por exemplo <tt>image/jpeg</tt>.',
'mimetype'           => 'Tipo MIME:',
'download'           => 'escargar',

# Unwatched pages
'unwatchedpages' => 'Pachinas no cosiratas',

# List redirects
'listredirects' => 'Lista de reendreceras',

# Unused templates
'unusedtemplates'     => 'Plantillas sin de uso',
'unusedtemplatestext' => "En ista pachina se fa una lista de todas as pachinas en o espacio de nombres {{ns:template}} que no sían incluyitas en atras pachinas. Alcuerde-se de comprebar as pachinas que tiengan vinclos t'as plantillas antis de no borrar-las.",
'unusedtemplateswlh'  => 'atros vinclos',

# Random page
'randompage'         => "Una pachina a l'azar",
'randompage-nopages' => 'No i ha garra pachina en {{PLURAL:$2|o siguient espacio de nombres|os siguients espacios de nombres}}: "$1".',

# Random redirect
'randomredirect'         => 'Ir-ie a una adreza qualsiquiera',
'randomredirect-nopages' => 'No bi ha garra reendrecera en o espacio de nombres "$1".',

# Statistics
'statistics'                   => 'Estatisticas',
'statistics-header-pages'      => 'Estatisticas de pachinas',
'statistics-header-edits'      => "Estatisticas d'edicions",
'statistics-header-views'      => 'Estatisticas de vesitas',
'statistics-header-users'      => "Estatisticas d'usuarios",
'statistics-header-hooks'      => 'Atras estatisticas',
'statistics-articles'          => 'Pachinas de contenito',
'statistics-pages'             => 'Pachinas',
'statistics-pages-desc'        => "Todas as pachinas d'o wiki, incluyendo pachinas de descusión, reendreceras, etz.",
'statistics-files'             => 'Fichers cargatos',
'statistics-edits'             => 'Edicions en pachinas dende que se debantó {{SITENAME}}',
'statistics-edits-average'     => "Meya d'edicions por pachina",
'statistics-views-total'       => 'Total de vesitas',
'statistics-views-peredit'     => 'Vesitas por edición',
'statistics-users'             => '[[Special:ListUsers|Usuarios]] rechistratos',
'statistics-users-active'      => 'Usuarios activos',
'statistics-users-active-desc' => 'Usuarios que han feito qualsiquier acción en {{PLURAL:$1|o zaguer día|os zaguers $1 días}}',
'statistics-mostpopular'       => 'Pachinas más vistas',

'disambiguations'      => 'Pachinas de desambigación',
'disambiguationspage'  => 'Template:Desambigación',
'disambiguations-text' => "As siguients pachinas tienen vinclos ta una '''pachina de desambigación'''.
Ixos vinclos habrían de ir millor t'a pachina especifica apropiada.<br />
Una pachina se considera pachina de desambigación si fa servir una plantilla provenient de  [[MediaWiki:Disambiguationspage]].",

'doubleredirects'            => 'Reendreceras dobles',
'doubleredirectstext'        => "En ista pachina s'amuestran as pachinas que son reendreceras enta atras pachinas reendrezatas.
Cada ringlera contién o vinclo t'a primer y segunda reendreceras, y tamién o destino d'a segunda reendrecera, que ye a ormino a pachina obchectivo \"reyal\" a la que a primer pachina habría d'endrezar.",
'double-redirect-fixed-move' => "S'ha tresladau [[$1]], agora ye una endrecera ta [[$2]]",
'double-redirect-fixer'      => 'Apanyador de reendreceras',

'brokenredirects'        => 'Reendreceras crebatas',
'brokenredirectstext'    => 'As siguients endreceras levan enta pachinas inexistents.',
'brokenredirects-edit'   => 'editar',
'brokenredirects-delete' => 'borrar',

'withoutinterwiki'         => "Pachinas sin d'interwikis",
'withoutinterwiki-summary' => 'As pachinas siguients no tienen vinclos ta versions en atras luengas:',
'withoutinterwiki-legend'  => 'Prefixo',
'withoutinterwiki-submit'  => 'Amostrar',

'fewestrevisions' => 'Articlos con menos edicions',

# Miscellaneous special pages
'nbytes'                  => '$1 {{PLURAL:$1|byte|bytes}}',
'ncategories'             => '$1 {{PLURAL:$1|categoría|categorías}}',
'nlinks'                  => '$1 {{PLURAL:$1|vinclo|vinclos}}',
'nmembers'                => '$1 {{PLURAL:$1|miembro|miembros}}',
'nrevisions'              => '$1 {{PLURAL:$1|versión|versions}}',
'nviews'                  => '$1 {{PLURAL:$1|vesita|vesitas}}',
'specialpage-empty'       => 'Ista pachina ye bueda.',
'lonelypages'             => 'Pachinas popiellas',
'lonelypagestext'         => "As siguients pachinas no tienen vinclos dende atras pachinas ni s'incluyen en atras pachinas de {{SITENAME}}.",
'uncategorizedpages'      => 'Pachinas sin categorizar',
'uncategorizedcategories' => 'Categorías sin categorizar',
'uncategorizedimages'     => 'Fichers sin categorizar',
'uncategorizedtemplates'  => 'Plantillas sin categorizar',
'unusedcategories'        => 'Categorías sin emplegar',
'unusedimages'            => 'Imachens sin uso',
'popularpages'            => 'Pachinas populars',
'wantedcategories'        => 'Categorías requiestas',
'wantedpages'             => 'Pachinas requiestas',
'wantedpages-badtitle'    => 'Títol no conforme en o conchunto de resultaus: $1',
'wantedfiles'             => 'Fichers requiestos',
'wantedtemplates'         => 'Plantillas requiestas',
'mostlinked'              => 'Pachinas más enlazadas',
'mostlinkedcategories'    => 'Categorías más enlazadas',
'mostlinkedtemplates'     => 'Plantillas más vinculatas',
'mostcategories'          => 'Pachinas con más categorías',
'mostimages'              => 'Fichers más emplegatos',
'mostrevisions'           => 'Pachinas con más edicions',
'prefixindex'             => 'Todas as pachinas con prefixo',
'shortpages'              => 'Pachinas más curtas',
'longpages'               => 'Pachinas más largas',
'deadendpages'            => 'Pachinas sin salida',
'deadendpagestext'        => 'As siguients pachinas no tienen vinclos ta garra atra pachina de {{SITENAME}}.',
'protectedpages'          => 'Pachinas protechitas',
'protectedpages-indef'    => 'Nomás proteccions indefinitas',
'protectedpages-cascade'  => 'Nomás proteccions en cascada',
'protectedpagestext'      => 'As siguients pachinas son protechitas contra edicions u treslaus',
'protectedpagesempty'     => 'En iste inte no bi ha garra pachina protechita con ixos parametros.',
'protectedtitles'         => 'Títols protechitos',
'protectedtitlestext'     => 'Os siguients títols son protechitos ta privar a suya creyación',
'protectedtitlesempty'    => 'En iste inte no bi ha garra títol protechito con ixos parametros.',
'listusers'               => "Lista d'usuarios",
'listusers-editsonly'     => 'Amostrar nomás usuarios con edicions',
'listusers-creationsort'  => 'Ordenato por calendata de creyación',
'usereditcount'           => '$1 {{PLURAL:$1|edición|edicions}}',
'usercreated'             => 'Creyato o $1 a las $2',
'newpages'                => 'Pachinas nuevas',
'newpages-username'       => "Nombre d'usuario",
'ancientpages'            => 'Pachinas más viellas',
'move'                    => 'Tresladar',
'movethispage'            => 'Tresladar ista pachina',
'unusedimagestext'        => 'Os siguient fichers existen pero no amaneixen incorporaus en garra pachina.
Por favor, pare cuenta que atros puestos web pueden tener vinclos ta fichers con una URL dreita y, por ixo, podrían amaneixer en ista lista encara que sí se faigan servir activament.',
'unusedcategoriestext'    => 'As siguients categoría son creyatas, pero no bi ha garra articlo u categoría que las faiga servir.',
'notargettitle'           => 'No bi ha garra pachina de destino',
'notargettext'            => 'No ha especificato en que pachina quiere aplicar ista función.',
'nopagetitle'             => 'No existe ixa pachina',
'nopagetext'              => 'A pachina que ha especificato no existe.',
'pager-newer-n'           => '{{PLURAL:$1|1 más recient|$1 más recients}}',
'pager-older-n'           => '{{PLURAL:$1|1 más antiga|$1 más antigas}}',
'suppress'                => 'Supervisión',

# Book sources
'booksources'               => 'Fuents de libros',
'booksources-search-legend' => 'Mirar fuents de libros',
'booksources-go'            => 'Ir-ie',
'booksources-text'          => 'Contino ye una lista de vinclos ta atros puestos an que venden libros nuevos y usatos, talment bi haiga más información sobre os libros que ye mirando.',
'booksources-invalid-isbn'  => "O numero d'ISBN dato pareix que no ye conforme; comprebe si no bi ha garra error en copiar d'a fuent orichinal.",

# Special:Log
'specialloguserlabel'  => 'Usuario:',
'speciallogtitlelabel' => 'Títol:',
'log'                  => 'Rechistros',
'all-logs-page'        => 'Totz os rechistros publicos',
'alllogstext'          => "Presentación conchunta de totz os rechistros de  {{SITENAME}}.
Puede reducir o listau trigando un tipo de rechistro, o nombre de l'usuario (sensible a mayusclas), u a pachina afectata (tamién sensible a mayusclas).",
'logempty'             => 'No bi ha garra elemento en o rechistro con ixas carauteristicas.',
'log-title-wildcard'   => 'Mirar títols que prencipien con iste texto',

# Special:AllPages
'allpages'          => 'Todas as pachinas',
'alphaindexline'    => '$1 a $2',
'nextpage'          => 'Siguient pachina ($1)',
'prevpage'          => 'Pachina anterior ($1)',
'allpagesfrom'      => 'Amostrar as pachinas que prencipien por:',
'allpagesto'        => 'Amostrar as pachinas que rematen en:',
'allarticles'       => 'Totz os articlos',
'allinnamespace'    => 'Todas as pachinas (espacio $1)',
'allnotinnamespace' => "Todas as pachinas (fueras d'o espacio de nombres $1)",
'allpagesprev'      => 'Anterior',
'allpagesnext'      => 'Siguient',
'allpagessubmit'    => 'Amostrar',
'allpagesprefix'    => 'Amostrar pachinas con o prefixo:',
'allpagesbadtitle'  => 'O títol yera incorrecto u teneba un prefixo de vinclo inter-luenga u inter-wiki. Puede contener uno u más carácters que no se pueden emplegar en títols.',
'allpages-bad-ns'   => '{{SITENAME}} no tiene o espacio de nombres "$1".',

# Special:Categories
'categories'                    => 'Categorías',
'categoriespagetext'            => "{{PLURAL:$1|A siguient categoría contién|As siguients categorías contienen}} pachinas u fichers multimedia.
No s'amuestran aquí as [[Special:UnusedCategories|categorías no emplegatas]].
Se veigan tamién as [[Special:WantedCategories|categorías requiestas]].",
'categoriesfrom'                => 'Amostrar as categoría que prencipien por:',
'special-categories-sort-count' => 'ordenar por recuento',
'special-categories-sort-abc'   => 'ordenar alfabeticament',

# Special:DeletedContributions
'deletedcontributions'             => "Contrebucions d'usuario borratas",
'deletedcontributions-title'       => "Contrebucions d'usuario borradas",
'sp-deletedcontributions-contribs' => 'contrebucions',

# Special:LinkSearch
'linksearch'       => 'vinclos externos',
'linksearch-pat'   => 'Mirar patrón:',
'linksearch-ns'    => 'Espacio de nombres:',
'linksearch-ok'    => 'Mirar',
'linksearch-text'  => 'Pueden usar-se carácters comodín como "*.wikipedia.org".<br />
Protocolos suportados: <tt>$1</tt>',
'linksearch-line'  => '$1 tiene un vinclo dende $2',
'linksearch-error' => "Os carácters comodín nomás pueden apareixer en o prencipio d'o nombre d'o sitio.",

# Special:ListUsers
'listusersfrom'      => 'Amostrar usuarios que o nombre suyo prencipie por:',
'listusers-submit'   => 'Amostrar',
'listusers-noresult' => "No s'ha trobato ixe usuario.",
'listusers-blocked'  => '({{GENDER:$1|bloqueyato|bloqueyata}})',

# Special:ActiveUsers
'activeusers'            => "Lista d'usuarios activos",
'activeusers-intro'      => "Ista ye una lista d'usuarios que han teniu bella actividat en os zaguers $1 {{PLURAL:$1|diya|diyas}}.",
'activeusers-count'      => '$1 {{PLURAL:$1|edición|edicions}} en os zaguers {{PLURAL:$3|diya|$3 diyas}}',
'activeusers-from'       => "Amostrar nombres d'usuario que prencipien por:",
'activeusers-hidebots'   => 'Amagar robots',
'activeusers-hidesysops' => 'Amagar administradors',
'activeusers-noresult'   => "No s'han trobato usuarios.",

# Special:Log/newusers
'newuserlogpage'              => 'Rechistro de nuevos usuarios',
'newuserlogpagetext'          => "Isto ye un rechistro de creyación d'usuarios.",
'newuserlog-byemail'          => 'Contrasenya ninviata por correu electronico',
'newuserlog-create-entry'     => 'Nuevo usuario',
'newuserlog-create2-entry'    => "s'ha creyato a nueva cuenta $1",
'newuserlog-autocreate-entry' => 'Cuenta creyata automaticament',

# Special:ListGroupRights
'listgrouprights'                      => "Dreitos d'a colla d'usuarios",
'listgrouprights-summary'              => "Contino trobará a lista de collas d'usuario definitas en iste wiki, con os suyos dreitos d'acceso asociatos. Tamién puet trobar aquí [[{{MediaWiki:Listgrouprights-helppage}}|información adicional]] sobre os dreitos individuals.",
'listgrouprights-key'                  => '* <span class="listgrouprights-granted">Dreito atorgau</span>
* <span class="listgrouprights-revoked">Dreito revocau</span>',
'listgrouprights-group'                => 'Colla',
'listgrouprights-rights'               => 'Dreitos',
'listgrouprights-helppage'             => "Help:Dreitos d'a colla",
'listgrouprights-members'              => '(lista de miembros)',
'listgrouprights-addgroup'             => 'Puet adhibir {{PLURAL:$2|colla|collas}}: $1',
'listgrouprights-removegroup'          => 'Puede borrar {{PLURAL:$2|colla|collas}}: $1',
'listgrouprights-addgroup-all'         => 'Puede adhibir todas as collas',
'listgrouprights-removegroup-all'      => 'Puede borrar todas as collas',
'listgrouprights-addgroup-self'        => 'Adhibir {{PLURAL:$2|colla|collas}} a la suya propia cuenta: $1',
'listgrouprights-removegroup-self'     => "Eliminar {{PLURAL:$2|colla|collas}} d'a suya propia cuenta: $1",
'listgrouprights-addgroup-self-all'    => 'Adhibir-se a todas as collas',
'listgrouprights-removegroup-self-all' => 'Salir de todas as collas',

# E-mail user
'mailnologin'          => "No ninviar l'adreza",
'mailnologintext'      => "Ha d'haber [[Special:UserLogin|encetato una sesión]] y tener una adreza conforme de correu-e en as suyas [[Special:Preferences|preferencias]] ta ninviar un correu electronico ta atros usuarios.",
'emailuser'            => 'Ninviar un correu electronico ta iste usuario',
'emailpage'            => "Ninviar correu ta l'usuario",
'emailpagetext'        => 'Puede fer servir o formulario que bi ye contino ta ninviar un correu electronico a iste usuario.
L\'adreza de correu-e que endicó en as suyas [[Special:Preferences|preferencias d\'usuario]] amaneixerá en o campo "Remitent" ta que o destinatario pueda responder-le.',
'usermailererror'      => "L'obchecto de correu retornó una error:",
'defemailsubject'      => 'Correu de {{SITENAME}}',
'usermaildisabled'     => "S'ha desactivau o ninvío de correus electronicos a os usuarios",
'usermaildisabledtext' => 'En ista wiki no puet ninviar un correu-e a atros usuarios',
'noemailtitle'         => 'No bi ha garra adreza de correu electronico',
'noemailtext'          => 'Iste usuario no ha especificato una adreza conforme de correu electronico.',
'nowikiemailtitle'     => 'no se premiten os correus electronicos',
'nowikiemailtext'      => "Iste usuario ha esleyiu de no recibir correus electronicos d'atros usuarios.",
'email-legend'         => 'Ninviar un correu electronico ta atro usuario de {{SITENAME}}',
'emailfrom'            => 'De:',
'emailto'              => 'Ta:',
'emailsubject'         => 'Afer:',
'emailmessage'         => 'Mensache:',
'emailsend'            => 'Ninviar',
'emailccme'            => "Ninviar-me una copia d'o mío mensache.",
'emailccsubject'       => "Copia d'o suyo mensache ta $1: $2",
'emailsent'            => 'Mensache de correu ninviato',
'emailsenttext'        => "S'ha ninviato o suyo correu.",
'emailuserfooter'      => 'Iste correu-e s\'ha ninviato por $1 ta $2 fendo servir a función "Email user" de {{SITENAME}}.',

# User Messenger
'usermessage-summary' => 'Deixando un mensache de sistema.',
'usermessage-editor'  => "Mensachero d'o sistema",

# Watchlist
'watchlist'            => 'Lista de seguimiento',
'mywatchlist'          => 'Lista de seguimiento',
'watchlistfor2'        => 'De $1 $2',
'nowatchlist'          => 'No tien garra pachina en a lista de seguimiento.',
'watchlistanontext'    => "Ha de $1 ta veyer u editar as dentradas d'a suya lista de seguimiento.",
'watchnologin'         => 'No ha encetato a sesión',
'watchnologintext'     => "Ha d'estar [[Special:UserLogin|identificato]] ta poder cambiar a suya lista de seguimiento.",
'addedwatch'           => 'Adhibiu a la suya lista de seguimiento',
'addedwatchtext'       => "A pachina «[[:\$1]]» s'ha adhibito t'a suya [[Special:Watchlist|lista de seguimiento]]. Os cambios esdevenideros en ista pachina y en a suya pachina de descusión asociata s'indicarán astí, y a pachina amanixerá '''en negreta''' en a [[Special:RecentChanges|lista de cambios recients]] ta que se veiga millor. <p>Si nunca quiere borrar a pachina d'a suya lista de seguimiento, punche \"Deixar de cosirar\" en o menú.",
'removedwatch'         => "Borrata d'a lista de seguimiento",
'removedwatchtext'     => 'A pachina "[[:$1]]" s\'ha sacau d\'a suya [[Special:Watchlist|lista de seguimiento]].',
'watch'                => 'Cosirar',
'watchthispage'        => 'Cosirar ista pachina',
'unwatch'              => 'Deixar de cosirar',
'unwatchthispage'      => 'Deixar de cosirar',
'notanarticle'         => 'No ye una pachina de conteniu',
'notvisiblerev'        => "S'ha borrato a revisión",
'watchnochange'        => "Dengún d'os articlos d'a suya lista de seguimiento no s'ha editato en o periodo de tiempo amostrato.",
'watchlist-details'    => '{{PLURAL:$1|$1 pachina|$1 pachinas}} en a suya lista de seguimiento, sin contar-ie as pachinas de descusión.',
'wlheader-enotif'      => '* A notificación por correu electronico ye activata',
'wlheader-showupdated' => "* Las pachinas cambiadas dende a suya zaguer vesita s'amuestran en '''negreta'''",
'watchmethod-recent'   => 'Mirando pachinas cosiratas en os zaguers cambeos',
'watchmethod-list'     => 'mirando edicions recients en as pachinas cosiratas',
'watchlistcontains'    => 'A suya lista de seguimiento tiene $1 {{PLURAL:$1|pachina|pachinas}}.',
'iteminvalidname'      => "Bi ha un problema con l'articlo '$1', o nombre no ye conforme...",
'wlnote'               => "Contino se i {{PLURAL:$1|amuestra o solo cambeo|amuestran os zaguers '''$1''' cambeos}} en {{PLURAL:$2|a zaguer hora|as zagueras '''$2''' horas}}.",
'wlshowlast'           => 'Amostrar as zagueras $1 horas, $2 días u $3',
'watchlist-options'    => "Opcions d'a lista de seguimiento",

# Displayed when you click the "watch" button and it is in the process of watching
'watching'   => 'Cosirando...',
'unwatching' => 'Deixar de cosirar...',

'enotif_mailer'                => 'Sistema de notificación por correu de {{SITENAME}}',
'enotif_reset'                 => 'Marcar todas as pachinas como vesitatas',
'enotif_newpagetext'           => 'Ista ye una nueva pachina.',
'enotif_impersonal_salutation' => 'usuario de {{SITENAME}}',
'changed'                      => 'editata',
'created'                      => 'creyata',
'enotif_subject'               => 'A pachina $PAGETITLE de {{SITENAME}} ha estato $CHANGEDORCREATED por $PAGEEDITOR',
'enotif_lastvisited'           => 'Vaiga ta $1 ta veyer totz os cambeos dende a suya zaguer vesita.',
'enotif_lastdiff'              => 'Vaiga ta $1 ta veyer iste cambeo.',
'enotif_anon_editor'           => 'usuario anonimo $1',
'enotif_body'                  => 'Quiesto/a $WATCHINGUSERNAME,

A pachina $PAGETITLE de {{SITENAME}} ha estato $CHANGEDORCREATED por l\'usuario $PAGEEDITOR o $PAGEEDITDATE. Puede veyer a versión actual en $PAGETITLE_URL.

$NEWPAGE

Resumen d\'edición: $PAGESUMMARY $PAGEMINOREDIT

Ta contactar con l\'editor:
correu: $PAGEEDITOR_EMAIL
wiki: $PAGEEDITOR_WIKI

Ta recullir nuevas notificacions de cambios d\'ista pachina habrá de vesitar-la nuevament.
Tamién puede cambiar, en a su lista de seguimiento, as opcions de notificación d\'as pachinas que ye cosirando.

Atentament,
O sistema de notificación de {{SITENAME}}.

--
Ta cambiar as opcions d\'a suya lista de seguimiento, punche:
{{fullurl:{{#special:Watchlist}}/edit}}

Ta borrar ista pachina d\'a suya lista de seguimiento, punche:
$UNWATCHURL

Ta obtenir más información y aduya:
{{fullurl:{{MediaWiki:Helppage}}}}',

# Delete
'deletepage'             => 'Borrar ista pachina',
'confirm'                => 'Confirmar',
'excontent'              => "O conteniu yera: '$1'",
'excontentauthor'        => "O conteniu yera: '$1' (y o suyo unico autor yera [[Special:Contributions/$2|$2]])",
'exbeforeblank'          => "O conteniu antis de blanquiar yera: '$1'",
'exblank'                => 'a pachina yera bueda',
'delete-confirm'         => 'Borrar "$1"',
'delete-legend'          => 'Borrar',
'historywarning'         => "'''Pare cuenta!:''' A pachina que ye en momentos de borrar tien un historial de $1 {{PLURAL:$1|versión|versions}}:",
'confirmdeletetext'      => "Ye en momentos de borrar d'a base de datos una pachina con tot o suyo historial.
Por favor, confirme que reyalment ye mirando de fer ixo, que entiende as conseqüencias, y que lo fa d'alcuerdo con as [[{{MediaWiki:Policy-url}}|politicas]] d'o wiki.",
'actioncomplete'         => 'Acción rematada',
'actionfailed'           => "L'acción ha feito fallita",
'deletedtext'            => 'S\'ha borrau "<nowiki>$1</nowiki>".
Se veiga en $2 un rechistro d\'os borraus recients.',
'deletedarticle'         => 'ha borrato "[[$1]]"',
'suppressedarticle'      => 's\'ha supreso "[[$1]]"',
'dellogpage'             => 'Rechistro de borraus',
'dellogpagetext'         => "Contino se i amuestra una lista d'os borraus más recients.",
'deletionlog'            => 'rechistro de borraus',
'reverted'               => "S'ha tornato ta una versión anterior",
'deletecomment'          => 'Razón:',
'deleteotherreason'      => 'Otras/Más razons:',
'deletereasonotherlist'  => 'Atra razón',
'deletereason-dropdown'  => "*Razons comuns de borrau
** A requesta d'o mesmo autor
** Trencar de copyright
** Vandalismo",
'delete-edit-reasonlist' => "Editar as razons d'o borrau",
'delete-toobig'          => "Ista pachina tiene un historial d'edicions prou largo, con mas de $1 {{PLURAL:$1|versión|versions}}. S'ha restrinchito o borrau d'ista mena de pachinas ta aprevenir d'a corrompición accidental de {{SITENAME}}.",
'delete-warning-toobig'  => "Ista pachina tiene un historial d'edición prou largo, con más de $1 {{PLURAL:$1|versión|versions}}. Si la borra podría corromper as operacions d'a base de datos de {{SITENAME}}; contine con cuenta.",

# Rollback
'rollback'          => 'Revertir edicions',
'rollback_short'    => 'Revertir',
'rollbacklink'      => 'revertir',
'rollbackfailed'    => "No s'ha puesto revertir",
'cantrollback'      => "No se pueden revertir as edicions; o zaguer colaborador ye o solo autor d'iste articlo.",
'alreadyrolled'     => "No se puet desfer a zaguer edición de [[:$1]] feita por [[User:$2|$2]] ([[User talk:$2|descusión]]{{int:pipe-separator}}[[Special:Contributions/$2|{{int:contribslink}}]]); belatro usuario ya ha editato u desfeito edicions en ixa pachina.

A zaguer edición d'a pachina la fació [[User:$3|$3]] ([[User talk:$3|descusión]]{{int:pipe-separator}}[[Special:Contributions/$3|{{int:contribslink}}]]).",
'editcomment'       => "O resumen d'a edición ye: \"''\$1''\".",
'revertpage'        => "S'han revertito as edicions de [[Special:Contributions/$2|$2]] ([[User talk:$2|Descusión]]); tornando t'a zaguera versión editada por [[User:$1|$1]]",
'revertpage-nouser' => "S'han revertito as edicions feitas por (nombre d'usuario eliminato) a la zaguera versión feita por [[User:$1|$1]]",
'rollback-success'  => "Revertidas as edicions de $1; s'ha retornato t'a zaguer versión de $2.",

# Edit tokens
'sessionfailure-title' => 'Error de sesión',
'sessionfailure'       => 'Pareix que bi ha un problema con a suya sesión;
s\'ha anulato ista acción como mida de precura contra seqüestros de sesión.
Por favor, prete "Entazaga", recargue a pachina d\'a que venió, y torne a prebar alavez.',

# Protect
'protectlogpage'              => 'Rechistro de proteccions de pachinas',
'protectlogtext'              => 'Contino se i amuestra una lista de proteccions y desproteccions de pachinas. Se veiga [[Special:ProtectedPages|lista de pachinas protechitas]] ta más información.',
'protectedarticle'            => "s'ha protechito [[$1]]",
'modifiedarticleprotection'   => 's\'ha cambiato o livel de protección de "[[$1]]"',
'unprotectedarticle'          => "s'ha esprotechito [[$1]]",
'movedarticleprotection'      => 'camiatos os parametros de protección de "[[$2]]" a "[[$1]]"',
'protect-title'               => 'Protechendo "$1"',
'prot_1movedto2'              => '[[$1]] tresladada a [[$2]]',
'protect-legend'              => 'Confirmar protección',
'protectcomment'              => 'Razón:',
'protectexpiry'               => 'Calendata de circumducción:',
'protect_expiry_invalid'      => 'O tiempo de circumducción ye incorrecto.',
'protect_expiry_old'          => 'O tiempo de circumducción ye una calendata ya pasata.',
'protect-unchain-permissions' => 'Desbloqueyar opcions de protección avanzatas',
'protect-text'                => "Puetz veyer y cambiar o livel e protección d'a pachina '''<nowiki>$1</nowiki>'''.",
'protect-locked-blocked'      => "No puede cambiar os livels de protección mientres ye bloqueyato. Contino se i amuestran as opcions actuals d'a pachina '''$1''':",
'protect-locked-dblock'       => "Os livels de protección no se pueden cambiar por un bloqueyo activo d'a base de datos.
Contino se i amuestran as opcions actuals d'a pachina '''$1''':",
'protect-locked-access'       => "A suya cuenta no tiene premiso ta cambiar os livels de protección d'as pachinas. Aquí bi son as propiedatz actuals d'a pachina '''$1''':",
'protect-cascadeon'           => "Ista pachina ye actualment protechita por estar incluyita en {{PLURAL:$1|a siguient pachina|as siguients pachinas}}, que tienen activata a opción de protección en cascada. Puede cambiar o livel de protección d'ista pachina, pero no afectará a la protección en cascada.",
'protect-default'             => 'Premitir a totz os usuarios',
'protect-fallback'            => 'Amenista o premiso "$1"',
'protect-level-autoconfirmed' => 'Bloqueyar os usuarios nuevos y no rechistratos',
'protect-level-sysop'         => 'Sólo almenistradors',
'protect-summary-cascade'     => 'en cascada',
'protect-expiring'            => 'caduca o $1 (UTC)',
'protect-expiry-indefinite'   => 'indefinito',
'protect-cascade'             => 'Protección en cascada - protecher totas as pachinas incluyidas en ista.',
'protect-cantedit'            => "No puet cambiar os livels de protección d'ista pachina, porque no tiene premiso ta editar-la.",
'protect-othertime'           => 'atro periodo:',
'protect-othertime-op'        => 'atra (especificar)',
'protect-existing-expiry'     => 'Calendata de circumducción actual: $2 a las $3',
'protect-otherreason'         => 'Atra razón:',
'protect-otherreason-op'      => 'Atra razón',
'protect-dropdown'            => "*Razons de protección excesivo
**Vandalismo excesivo
**Spam excesivo
**Guerra d'edicions
**Pachina muit vesitada",
'protect-edit-reasonlist'     => 'Editar as razons ta protecher',
'protect-expiry-options'      => '1 hora:1 hour,1 día:1 day,1 semana:1 week,2 semanas:2 weeks,1 mes:1 month,3 meses:3 months,6 meses:6 months,1 anyo:1 year,ta cutio:infinite',
'restriction-type'            => 'Premiso:',
'restriction-level'           => 'Livel de restricción:',
'minimum-size'                => 'Grandaria menima',
'maximum-size'                => 'Grandaria masima:',
'pagesize'                    => '(bytes)',

# Restrictions (nouns)
'restriction-edit'   => 'Edición',
'restriction-move'   => 'Tresladar',
'restriction-create' => 'Creyar',
'restriction-upload' => 'Carga',

# Restriction levels
'restriction-level-sysop'         => 'protechita de tot',
'restriction-level-autoconfirmed' => 'semiprotechita',
'restriction-level-all'           => 'qualsiquier livel',

# Undelete
'undelete'                     => 'Veyer pachinas borratas',
'undeletepage'                 => 'Veyer y restaurar pachinas borratas',
'undeletepagetitle'            => "'''Contino s'amuestran as versions borratas de [[:$1]]'''.",
'viewdeletedpage'              => 'Veyer pachinas borratas',
'undeletepagetext'             => '{{PLURAL:$1|A pachina siguent ye estada borrata pera encara ye|As siguients $1 pachinas son estadas borratas pero encara son}} en o fichero y {{PLURAL:$1|podría restaurar-se|podrían restaurar-sen}}. O fichero se borra periodicament.',
'undelete-fieldset-title'      => 'Restaurar versions',
'undeleteextrahelp'            => "Ta restaurar tot o historial de versions d'una pachina, deixe todas as caixetas sin sinyalar y prete '''''Restaurar!'''''. Ta no restaurar que bell unas d'as versions, sinyale as caixetas correspondients a las versions que quiere restaurar y punche dimpués en '''''Restaurar!'''''. Punchando en '''''Prencipiar''''' se borrará o comentario y se tirarán os sinyals d'as caixetas.",
'undeleterevisions'            => '$1 {{PLURAL:$1|versión|versions}} archivatas',
'undeletehistory'              => "Si restableix a pachina, se restaurarán  todas as versions en o suyo historial.
Si s'ha creyato una nueva pachina con o mesmo nombre dende que se borró a orichinal, as versions restauradas amaneixerán antes en o historial.",
'undeleterevdel'               => "O borrau no se desferá si resultalse en o borrau parcial d'a zaguera versión d'a pachina u o fichero.  En ixos casos, ha de deseleccionar u fer veyer as versions borratas más recients.",
'undeletehistorynoadmin'       => "Esta pachina ye borrata. A razón d'o suyo borrau s'amuestra más t'abaixo en o resumen, asinas como os detalles d'os usuarios que eban editato a pachina antes d'o borrau. O texto completo d'istas edicions borratas ye disponible nomás ta os almenistradors.",
'undelete-revision'            => 'Versión borrata de $1 (editada por $3, o $4 a las $5):',
'undeleterevision-missing'     => "Versión no conforme u no trobata. Regular que o vinclo sía incorrecto u que ixa versión s'haiga restaurato u borrato d'o fichero.",
'undelete-nodiff'              => "No s'ha trobato garra versión anterior.",
'undeletebtn'                  => 'Restaurar!',
'undeletelink'                 => 'amostrar/restaurar',
'undeleteviewlink'             => 'veyer',
'undeletereset'                => 'Prencipiar',
'undeleteinvert'               => 'Contornar selección',
'undeletecomment'              => 'Razón:',
'undeletedarticle'             => 'ha restaurato "[[$1]]"',
'undeletedrevisions'           => '{{PLURAL:$1|Una edición restaurata|$1 edicions restauratas}}',
'undeletedrevisions-files'     => '$1 {{PLURAL:$1|revisón|revisions}} y $2 {{PLURAL:$2|fichero|fichers}} restauratos',
'undeletedfiles'               => '$1 {{PLURAL:$1|fichero restaurato|fichers restauratos}}',
'cannotundelete'               => "No s'ha puesto desfer o borrau; belatro usuario puede haber desfeito antis o borrau.",
'undeletedpage'                => "'''S'ha restaurato $1'''

Consulte o [[Special:Log/delete|rechistro de borraus]] ta veyer una lista d'os zaguers borraus y restauracions.",
'undelete-header'              => 'En o [[Special:Log/delete|rechistro de borraus]] se listan as pachina borratas fa poco tiempo.',
'undelete-search-box'          => 'Mirar en as pachinas borratas',
'undelete-search-prefix'       => 'Amostrar as pachinas que prencipien por:',
'undelete-search-submit'       => 'Mirar',
'undelete-no-results'          => "No s'han trobato pachinas borratas con ixos criterios.",
'undelete-filename-mismatch'   => 'No se pueden restaurar a revisión de fichero con calendata $1: o nombre de fichero no consona',
'undelete-bad-store-key'       => "No se puede restaurar a versión d'o fichero con calendata $1: o fichero ya no se i trobaba antis d'o borrau.",
'undelete-cleanup-error'       => 'Bi habió una error mientres se borraba o fichero "$1".',
'undelete-missing-filearchive' => "No ye posible restaurar o fichero con ID $1 porque no bi ye en a base de datos. Puede que ya s'aiga restaurato.",
'undelete-error-short'         => 'Error mientres se restauraba o fichero: $1',
'undelete-error-long'          => "S'han trobato errors mientres se borraban os fichers:

$1",
'undelete-show-file-confirm'   => 'Seguro que quiere veyer una versión borrata d\'o fichero "<nowiki>$1</nowiki>" d\'o $2 a las $3?',
'undelete-show-file-submit'    => 'Sí',

# Namespace form on various pages
'namespace'      => 'Espacio de nombres:',
'invert'         => 'Contornar selección',
'blanknamespace' => '(Prencipal)',

# Contributions
'contributions'       => "Contrebucions de l'usuario",
'contributions-title' => "Contribucions de l'usuario $1",
'mycontris'           => 'Contrebucions',
'contribsub2'         => 'De $1 ($2)',
'nocontribs'          => "No s'han trobato cambeos que concordasen con ixos criterios",
'uctop'               => '(zaguer cambeo)',
'month'               => 'Dende o mes (y anteriors):',
'year'                => "Dende l'anyo (y anteriors):",

'sp-contributions-newbies'             => "Amostrar nomás as contrebucions d'os usuarios nuevos",
'sp-contributions-newbies-sub'         => 'Por nuevos usuarios',
'sp-contributions-newbies-title'       => "Contrebucions d'os nuevos usuarios",
'sp-contributions-blocklog'            => 'Rechistro de bloqueyos',
'sp-contributions-deleted'             => "contribucions d'usuario borradas",
'sp-contributions-logs'                => 'rechistros',
'sp-contributions-talk'                => 'descusión',
'sp-contributions-userrights'          => "administración de dreitos d'usuario",
'sp-contributions-blocked-notice'      => "Iste usuario ye bloqueyato en istos momentos. A zaguer dentrada d'o rechistro de bloqueyos se presienta debaixo ta más información:",
'sp-contributions-blocked-notice-anon' => "Ista adreza IP se troba acutalment bloqueyada.
Ta más información, s'amuestra contino a zaguera dentrada d'o rechistro de bloqueyos.",
'sp-contributions-search'              => 'Mirar contribucions',
'sp-contributions-username'            => "Adreza IP u nombre d'usuario:",
'sp-contributions-toponly'             => 'Mostrar nomás as zagueras versions',
'sp-contributions-submit'              => 'Mirar',

# What links here
'whatlinkshere'            => 'Pachinas que enlazan con ista',
'whatlinkshere-title'      => 'Pachinas que tienen vinclos ta $1',
'whatlinkshere-page'       => 'Pachina:',
'linkshere'                => "As siguients pachinas tienen vinclos enta '''[[:$1]]''':",
'nolinkshere'              => "Garra pachina tiene vinclos ta '''[[:$1]]'''.",
'nolinkshere-ns'           => "Garra pachina d'o espacio de nombres trigato tiene vinclos ta '''[[:$1]]'''.",
'isredirect'               => 'pachina reendrezata',
'istemplate'               => 'incluyida',
'isimage'                  => 'vinclo ta imachen',
'whatlinkshere-prev'       => '{{PLURAL:$1|anterior|anteriors $1}}',
'whatlinkshere-next'       => '{{PLURAL:$1|siguient|siguients $1}}',
'whatlinkshere-links'      => '← vinclos',
'whatlinkshere-hideredirs' => '$1 endreceras',
'whatlinkshere-hidetrans'  => '$1 transclusions',
'whatlinkshere-hidelinks'  => '$1 vinclos',
'whatlinkshere-hideimages' => '$1 vinclos ta imachens',
'whatlinkshere-filters'    => 'Filtros',

# Block/unblock
'blockip'                         => 'Bloqueyar usuario',
'blockip-title'                   => 'Bloqueyar usuario',
'blockip-legend'                  => 'Bloqueyar usuario',
'blockiptext'                     => "Replene o siguient formulario ta bloqueyar l'acceso
d'escritura dende una cuenta d'usuario u una adreza IP especifica.
Isto habría de fer-se nomás ta privar vandalismos, y d'alcuerdo con
as [[{{MediaWiki:Policy-url}}|politicas]].
Escriba a razón especifica ta o bloqueyo (por exemplo, quaternando
as pachinas que s'han vandalizato).",
'ipaddress'                       => 'Adreza IP',
'ipadressorusername'              => "Adreza IP u nombre d'usuario",
'ipbexpiry'                       => 'Circumducción:',
'ipbreason'                       => 'Razón:',
'ipbreasonotherlist'              => 'Atra razón',
'ipbreason-dropdown'              => "*Razons comuns de bloqueyo
** Meter información falsa
** Borrar conteniu d'as pachinas
** Fer publicidat ficando vinclos con atras pachinas web
** Meter sinconisions u vasuera en as pachinas
** Portar-se de traza intimidatoria u violenta / atosegar
** Abusar de multiples cuentas
** Nombre d'usuario inacceptable",
'ipbanononly'                     => 'Bloqueyar nomás os usuarios anonimos',
'ipbcreateaccount'                => "Aprevenir a creyación de cuentas d'usuario.",
'ipbemailban'                     => 'Privar que os usuarios ninvíen correus electronicos',
'ipbenableautoblock'              => "bloqueyar automaticament l'adreza IP emplegata por iste usuario, y qualsiquier IP posterior dende a que prebe d'editar",
'ipbsubmit'                       => 'bloqueyar a iste usuario',
'ipbother'                        => 'Especificar atro periodo',
'ipboptions'                      => '2 horas:2 hours,1 día:1 day,3 días:3 days,1 semana:1 week,2 semanas:2 weeks,1 mes:1 month,3 meses:3 months,6 meses:6 months,1 anyo:1 year,ta cutio:infinite',
'ipbotheroption'                  => 'un atra',
'ipbotherreason'                  => 'Razons diferens u adicionals',
'ipbhidename'                     => "Amagar o nombre d'usuario en edicions y listas",
'ipbwatchuser'                    => "Cosirar as pachinas d'usuario y de descusión d'iste usuario",
'ipballowusertalk'                => 'Premitir que iste usuario edite a suya pachina de descusión en o tiempo que ye bloqueyato',
'ipb-change-block'                => "Rebloqueyear a l'usuario con istas condicions",
'badipaddress'                    => "L'adreza IP no ye conforme.",
'blockipsuccesssub'               => "O bloqueyo s'ha feito correctament",
'blockipsuccesstext'              => "L'adreza IP [[Special:Contributions/$1|$1]] ye bloqueyata. <br />Ir t'a [[Special:IPBlockList|lista d'adrezas IP bloqueyatas]] ta veyer os bloqueyos.",
'ipb-edit-dropdown'               => "Editar as razons d'o bloqueyo",
'ipb-unblock-addr'                => 'Desbloqueyar $1',
'ipb-unblock'                     => 'Desbloqueyar un usuario u una IP',
'ipb-blocklist-addr'              => 'Bloqueyos actuals de $1',
'ipb-blocklist'                   => 'Amostrar bloqueyos actuals',
'ipb-blocklist-contribs'          => 'Contrebucions de $1',
'unblockip'                       => 'Desbloqueyar usuario',
'unblockiptext'                   => "Replene o formulario que bi ha contino ta tornar os premisos d'escritura ta una adreza IP u cuenta d'usuario que haiga estato bloqueyata.",
'ipusubmit'                       => 'Debantar ista bloqueyo',
'unblocked'                       => '[[User:$1|$1]] ha estato desbloqueyato',
'unblocked-id'                    => "S'ha sacato o bloqueyo $1",
'ipblocklist'                     => "Adrezas IP y nombres d'usuario bloqueyatos",
'ipblocklist-legend'              => 'Mirar un usuario bloqueyato',
'ipblocklist-username'            => "Nombre d'usuario u adreza IP:",
'ipblocklist-sh-userblocks'       => '$1 bloqueyos de cuentas',
'ipblocklist-sh-tempblocks'       => '$1 bloqueyos temporals',
'ipblocklist-sh-addressblocks'    => "$1 bloqueyos d'adrezas IP individuals",
'ipblocklist-submit'              => 'Mirar',
'ipblocklist-localblock'          => 'Bloqueyo local',
'ipblocklist-otherblocks'         => '{{PLURAL:$1|Atro bloqueyo|Atros bloqueyos}}',
'blocklistline'                   => '$1, $2 ha bloqueyato a $3 ($4)',
'infiniteblock'                   => 'infinito',
'expiringblock'                   => 'circumduz o $1 a las $2',
'anononlyblock'                   => 'nomás anon.',
'noautoblockblock'                => 'Bloqueyo automatico desactivato',
'createaccountblock'              => "S'ha bloqueyato a creyación de nuevas cuentas",
'emailblock'                      => "S'ha bloqueyato o ninvió de correus electronicos",
'blocklist-nousertalk'            => 'No puet editar a suya propia pachina de descusión',
'ipblocklist-empty'               => 'A lista de bloqueyos ye bueda.',
'ipblocklist-no-results'          => "A cuenta d'usuario u adreza IP indicata no ye bloqueyata.",
'blocklink'                       => 'bloqueyar',
'unblocklink'                     => 'desbloqueyar',
'change-blocklink'                => 'cambear bloqueyo',
'contribslink'                    => 'contrebucions',
'autoblocker'                     => 'Ye bloqueyato automaticament porque a suya adreza IP l\'ha feito servir recientement "[[User:$1|$1]]". A razón data ta bloqueyar a "[[User:$1|$1]]" estió "$2".',
'blocklogpage'                    => 'Rechistro de bloqueyos',
'blocklog-showlog'                => "Iste usuario ya ha estau bloqueyau.
Ta más detalles, debaixo s'amuestro o rechistro de bloqueyos:",
'blocklog-showsuppresslog'        => "Iste usuario ha estau bloqueyau y amagau.
Ta más detalles, debaixo s'amuestra o rechistro de supresions:",
'blocklogentry'                   => "S'ha bloqueyato a [[$1]] con una durada de $2 $3",
'reblock-logentry'                => 'cambiato o bloqueyo de [[$1]] con circumducción o $3 a las $2',
'blocklogtext'                    => "Isto ye un rechistro de bloqueyos y desbloqueyos d'usuarios. As adrezas bloqueyatas automaticament no amaneixen aquí. Mire-se a [[Special:IPBlockList|lista d'adrezas IP bloqueyatas]] ta veyer a lista actual de vedas y bloqueyos.",
'unblocklogentry'                 => 'ha desbloqueyato a "$1"',
'block-log-flags-anononly'        => 'nomás os usuarios anonimos',
'block-log-flags-nocreate'        => "s'ha desactivato a creyación de cuentas",
'block-log-flags-noautoblock'     => "s'ha desactivato o bloqueyo automatico",
'block-log-flags-noemail'         => "s'ha desactivato o ninvío de mensaches por correu electronico",
'block-log-flags-nousertalk'      => 'no puet editar a suya pachina de descusión',
'block-log-flags-angry-autoblock' => "s'ha activato l'autobloqueyo amillorato",
'block-log-flags-hiddenname'      => "nombre d'usuario oculto",
'range_block_disabled'            => "A posibilidat d'os almenistradors de bloqueyar rangos d'adrezas IP ye desactivata.",
'ipb_expiry_invalid'              => 'O tiempo de circumducción no ye conforme.',
'ipb_expiry_temp'                 => "Os bloqueyos con nombre d'usuario amagato habría d'estar ta cutio.",
'ipb_hide_invalid'                => "No s'ha puesto eliminar a cuenta; talment tiene masiadas edicions.",
'ipb_already_blocked'             => '"$1" ya yera bloqueyato',
'ipb-needreblock'                 => "== Ya ye bloqueyato ==
$1 ya ye bloqueyato. Quiere cambiar as condicions d'o bloqueyo?",
'ipb-otherblocks-header'          => '{{PLURAL:$1|Atro bloqueyo|Atros bloqueyos}}',
'ipb_cant_unblock'                => "'''Error''': no s'ha trobato o ID de bloqueyo $1. Talment sía ya desbloqueyato.",
'ipb_blocked_as_range'            => "Error: L'adreza IP $1 no s'ha bloqueyato dreitament y por ixo no se puede desbloqueyar. Manimenos, ye bloqueyata por estar parte d'o rango $2, que sí puede desbloqueyar-se de conchunta.",
'ip_range_invalid'                => "O rango d'adrezas IP no ye conforme.",
'ip_range_toolarge'               => 'No se permiten os bloqueyos de rangos más grans que /$1.',
'blockme'                         => 'bloqueyar-me',
'proxyblocker'                    => 'bloqueyador de proxies',
'proxyblocker-disabled'           => 'Ista función ye desactivata.',
'proxyblockreason'                => "S'ha bloqueyato a suya adreza IP porque ye un proxy ubierto. Por favor, contaute on o suyo furnidor de servicios d'Internet u con o suyo servicio d'asistencia tecnica e informe-les d'iste grau problema de seguridat.",
'proxyblocksuccess'               => 'Feito.',
'sorbsreason'                     => 'A suya adreza IP ye en a lista de proxies ubiertos en a DNSBL de {{SITENAME}}.',
'sorbs_create_account_reason'     => 'A suya adreza IP ye en a lista de proxies ubiertos en a DNSBL de {{SITENAME}}. No puede creyar una cuenta',
'cant-block-while-blocked'        => 'No puet bloqueyar a atros usuarios en o tiempo que ye bloqueyato.',
'cant-see-hidden-user'            => "L'usuario a qui ye mirando de bloqueyar ya ye bloqueyau y amagau. Como que ye posible que vusté no tienga o dreito hideuser, no puede veyer ni editar os bloqueyos d'ixe usuario.",
'ipbblocked'                      => 'No puede bloqueyar ni desbloqueyar atros usuarios porque ya ye bloqueyau.',
'ipbnounblockself'                => 'No tiene permiso ta sacar o suyo propio bloqueyo',

# Developer tools
'lockdb'              => 'Trancar a base de datos',
'unlockdb'            => 'Destrancar a base de datos',
'lockdbtext'          => "Trancando a base de datos privará a totz os usuarios d'editar pachinas, cambiar as preferencias, cambiar as listas de seguimiento y qualsiquier atra función que ameniste fer cambios en a base de datos. Por favor, confirme que isto ye mesmament o que se mira de fer y que destrancará a base de datos malas que haiga rematato con a fayena de mantenimiento.",
'unlockdbtext'        => "Destrancar a base de datos premitirá a totz os usuarios d'editar pachinas, cambiar as preferencias y as listas de seguimiento, y qualsiquier atra función que ameniste cambiar a base de datos. Por favor, confirme que isto ye mesmament o que se mira de fer.",
'lockconfirm'         => 'Sí, de verdat quiero trancar a base de datos.',
'unlockconfirm'       => 'Sí, de verdat quiero destrancar a base de datos.',
'lockbtn'             => 'Trancar a base de datos',
'unlockbtn'           => 'Destrancar a base de datos',
'locknoconfirm'       => 'No ha sinyalato a caixeta de confirmación.',
'lockdbsuccesssub'    => "A base de datos s'ha trancato correctament",
'unlockdbsuccesssub'  => "A base de datos s'ha estrancato correctament",
'lockdbsuccesstext'   => "Ha trancato a base de datos de {{SITENAME}}.
Alcuerde-se-ne d'[[Special:UnlockDB|destrancar a base de datos]] dimpués de rematar as fayenas de mantenimiento.",
'unlockdbsuccesstext' => "S'ha destrancato a base de datos de {{SITENAME}}.",
'lockfilenotwritable' => "O rechistro de trancamientos d'a base de datos no tiene premiso d'escritura. Ta trancar u destrancar a base de datos, iste fichero ha de tener premisos d'escritura en o servidor web.",
'databasenotlocked'   => 'A base de datos no ye trancata.',

# Move page
'move-page'                    => 'Tresladar $1',
'move-page-legend'             => 'Tresladar pachina',
'movepagetext'                 => "Fendo servir o formulario siguient se cambiará o nombre d'a pachina, tresladando tot o suyo historial t'o nuevo nombre.
O títol anterior se tornará en una reendrecera ta o nuevo títol.
Puede esviellar automaticament as reendreceras que plegan ta o títol orichinal.
Si s'estima más de no fer-lo, asegure-se de no deixar [[Special:DoubleRedirects|reendreceras doples]] u [[Special:BrokenRedirects|trencatas]].
Ye a suya responsabilidat d'asegurar-se que os vinclos continan endrezando t'a on que habrían de fer-lo.

Remere que a pachina '''no''' se renombrará si ya existe una pachina con o nuevo títol, si no ye que estase una pachina vueda u una ''reendrecera'' sin historial.
Isto significa que podrá tresladar una pachina ta o suyo títol orichinal si ha feito una error, pero no podrá escribir dencima d'una pachina ya existent.

'''¡PARE CUENTA!'''
Iste puede estar un cambio drastico e inasperato ta una pachina popular;
por favor, asegure-se d'entender as conseqüencias que tendrá ista acción antes de seguir enta debant.",
'movepagetalktext'             => "A pachina de descusión asociata será tresladata automaticament '''de no estar que:'''

*Ya exista una pachina de descusión no vueda con o nombre nuevo, u
*Desactive a caixeta d'abaixo.

En ixos casos, si lo deseya, habrá de tresladar u combinar manualment o conteniu d'a pachina de descusión.",
'movearticle'                  => 'Tresladar pachina:',
'moveuserpage-warning'         => "'''Pare cuenta:''' ye en momentos de tresladar una pachina d'usuario. Pare cuenta en que nomás a pachina será tresladada peor l'usuario '''no''' será renombrau.",
'movenologin'                  => 'No ha encetato sesión',
'movenologintext'              => 'Amenista estar un usuario rechistrato y [[Special:UserLogin|aber-se identificato encetando una sesión]] ta tresladar una pachina.',
'movenotallowed'               => 'No tiene premisos ta tresladar pachinas.',
'movenotallowedfile'           => 'No tien premiso ta tresladar fichers.',
'cant-move-user-page'          => "No tien premiso ta tresladar pachinas d'usuario (fueras de subpachinas).",
'cant-move-to-user-page'       => "No tiene premisos ta tresladar una pachina ta una pachina d'usuario (fueras de si ye ta una subpachina).",
'newtitle'                     => 'Ta o nuevo títol',
'move-watch'                   => 'Cosirar iste articlo',
'movepagebtn'                  => 'Tresladar pachina',
'pagemovedsub'                 => 'Treslado feito correctament',
'movepage-moved'               => "S'ha tresladato '''\"\$1\"  ta \"\$2\"'''",
'movepage-moved-redirect'      => "S'ha creyato una reendrecera.",
'movepage-moved-noredirect'    => "S'ha cancelato a creyación d'una reendrecera.",
'articleexists'                => 'Ya bi ha una pachina con ixe nombre u o nombre que ha eslechito no ye conforme. Por favor trigue un atro nombre.',
'cantmove-titleprotected'      => 'No puede tresladar una pachina ta íste títol porque o nuevo títol ye protechito y no puede estar creyato',
'talkexists'                   => "A pachina s'ha tresladato correctament, pero a descusión no s'ha puesto tresladar porque ya en existe una con o nuevo títol. Por favor, incorpore manualment o suyo conteniu.",
'movedto'                      => 'tresladato ta',
'movetalk'                     => 'Tresladar a pachina de descusión asociata.',
'move-subpages'                => 'Tresladar as sozpachinas (dica $1)',
'move-talk-subpages'           => "Tresladar todas as sozpachinas d'a pachina de descusión (dica $1)",
'movepage-page-exists'         => 'A pachina $1 ya existe y no se puede sobrescribir automaticament.',
'movepage-page-moved'          => "S'ha tresladato a pachina $1 ta $2.",
'movepage-page-unmoved'        => "No s'ha puesto tresladar a pachina $1 ta $2.",
'movepage-max-pages'           => "S'han tresladato o masimo posible de $1 {{PLURAL:$1|pachina|pachinas}} y no se tresladarán más automaticament.",
'1movedto2'                    => '[[$1]] tresladada a [[$2]]',
'1movedto2_redir'              => '[[$1]] tresladada a [[$2]] sobre una reendrecera',
'move-redirect-suppressed'     => 'reendrecera eliminata',
'movelogpage'                  => 'Rechistro de treslatos',
'movelogpagetext'              => 'Contino se i amuestra una lista de pachinas tresladatas.',
'movesubpage'                  => '{{PLURAL:$1|Subpachina|Subpachinas}}',
'movesubpagetext'              => 'Ista pachina tien {{PLURAL:$1|a siguient subpachina|as siguients $1 subpachinas}}:',
'movenosubpage'                => 'Ista pachina no tien subpachinas',
'movereason'                   => 'Razón:',
'revertmove'                   => 'revertir',
'delete_and_move'              => 'Borrar y tresladar',
'delete_and_move_text'         => '==S\'amenista borrar a pachina==

A pachina de destino ("[[:$1]]") ya existe. Quiere borrar-la ta premitir o treslau?',
'delete_and_move_confirm'      => 'Sí, borrar a pachina',
'delete_and_move_reason'       => 'Borrata ta premitir o treslau',
'selfmove'                     => "Os títols d'orichen y destino son os mesmos. No se puede tresladar una pachina ta ella mesma.",
'immobile-source-namespace'    => 'No puede tresladar pachinas en o espacio de nombres "$1"',
'immobile-target-namespace'    => 'No puede tresladar pachinas enta o espacio de nombres "$1"',
'immobile-target-namespace-iw' => 'No se puet tresladar una pachina enta un vinclo interwiki.',
'immobile-source-page'         => 'Ista pachina no se puet tresladar.',
'immobile-target-page'         => 'No se puet tresladar ta ixe títol.',
'imagenocrossnamespace'        => 'No se puede tresladar un fichero ta un espacio de nombres que no sía fichers',
'nonfile-cannot-move-to-file'  => 'No ye posible de mover qualcosa que no siga un fichero ta o espacio de nombres fichers',
'imagetypemismatch'            => 'A nueva estensión no concuerda con o tipo de fichero',
'imageinvalidfilename'         => "O nombre d'o fichero obchectivo no ye conforme",
'fix-double-redirects'         => 'Esviellar todas as reendreceras que plegan ta o títol orichinal',
'move-leave-redirect'          => 'Deixar una reendrecera',
'protectedpagemovewarning'     => "'''Pare cuenta:''' Ista pachina s'ha bloqueyat de traza que nomás usuarios con dreitos d'administrador puedan tresladar-la. Ta más información s'amuestra contino a zaguera dentrada d'o rechistro.",
'semiprotectedpagemovewarning' => "'''Pare cuenta:''' Ista pachina s'ha bloqueyato ta que nomás os usuarios rechistratos pueden puedan tresladar-la. Contino s'amuestra a zaguera dentrada d'o rechistro:",
'move-over-sharedrepo'         => '== O fichero existe ==
[[:$1]] existe en un reposte compartiu. Mover o fichero ta ista títol invalidará o fichero completo.',
'file-exists-sharedrepo'       => 'O nombre de fichero trigau ya ye estando usato en un reposte compartiu. Por favor, esliya-ne un atro.',

# Export
'export'            => 'Exportar pachinas',
'exporttext'        => "Puede exportar o texto y l'historial d'edicions d'una pachina u conchunto de pachinas ta un texto XML. Iste texto XML puede importar-se ta atro wiki que faiga servir MediaWiki a traviés d'a [[Special:Import|pachina d'importación]].

Ta exportar pachinas, escriba os títols en a caixa de texto que bi ha más ta baixo, metendo un títol en cada linia, y esliya si quiere exportar a versión actual con as versions anteriors y as linias de l'historial u nomás a versión actual con a información sobre a zaguer edición.

En iste zaguer caso tamién puede usar un vinclo, por eixemplo [[{{#Special:Export}}/{{MediaWiki:Mainpage}}]] t'a pachina \"[[{{MediaWiki:Mainpage}}]]\".",
'exportcuronly'     => "Incluyir nomás a versión actual, no pas l'historial de versions completo.",
'exportnohistory'   => "----
'''Nota:''' A exportación de historials de pachinas a traviés d'iste formulario ye desactivata por problemas en o rendimiento d'o servidor.",
'export-submit'     => 'Exportar',
'export-addcattext' => 'Adhibir pachinas dende a categoría:',
'export-addcat'     => 'Adhibir',
'export-addnstext'  => "Adhibir pachinas d'o espacio de nombres:",
'export-addns'      => 'Adhibir',
'export-download'   => 'Alzar como un fichero',
'export-templates'  => 'incluyir-ie plantillas',
'export-pagelinks'  => 'Incluyir pachinas vinculadas con una fundaria de:',

# Namespace 8 related
'allmessages'                   => "Mensaches d'o sistema",
'allmessagesname'               => 'Nombre',
'allmessagesdefault'            => 'texto por defecto',
'allmessagescurrent'            => 'texto actual',
'allmessagestext'               => "Ista ye una lista de totz os mensaches disponibles en o espacio de nombres MediaWiki.
Vesite por favor [http://www.mediawiki.org/wiki/Localisation a pachina sobre localización de MediaWiki] y  [http://translatewiki.net translatewiki.net] si deseya contrebuyir t'a localización cheneral de MediaWiki.",
'allmessagesnotsupportedDB'     => 'Ista pachina no ye disponible porque wgUseDatabaseMessages ye desactivato.',
'allmessages-filter-legend'     => 'Filtro',
'allmessages-filter'            => 'Filtrar por estau de personalización:',
'allmessages-filter-unmodified' => 'Sin modificar',
'allmessages-filter-all'        => 'Totz',
'allmessages-filter-modified'   => 'Modificato',
'allmessages-prefix'            => 'Filtrar por prefixo:',
'allmessages-language'          => 'Idioma:',
'allmessages-filter-submit'     => 'Ir-ie',

# Thumbnails
'thumbnail-more'           => 'Fer más gran',
'filemissing'              => 'Archivo no trobato',
'thumbnail_error'          => "S'ha producito una error en creyar a miniatura: $1",
'djvu_page_error'          => "Pachina DjVu difuera d'o rango",
'djvu_no_xml'              => "No s'ha puesto replegar o XML ta o fichero DjVu",
'thumbnail_invalid_params' => "Os parametros d'as miniatura no son correctos",
'thumbnail_dest_directory' => "No s'ha puesto creyar o directorio de destino",
'thumbnail_image-type'     => "Mena d'imachen no prevista",
'thumbnail_gd-library'     => "Configuración d'a librería GD incompleta: falta a función $1",
'thumbnail_image-missing'  => 'O fichero pareix no existir: $1',

# Special:Import
'import'                     => 'Importar pachinas',
'importinterwiki'            => 'Importación interwiki',
'import-interwiki-text'      => "Trigue un wiki y un títol de pachina ta importar.
As calendatas d'as versions y os nombres d'os editors se preservarán.
Todas as importacions interwiki se rechistran en o [[Special:Log/import|rechistro d'importacions]].",
'import-interwiki-source'    => 'Wiki/pachina fuent:',
'import-interwiki-history'   => "Copiar todas as versions de l'historial d'ista pachina",
'import-interwiki-templates' => 'Incluir-ie todas as las plantillas',
'import-interwiki-submit'    => 'Importar',
'import-interwiki-namespace' => 'Espacio de nombres de destín:',
'import-upload-filename'     => 'Nombre de fichero:',
'import-comment'             => 'Comentario:',
'importtext'                 => "Por favor, exporte o fichero dende o wiki d'orichen fendo servir a [[Special:Export|ferramienta d'exportación]]. Alce-lo en o suyo ordenador y cargue-lo aquí.",
'importstart'                => 'Importando pachinas...',
'import-revision-count'      => '$1 {{PLURAL:$1|versión|versions}}',
'importnopages'              => 'No bi ha garra pachina ta importar.',
'imported-log-entries'       => "S'ha importau {{PLURAL:$1|una dentrada d'o rechistro|S'han importau $1 dentradas d'o rechistro}}.",
'importfailed'               => 'Ha fallato a importación: $1',
'importunknownsource'        => "O tipo de fuent d'a importación ye esconoixito",
'importcantopen'             => "No s'ha puesto importar iste fichero",
'importbadinterwiki'         => 'vinclo interwiki incorrecto',
'importnotext'               => 'Buendo y sin de texto',
'importsuccess'              => "S'ha rematato a importación!",
'importhistoryconflict'      => "Bi ha un conflicto de versions en o historial (talment ista pachina s'haiga importato denantes)",
'importnosources'            => "No bi ha fuents d'importación interwiki y no ye premitito cargar o historial dreitament.",
'importnofile'               => "No s'ha cargato os fichers d'importación.",
'importuploaderrorsize'      => "Ha fallato a carga d'o fichero importato. O fichero brinca d'a grandaria de carga premitita.",
'importuploaderrorpartial'   => "Ha fallato a carga d'o fichero importato. Sólo una parte d'o fichero s'ha cargato.",
'importuploaderrortemp'      => "Ha fallato a carga d'o fichero importato. No se troba o directorio temporal.",
'import-parse-failure'       => "Fallo en o parseyo d'a importación XML",
'import-noarticle'           => 'No bi ha garra pachina ta importar!',
'import-nonewrevisions'      => "Ya s'heban importato denantes todas as versions.",
'xml-error-string'           => '$1 en a linia $2, col $3 (byte $4): $5',
'import-upload'              => 'Datos XML cargatos',
'import-token-mismatch'      => "S'han perdito os datos d'a sesión. Por favor, prebe unatra vegada.",
'import-invalid-interwiki'   => 'No se puet importar dende o wiki especificato.',

# Import log
'importlogpage'                    => "Rechistro d'importacions",
'importlogpagetext'                => 'Importacions almenistrativas de pachinas con historial dende atros wikis.',
'import-logentry-upload'           => 'importata [[$1]] cargando un fichero',
'import-logentry-upload-detail'    => '$1 {{PLURAL:$1|versión|versions}}',
'import-logentry-interwiki'        => 'Importata $1 entre wikis',
'import-logentry-interwiki-detail' => '$1 {{PLURAL:$1|versión|versions}} dende $2',

# Tooltip help for the actions
'tooltip-pt-userpage'             => "A suya pachina d'usuario",
'tooltip-pt-anonuserpage'         => "A pachina d'usuario de l'adreza IP dende a que ye editando",
'tooltip-pt-mytalk'               => 'A suya pachina de descusión',
'tooltip-pt-anontalk'             => 'Descusión sobre edicions feitas dende ista adreza IP',
'tooltip-pt-preferences'          => 'As suyas preferencias',
'tooltip-pt-watchlist'            => 'A lista de pachinas que en ye cosirando os cambeos',
'tooltip-pt-mycontris'            => "Lista d'as suyas contrebucions",
'tooltip-pt-login'                => 'Le recomendamos que se rechistre, encara que no ye obligatorio',
'tooltip-pt-anonlogin'            => 'Li alentamos a rechistrar-se, anque no ye obligatorio',
'tooltip-pt-logout'               => 'Rematar a sesión',
'tooltip-ca-talk'                 => "Descusión sobre l'articlo",
'tooltip-ca-edit'                 => 'Puede editar ista pachina. Por favor, faiga servir o botón de visualización previa antes de grabar.',
'tooltip-ca-addsection'           => 'Encetar una nueva sección',
'tooltip-ca-viewsource'           => 'Ista pachina ye protechit.
Puede veyer-ne, manimenos, o codigo fuent.',
'tooltip-ca-history'              => "Versions anteriors d'ista pachina.",
'tooltip-ca-protect'              => 'Protecher ista pachina',
'tooltip-ca-unprotect'            => 'Desproteger ista pagina',
'tooltip-ca-delete'               => 'Borrar ista pachina',
'tooltip-ca-undelete'             => 'Restaurar as edicions feitas a ista pachina antis que no estase borrata',
'tooltip-ca-move'                 => 'Tresladar (renombrar) ista pachina',
'tooltip-ca-watch'                => 'Adhibir ista pachina a la suya lista de seguimiento',
'tooltip-ca-unwatch'              => "Borrar ista pachina d'a suya lista de seguimiento",
'tooltip-search'                  => 'Mirar en {{SITENAME}}',
'tooltip-search-go'               => "Ir t'a pachina con iste títol exacto, si existe",
'tooltip-search-fulltext'         => 'Mirar iste texto en as pachinas',
'tooltip-p-logo'                  => 'Portalada',
'tooltip-n-mainpage'              => 'Vesitar a Portalada',
'tooltip-n-mainpage-description'  => 'Vesitar a pachina prencipal',
'tooltip-n-portal'                => 'Sobre o prochecto, que puede fer, aon trobar as cosas',
'tooltip-n-currentevents'         => 'Trobar información cheneral sobre escaicimientos actuals',
'tooltip-n-recentchanges'         => "A lista d'os zaguers cambeos en o wiki",
'tooltip-n-randompage'            => 'Cargar una pachina aleatoriament',
'tooltip-n-help'                  => 'O puesto ta saber más.',
'tooltip-t-whatlinkshere'         => "Lista de todas as pachinas d'o wiki vinculatas con ista",
'tooltip-t-recentchangeslinked'   => 'Zaguers cambeos en as pachinas que tienen vinclos enta ista',
'tooltip-feed-rss'                => "Canal RSS d'ista pachina",
'tooltip-feed-atom'               => "Canal Atom d'ista pachina",
'tooltip-t-contributions'         => "Veyer a lista de contrebucions d'iste usuario",
'tooltip-t-emailuser'             => 'Ninviar un correu electronico ta iste usuario',
'tooltip-t-upload'                => 'Lista de todas as pachinas especials',
'tooltip-t-specialpages'          => 'Lista de todas as pachinas especials',
'tooltip-t-print'                 => "Versión d'ista pachina ta imprentar",
'tooltip-t-permalink'             => "Vinclo permanent ta ista versión d'a pachina",
'tooltip-ca-nstab-main'           => 'Veyer a pachina',
'tooltip-ca-nstab-user'           => "Veyer a pachina d'usuario",
'tooltip-ca-nstab-media'          => "Veyer a pachina d'o elemento multimedia",
'tooltip-ca-nstab-special'        => 'Ista ye una pachina especial, y no puede editar-la',
'tooltip-ca-nstab-project'        => "Veyer a pachina d'o prochecto",
'tooltip-ca-nstab-image'          => "Veyer a pachina d'o fichero",
'tooltip-ca-nstab-mediawiki'      => 'Veyer o mensache de sistema',
'tooltip-ca-nstab-template'       => 'Veyer a plantilla',
'tooltip-ca-nstab-help'           => "Veyer a pachina d'aduya",
'tooltip-ca-nstab-category'       => "Veyer a pachina d'a categoría",
'tooltip-minoredit'               => 'Sinyalar ista edición como menor',
'tooltip-save'                    => 'Alzar os cambeos',
'tooltip-preview'                 => 'Revise os suyos cambeos, por favor, faiga servir isto antes de grabar!',
'tooltip-diff'                    => 'Amuestra os cambeos que ha feito en o texto.',
'tooltip-compareselectedversions' => "Veyer  as esferencias entre as dos versions trigatas d'ista pachina.",
'tooltip-watch'                   => 'Adhibir ista pachina a la suya lista de seguimiento',
'tooltip-recreate'                => 'Recreya una pachina mesmo si ya ha estato borrata dinantes',
'tooltip-upload'                  => 'Prencipia a carga',
'tooltip-rollback'                => '"Revertir" revierte todas as zagueras edicions d\'un mesmo usuario en ista pachina nomás con un clic.',
'tooltip-undo'                    => '"Desfer" revierte a edición trigata y ubre a pachina d\'edición en o modo de previsualización. Deixa escribir una razón en o resumen d\'edición.',
'tooltip-preferences-save'        => 'Alzar as preferencias',
'tooltip-summary'                 => 'Escribir un breu resumen',

# Metadata
'nodublincore'      => 'Metadatos Dublin Core RDF desactivatos en iste servidor.',
'nocreativecommons' => 'Metadatos Creative Commons RDF desactivatos en iste servidor.',
'notacceptable'     => 'O servidor wiki no puede ufrir os datos en un formato que o suyo client (navegador) pueda leyer.',

# Attribution
'anonymous'        => '{{PLURAL:$1|Usuario anónimo|Usuarios anónimos}} de {{SITENAME}}',
'siteuser'         => 'Usuario $1 de {{SITENAME}}',
'anonuser'         => '{{SITENAME}} usuario anonimo $1',
'lastmodifiedatby' => 'Ista pachina estió modificata por zaguer vegada a $2, $1 por $3.',
'othercontribs'    => 'Basato en o treballo de $1.',
'others'           => 'atros',
'siteusers'        => '{{PLURAL:$2|Usuario|Usuarios}} $1 de {{SITENAME}}',
'anonusers'        => '{{SITENAME}} {{PLURAL:$2|usuario|usuarios}} anonimos $1',
'creditspage'      => "Creditos d'a pachina",
'nocredits'        => 'No bi ha información de creditos ta ista pachina.',

# Spam protection
'spamprotectiontitle' => 'Filtro de protección contra o spam',
'spamprotectiontext'  => "A pachina que mira d'alzar l'ha bloqueyata o filtro de spam.  Regular que a causa sía que i haiga bell vinclo ta un sitio externo que i sía en a lista negra.",
'spamprotectionmatch' => 'O texto siguient ye o que activó o nuestro filtro de spam: $1',
'spambot_username'    => 'Esporga de spam de MediaWiki',
'spam_reverting'      => "Tornando t'a zaguera versión sin de vinclos ta $1",
'spam_blanking'       => 'Todas as versions teneban vinclos ta $1, se deixa en blanco',

# Info page
'infosubtitle'   => "Información d'a pachina",
'numedits'       => "Numero d'edicions (articlo): $1",
'numtalkedits'   => "Numero d'edicions (pachina de descusión): $1",
'numwatchers'    => "Número d'usuario cosirando: $1",
'numauthors'     => "Numero d'autors (articlo): $1",
'numtalkauthors' => "Numero d'autors (pachina de descusión): $1",

# Skin names
'skinname-standard'    => 'Clasica (Classic)',
'skinname-nostalgia'   => 'Recosiros (Nostalgia)',
'skinname-cologneblue' => 'Colonia Azul (Cologne Blue)',
'skinname-myskin'      => 'A mía aparenzia (MySkin)',
'skinname-simple'      => 'Simpla (Simple)',

# Math options
'mw_math_png'    => 'Producir siempre PNG',
'mw_math_simple' => "HTML si ye muit simple, si no'n ye, PNG",
'mw_math_html'   => "HTML si ye posible, si no'n ye, PNG",
'mw_math_source' => 'Deixar como TeX (ta navegadores en formato texto)',
'mw_math_modern' => 'Recomendato ta navegadors modernos',
'mw_math_mathml' => 'MathML si ye posible (esperimental)',

# Math errors
'math_failure'          => 'Error en o codigo',
'math_unknown_error'    => 'error esconoxita',
'math_unknown_function' => 'función esconoxita',
'math_lexing_error'     => 'error de lexico',
'math_syntax_error'     => 'error de sintaxi',
'math_image_error'      => 'A conversión enta PNG ha tenito errors;
comprebe si latex, dvips, gs y convert son bien instalatos.',
'math_bad_tmpdir'       => "No s'ha puesto escribir u creyar o directorio temporal d'esprisions matematicas",
'math_bad_output'       => "No s'ha puesto escribir u creyar o directorio de salida d'esprisions matematicas",
'math_notexvc'          => "No s'ha trobato o fichero executable ''texvc''. Por favor, leiga <em>math/README</em> ta confegurar-lo correctament.",

# Patrolling
'markaspatrolleddiff'                 => 'Sinyalar como ya controlato',
'markaspatrolledtext'                 => 'Sinyalar iste articlo como controlato',
'markedaspatrolled'                   => 'Sinyalato como controlato',
'markedaspatrolledtext'               => "A versión seleccionata de [[:$1]] s'ha sinyalato como patrullata.",
'rcpatroldisabled'                    => "S'ha desactivato o control d'os zagurers cambeos",
'rcpatroldisabledtext'                => "A función de control d'os zaguers cambeos ye desactivata en iste inte.",
'markedaspatrollederror'              => 'No se puede sinyalar como controlata',
'markedaspatrollederrortext'          => "Ha d'especificar una versión ta sinyalar-la como revisata.",
'markedaspatrollederror-noautopatrol' => 'No tiene premisos ta sinyalar os suyos propios cambios como controlatos.',

# Patrol log
'patrol-log-page'      => 'Rechistro de control de revisions',
'patrol-log-header'    => 'Iste ye un rechistro de revisions patrullatas.',
'patrol-log-line'      => "s'ha sinyalato a versión $1 de $2 como revisata $3",
'patrol-log-auto'      => '(automatico)',
'patrol-log-diff'      => 'versión $1',
'log-show-hide-patrol' => '$1 o rechistro de patrullache',

# Image deletion
'deletedrevision'                 => "S'ha borrato a versión antiga $1",
'filedeleteerror-short'           => 'Error borrando o fichero: $1',
'filedeleteerror-long'            => 'Se troboron errors borrando o fichero:

$1',
'filedelete-missing'              => 'O fichero "$1" no se puede borrar porque no existe.',
'filedelete-old-unregistered'     => 'A versión d\'o fichero especificata "$1" no ye en a base de datos.',
'filedelete-current-unregistered' => 'O fichero especificato "$1" no ye en a base de datos.',
'filedelete-archive-read-only'    => 'O directorio de fichero "$1" no puede escribir-se en o servidor web.',

# Browsing diffs
'previousdiff' => "← Ir t'a edición anterior",
'nextdiff'     => "Ir t'a edición siguient →",

# Media information
'mediawarning'         => "'''Pare cuenta!''': Iste tipo de fichero puet contener codigo endino.
En executar-lo, podría meter en un contornillo a seguridat d'o suyo sistema.",
'imagemaxsize'         => "Limite de grandaria d'as imáchens:<br />''(ta pachinas de descripción de fichers)''",
'thumbsize'            => "Midas d'a miniatura:",
'widthheightpage'      => '$1×$2, $3 {{PLURAL:$3|pachina|pachinas}}',
'file-info'            => "(grandaria d'o fichero: $1; tipo MIME: $2)",
'file-info-size'       => "($1 × $2 píxels; grandaria d'o fichero: $3; tipo MIME: $4)",
'file-nohires'         => '<small>No bi ha garra versión con resolución más gran.</small>',
'svg-long-desc'        => '(fichero SVG, nominalment $1 × $2 píxels, grandaria: $3)',
'show-big-image'       => 'Imachen en a maxima resolución',
'show-big-image-thumb' => "<small>Grandaria d'ista anvista previa: $1 × $2 píxels</small>",
'file-info-gif-looped' => 'embuclau',
'file-info-gif-frames' => '$1 {{PLURAL:$1|imachen|imáchens}}',
'file-info-png-looped' => 'embuclau',
'file-info-png-repeat' => 'reproducito $1 {{PLURAL:$1|vegada|vegadas}}',
'file-info-png-frames' => '$1 {{PLURAL:$1|imáchens|imáchens}}',

# Special:NewFiles
'newimages'             => 'Galería de nuevas imachens',
'imagelisttext'         => "Contino bi ha una lista de '''$1''' {{PLURAL:$1|imachen ordenata|imachens ordenatas}} $2.",
'newimages-summary'     => 'Ista pachina especial amuestra os zaguers fichers cargatos.',
'newimages-legend'      => 'Filtro',
'newimages-label'       => "Nombre d'o fichero (u bella parte d'el):",
'showhidebots'          => '($1 bots)',
'noimages'              => 'No bi ha cosa a veyer.',
'ilsubmit'              => 'Mirar',
'bydate'                => 'por a calendata',
'sp-newimages-showfrom' => "Amostrar fichers nuevos dende as $2 d'o $1",

# Bad image list
'bad_image_list' => "O formato ha d'estar o siguient:

Nomás se consideran os elementos de lista (ringleras que escomienzan por *). O primer vinclo de cada linia ha d'estar un vinclo ta un fichero malo. Qualsiquier atros vinclos en a mesma linia se consideran excepcions, ye dicir, pachinas an que o fichero puede amaneixer.",

# Metadata
'metadata'          => 'Metadatos',
'metadata-help'     => 'Iste fichero contiene información adicional, probablement adhibida dende a camara dichital, o escáner u o programa emplegato ta creyar-lo u dichitalizar-lo.  Si o fichero ha estato modificato dende o suyo estau orichinal, bells detalles podrían no refleixar de tot o fichero modificato.',
'metadata-expand'   => 'Amostrar información detallata',
'metadata-collapse' => 'Amagar a información detallata',
'metadata-fields'   => "Os campos de metadatos EXIF que amaneixen en iste mensache s'amostrarán en a pachina de descripción d'a imachen, mesmo si a tabla ye plegata. Bi ha atros campos que remanirán amagatos por defecto.
* make
* model
* datetimeoriginal
* exposuretime
* fnumber
* isospeedratings
* focallength",

# EXIF tags
'exif-imagewidth'                  => 'Amplaria',
'exif-imagelength'                 => 'Altaria',
'exif-bitspersample'               => 'Bits por component',
'exif-compression'                 => 'Esquema de compresión',
'exif-photometricinterpretation'   => "Composición d'os pixels",
'exif-orientation'                 => 'Orientación',
'exif-samplesperpixel'             => 'Numero de components por píxel',
'exif-planarconfiguration'         => 'Ordinación de datos',
'exif-ycbcrsubsampling'            => 'Razón de submuestreyo de Y a C',
'exif-ycbcrpositioning'            => 'Posición de Y y C',
'exif-xresolution'                 => 'Resolución horizontal',
'exif-yresolution'                 => 'Resolución vertical',
'exif-resolutionunit'              => "Unidatz d'as resolucions en X e Y",
'exif-stripoffsets'                => "Localización d'os datos d'a imachen",
'exif-rowsperstrip'                => 'Numero de ringleras por faixa',
'exif-stripbytecounts'             => 'Bytes por faixa comprimita',
'exif-jpeginterchangeformat'       => "Offset d'o JPEG SOI",
'exif-jpeginterchangeformatlength' => 'Bytes de datos JPEG',
'exif-transferfunction'            => 'Función de transferencia',
'exif-whitepoint'                  => "Coordinatas cromaticas d'o punto blanco",
'exif-primarychromaticities'       => "Coordinatas cromaticas d'as colors primarias",
'exif-ycbcrcoefficients'           => "Coeficients d'a matriz de transformación d'o espacio de colors",
'exif-referenceblackwhite'         => 'Parella de valuras blanco/negro de referencia',
'exif-datetime'                    => "Calendata y hora d'o zaguer cambeo d'o fichero",
'exif-imagedescription'            => "Títol d'a imachen",
'exif-make'                        => "Fabriquero d'a maquina",
'exif-model'                       => 'Modelo de maquina',
'exif-software'                    => 'Software emplegato',
'exif-artist'                      => 'Autor',
'exif-copyright'                   => "Duenyo d'os dreitos d'autor (copyright)",
'exif-exifversion'                 => 'Versión Exif',
'exif-flashpixversion'             => 'Versión de Flashpix admesa',
'exif-colorspace'                  => 'Espacio de colors',
'exif-componentsconfiguration'     => 'Significación de cada component',
'exif-compressedbitsperpixel'      => "Modo de compresión d'a imachen",
'exif-pixelydimension'             => "Amplaria conforme d'a imachen",
'exif-pixelxdimension'             => "Altaria conforme d'a imachen",
'exif-makernote'                   => "Notas d'o fabriquero",
'exif-usercomment'                 => "Comentarios de l'usuario",
'exif-relatedsoundfile'            => "Fichero d'audio relacionato",
'exif-datetimeoriginal'            => "Calendata y hora de cheneración d'os datos",
'exif-datetimedigitized'           => "Calendata y hora d'a dichitalización",
'exif-subsectime'                  => 'Calendata y hora (fraccions de segundo)',
'exif-subsectimeoriginal'          => "Calendata y hora d'a cheneración d'os datos (fraccions de segundo)",
'exif-subsectimedigitized'         => "Calendata y hora d'a dichitalización (fraccions de segundo)",
'exif-exposuretime'                => "Tiempo d'exposición",
'exif-exposuretime-format'         => '$1 seg ($2)',
'exif-fnumber'                     => 'Numero F',
'exif-exposureprogram'             => "Programa d'exposición",
'exif-spectralsensitivity'         => 'Sensibilidat espectral',
'exif-isospeedratings'             => 'Sensibilidat ISO',
'exif-oecf'                        => 'Factor de conversión optoelectronica',
'exif-shutterspeedvalue'           => "Velocidat de l'obturador",
'exif-aperturevalue'               => 'Obredura',
'exif-brightnessvalue'             => 'Brilor',
'exif-exposurebiasvalue'           => "Siesco d'exposición",
'exif-maxaperturevalue'            => 'Obredura maxima',
'exif-subjectdistance'             => 'Distancia a o sucheto',
'exif-meteringmode'                => 'Modo de mesura',
'exif-lightsource'                 => 'Fuent de luz',
'exif-flash'                       => 'Flash',
'exif-focallength'                 => "Longaria d'o lente focal",
'exif-subjectarea'                 => "Aria d'o sucheto",
'exif-flashenergy'                 => "Enerchía d'o flash",
'exif-spatialfrequencyresponse'    => 'Respuesta en freqüencia espacial',
'exif-focalplanexresolution'       => 'Resolución en o plano focal X',
'exif-focalplaneyresolution'       => 'Resolución en o plano focal Y',
'exif-focalplaneresolutionunit'    => "Unidatz d'a resolución en o plano focal",
'exif-subjectlocation'             => "Posición d'o sucheto",
'exif-exposureindex'               => "Endice d'exposición",
'exif-sensingmethod'               => 'Metodo de sensache',
'exif-filesource'                  => "Fuent d'o fichero",
'exif-scenetype'                   => "Mena d'escena",
'exif-cfapattern'                  => 'Patrón CFA',
'exif-customrendered'              => "Procesau d'imachen presonalizato",
'exif-exposuremode'                => "Modo d'exposición",
'exif-whitebalance'                => 'Balance de blancos',
'exif-digitalzoomratio'            => 'Ratio de zoom dichital',
'exif-focallengthin35mmfilm'       => 'Longaria focal equivalent a cinta de 35 mm',
'exif-scenecapturetype'            => "Mena de captura d'a escena",
'exif-gaincontrol'                 => "Control d'escena",
'exif-contrast'                    => 'Contraste',
'exif-saturation'                  => 'Saturación',
'exif-sharpness'                   => 'Nitideza',
'exif-devicesettingdescription'    => "Descripción d'os achustes d'o dispositivo",
'exif-subjectdistancerange'        => 'Rango de distancias a o sucheto',
'exif-imageuniqueid'               => "ID unico d'a imachen",
'exif-gpsversionid'                => "Versión d'as etiquetas de GPS",
'exif-gpslatituderef'              => 'Latitut norte/sud',
'exif-gpslatitude'                 => 'Latitut',
'exif-gpslongituderef'             => 'Lonchitut este/ueste',
'exif-gpslongitude'                => 'Lonchitut',
'exif-gpsaltituderef'              => "Referencia d'a altitut",
'exif-gpsaltitude'                 => 'Altitut',
'exif-gpstimestamp'                => 'Tiempo GPS (reloch atomico)',
'exif-gpssatellites'               => 'Satelites emplegatos en a mida',
'exif-gpsstatus'                   => "Estau d'o receptor",
'exif-gpsmeasuremode'              => 'Modo de mesura',
'exif-gpsdop'                      => "Precisión d'a mida",
'exif-gpsspeedref'                 => 'Unidatz de velocidat',
'exif-gpsspeed'                    => "Velocidat d'o receptor GPS",
'exif-gpstrackref'                 => "Referencia d'a endrecera d'o movimiento",
'exif-gpstrack'                    => "Endrecera d'o movimiento",
'exif-gpsimgdirectionref'          => "Referencia d'a orientación d'a imachen",
'exif-gpsimgdirection'             => "Orientación d'a imachen",
'exif-gpsmapdatum'                 => 'Emplegatos datos de mesura cheodesica',
'exif-gpsdestlatituderef'          => "Referencia t'a latitut d'o destino",
'exif-gpsdestlatitude'             => "Latitut d'o destino",
'exif-gpsdestlongituderef'         => "Referencia d'a lonchitut d'o destino",
'exif-gpsdestlongitude'            => "Lonchitut d'o destino",
'exif-gpsdestbearingref'           => "Referencia d'a orientación a o destino",
'exif-gpsdestbearing'              => "Orientación d'o destino",
'exif-gpsdestdistanceref'          => "Referencia d'a distancia a o destino",
'exif-gpsdestdistance'             => 'Distancia a o destino',
'exif-gpsprocessingmethod'         => "Nombre d'o metodo de procesamiento GPS",
'exif-gpsareainformation'          => "Nombre d'aria GPS",
'exif-gpsdatestamp'                => 'Calendata GPS',
'exif-gpsdifferential'             => 'Corrección diferencial de GPS',

# EXIF attributes
'exif-compression-1' => 'Sin de compresión',

'exif-unknowndate' => 'Calendata esconoixita',

'exif-orientation-1' => 'Normal',
'exif-orientation-2' => 'Contornata horizontalment',
'exif-orientation-3' => 'Chirata 180º',
'exif-orientation-4' => 'Contornata verticalment',
'exif-orientation-5' => "Chirata 90° en contra d'as agullas d'o reloch y contornata verticalment",
'exif-orientation-6' => "Chirata 90° como as agullas d'o reloch",
'exif-orientation-7' => "Chirata 90° como as agullas d'o reloch y contornata verticalment",
'exif-orientation-8' => "Chirata 90° en contra d'as agullas d'o reloch",

'exif-planarconfiguration-1' => 'formato de paquetz de píxels',
'exif-planarconfiguration-2' => 'formato plano',

'exif-componentsconfiguration-0' => 'no existe',

'exif-exposureprogram-0' => 'No definito',
'exif-exposureprogram-1' => 'Manual',
'exif-exposureprogram-2' => 'Modo normal',
'exif-exposureprogram-3' => "Prioridat a l'obredura",
'exif-exposureprogram-4' => "Prioridat a l'obturador",
'exif-exposureprogram-5' => 'Modo creyativo (con prioridat a la fondura de campo)',
'exif-exposureprogram-6' => "Modo acción (alta velocidat de l'obturador)",
'exif-exposureprogram-7' => 'Modo retrato (ta primers planos con o fundo desenfocato)',
'exif-exposureprogram-8' => 'Modo paisache (ta fotos de paisaches con o fundo enfocato)',

'exif-subjectdistance-value' => '$1 metros',

'exif-meteringmode-0'   => 'Esconoixito',
'exif-meteringmode-1'   => 'Meya',
'exif-meteringmode-2'   => 'Meya aponderata a o centro',
'exif-meteringmode-3'   => 'Puntual',
'exif-meteringmode-4'   => 'Multipunto',
'exif-meteringmode-5'   => 'Patrón',
'exif-meteringmode-6'   => 'Parcial',
'exif-meteringmode-255' => 'Atros',

'exif-lightsource-0'   => 'Esconoixito',
'exif-lightsource-1'   => 'Luz de día',
'exif-lightsource-2'   => 'Fluorescent',
'exif-lightsource-3'   => 'Tungsteno (luz incandescent)',
'exif-lightsource-4'   => 'Flash',
'exif-lightsource-9'   => 'Buen orache',
'exif-lightsource-10'  => 'Orache nublo',
'exif-lightsource-11'  => 'Uembra',
'exif-lightsource-12'  => 'Fluorescente de luz de día (D 5700 – 7100K)',
'exif-lightsource-13'  => 'Fluorescent blanco de día (N 4600 – 5400K)',
'exif-lightsource-14'  => 'Fluorescent blanco fredo (W 3900 – 4500K)',
'exif-lightsource-15'  => 'Fluorescent blanco (WW 3200 – 3700K)',
'exif-lightsource-17'  => 'Luz estándar A',
'exif-lightsource-18'  => 'Luz estándar B',
'exif-lightsource-19'  => 'Luz estándar C',
'exif-lightsource-24'  => "Bombeta de tungsteno d'estudeo ISO",
'exif-lightsource-255' => 'Atra fuent de luz',

# Flash modes
'exif-flash-fired-0'    => 'No se disparó o flash',
'exif-flash-fired-1'    => 'Flash disparato',
'exif-flash-return-0'   => "no bi ha función de detección d'o retorno d'a luz estroboscopica",
'exif-flash-return-2'   => 'no se detectó retorno de luz estroboscopica',
'exif-flash-return-3'   => 'luz estroboscopica detectata',
'exif-flash-mode-1'     => 'disparo de flash forzato',
'exif-flash-mode-2'     => 'supresión de flash forzato',
'exif-flash-mode-3'     => 'modo automatico',
'exif-flash-function-1' => 'Modo sin de flash',
'exif-flash-redeye-1'   => 'modo de reducción de uellos royos',

'exif-focalplaneresolutionunit-2' => 'pulzadas',

'exif-sensingmethod-1' => 'No definito',
'exif-sensingmethod-2' => "Sensor d'aria de color d'un chip",
'exif-sensingmethod-3' => "Sensor d'aria de color de dos chips",
'exif-sensingmethod-4' => "Sensor d'aria de color de tres chips",
'exif-sensingmethod-5' => "Sensor d'aria de color seqüencial",
'exif-sensingmethod-7' => 'Sensor trilinial',
'exif-sensingmethod-8' => 'Sensor linial de color seqüencial',

'exif-scenetype-1' => 'Una imachen fotiata dreitament',

'exif-customrendered-0' => 'Proceso normal',
'exif-customrendered-1' => 'Proceso presonalizato',

'exif-exposuremode-0' => 'Exposición automatica',
'exif-exposuremode-1' => 'Exposición manual',
'exif-exposuremode-2' => 'Bracketting automatico',

'exif-whitebalance-0' => 'Balance automatico de blancos',
'exif-whitebalance-1' => 'Balance manual de blancos',

'exif-scenecapturetype-0' => 'Estándar',
'exif-scenecapturetype-1' => 'Anvista (horizontal)',
'exif-scenecapturetype-2' => 'Retrato (vertical)',
'exif-scenecapturetype-3' => 'Escena de nueits',

'exif-gaincontrol-0' => 'Garra',
'exif-gaincontrol-1' => 'Ganancia baixa ta valuras altas (low gain up)',
'exif-gaincontrol-2' => 'Ganancia alta ta valuras altas (high gain up)',
'exif-gaincontrol-3' => 'Ganancia baixa ta valuras baixas (low gain down)',
'exif-gaincontrol-4' => 'Ganancia alta ta baluras baixas (high gain down)',

'exif-contrast-0' => 'Normal',
'exif-contrast-1' => 'Suau',
'exif-contrast-2' => 'Fuerte',

'exif-saturation-0' => 'Normal',
'exif-saturation-1' => 'Baixa saturación',
'exif-saturation-2' => 'Alta saturación',

'exif-sharpness-0' => 'Normal',
'exif-sharpness-1' => 'Suau',
'exif-sharpness-2' => 'Fuerte',

'exif-subjectdistancerange-0' => 'Esconoixita',
'exif-subjectdistancerange-1' => 'Macro',
'exif-subjectdistancerange-2' => 'Vista amanada',
'exif-subjectdistancerange-3' => 'Vista leixana',

# Pseudotags used for GPSLatitudeRef and GPSDestLatitudeRef
'exif-gpslatitude-n' => 'Latitut norte',
'exif-gpslatitude-s' => 'Latitut sud',

# Pseudotags used for GPSLongitudeRef and GPSDestLongitudeRef
'exif-gpslongitude-e' => 'Lonchitut este',
'exif-gpslongitude-w' => 'Lonchitut ueste',

'exif-gpsstatus-a' => "S'está fendo a mida",
'exif-gpsstatus-v' => 'Interoperabilitat de mesura',

'exif-gpsmeasuremode-2' => 'Mesura bidimensional',
'exif-gpsmeasuremode-3' => 'Mesura tridimensional',

# Pseudotags used for GPSSpeedRef
'exif-gpsspeed-k' => 'Kilometros por hora',
'exif-gpsspeed-m' => 'Millas por hora',
'exif-gpsspeed-n' => 'Nugos',

# Pseudotags used for GPSTrackRef, GPSImgDirectionRef and GPSDestBearingRef
'exif-gpsdirection-t' => 'Endrecera reyal',
'exif-gpsdirection-m' => 'Endrecera magnetica',

# External editor support
'edit-externally'      => 'Editar iste fichero fendo servir una aplicación externa',
'edit-externally-help' => '(Ta más información, leiga as [http://www.mediawiki.org/wiki/Manual:External_editors instruccions de configuración])',

# 'all' in various places, this might be different for inflected languages
'recentchangesall' => 'totz',
'imagelistall'     => 'todas',
'watchlistall2'    => 'totz',
'namespacesall'    => 'totz',
'monthsall'        => 'totz',
'limitall'         => 'Totz',

# E-mail address confirmation
'confirmemail'              => 'Confirmar adreza de correu-e',
'confirmemail_noemail'      => "No tiene una adreza de correu-e conforme en as suyas [[Special:Preferences|preferencias d'usuario]].",
'confirmemail_text'         => "{{SITENAME}} requiere que confirme a suya adreza de correu-e antis de poder usar as funcions de correu-e. Punche o botón de baxo ta ninviar un mensache de confirmación t'a suya adreza. O mensache incluirá un vinclo con un codigo. Escriba-lo ta confirmar que a suya adreza ye conforme.",
'confirmemail_pending'      => "Ya se le ha ninviato un codigo de confirmación; si creyó una cuenta fa poco tiempo, puede que s'estime más d'aguardar bells menutos ta veyer si le plega antes de pedir un nuevo codigo.",
'confirmemail_send'         => 'Ninviar un codigo de confirmación.',
'confirmemail_sent'         => "S'ha ninviato un correu de confirmación.",
'confirmemail_oncreate'     => "S'ha ninviato un codigo de confirmación t'a suya adreza de correu-e.
Iste codigo no ye necesario ta dentrar, pero amenistará escribir-lo antis d'activar qualsiquier función d'o wiki basata en o correu electronico.",
'confirmemail_sendfailed'   => "{{SITENAME}} no ha puesto ninviar-le o mensache de confirmación. Por favor, comprebe que no bi haiga carácters no conformes en l'adreza de correu electronico indicata.

O programa retornó o siguient codigo d'error: $1",
'confirmemail_invalid'      => 'O codigo de confirmación no ye conforme. Regular que o codigo sía circumducito.',
'confirmemail_needlogin'    => 'Amenistar $1 ta confirmar a suya adreza de correu-e.',
'confirmemail_success'      => 'A suya adreza de correu-e ya ye confirmata. Agora puede [[Special:UserLogin|dentrar]] en o wiki y gronxiar-se-ie.',
'confirmemail_loggedin'     => 'A suya adreza de correu-e ya ye confirmata.',
'confirmemail_error'        => 'Bella cosa falló en alzar a suya confirmación.',
'confirmemail_subject'      => "confirmación de l'adreza de correu-e de {{SITENAME}}",
'confirmemail_body'         => 'Belún, probablement vusté mesmo, ha rechistrato una cuenta "$2" con ista adreza de correu-e en {{SITENAME}} dende l\'adreza IP $1.

Ta confirmar que ista cuenta reyalment le perteneixe y activar as funcions de correu-e en {{SITENAME}}, ubra iste vinclo en o suyo navegador:

$3

Si a cuenta *no* ye suya, siga iste atro vinclo ta anular a confirmación d\'adreza de correu-e:

$5

Iste codigo de confirmación circumducirá en $4.',
'confirmemail_body_changed' => 'Belún, probablement vusté mesmo, dende l\'adreza IP $1, ha cambiato l\'adreza de correu-e d\'a cuenta "$2" ta ista adreza en {{SITENAME}}.

Ta confirmar que ista cuenta reyalment le perteneix y ta reactivar as funcions de correu-e en {{SITENAME}}, ubra iste vinclo en o suyo navegador:

$3

Si a cuenta *no* ye suya, siga iste atro vinclo ta anular a confirmación d\'adreza de correu-e:

$5

Iste codigo de confirmación circumducirá en $4.',
'confirmemail_invalidated'  => "Anular a confirmación d'adreza de correu-e",
'invalidateemail'           => 'Anular a confirmación de correu-e',

# Scary transclusion
'scarytranscludedisabled' => "[S'ha desactivato a transclusión interwiki]",
'scarytranscludefailed'   => "[Ha fallato a recuperación d'a plantilla ta $1]",
'scarytranscludetoolong'  => '[O URL ye masiau largo]',

# Trackbacks
'trackbackbox'      => 'Retrovinclos (trackbacks) ta ista pachina:<br />
$1',
'trackbackremove'   => '([$1 Borrar])',
'trackbacklink'     => 'Retrovinclo (Trackback)',
'trackbackdeleteok' => "O retrovinclo (trackback) s'ha borrato correctament.",

# Delete conflict
'deletedwhileediting' => "Pare cuenta: Ista pachina s'ha borrato dimpués de que vusté prencipiase a editar!",
'confirmrecreate'     => "L'usuario [[User:$1|$1]] ([[User talk:$1|descusión]]) ha borrato iste articlo dimpués que vusté prencipase a editar-lo, y ha dato a siguient razón:
: ''$2''
Por favor, confirme que reyalment deseya tornar a creyar l'articlo.",
'recreate'            => 'Tornar a creyar',

# action=purge
'confirm_purge_button' => 'Confirmar',
'confirm-purge-top'    => "Limpiar a caché d'ista pachina?",
'confirm-purge-bottom' => 'En porgar una pachina, se limpia a memoria caché y afuerza que amaneixca a versión más actual.',

# Multipage image navigation
'imgmultipageprev' => '← pachina anterior',
'imgmultipagenext' => 'pachina siguient →',
'imgmultigo'       => 'Ir-ie!',
'imgmultigoto'     => "Ir t'a pachina $1",

# Table pager
'ascending_abbrev'         => 'asc',
'descending_abbrev'        => 'desc',
'table_pager_next'         => 'Pachina siguient',
'table_pager_prev'         => 'Pachina anterior',
'table_pager_first'        => 'Primera pachina',
'table_pager_last'         => 'Zaguer pachina',
'table_pager_limit'        => 'Amostrar $1 elementos por pachina',
'table_pager_limit_label'  => 'Ítems por pachina:',
'table_pager_limit_submit' => 'Ir-ie',
'table_pager_empty'        => 'No bi ha garra resultau',

# Auto-summaries
'autosumm-blank'   => "S'ha blanquiato a pachina",
'autosumm-replace' => 'O conteniu s\'ha cambiato por "$1"',
'autoredircomment' => 'Reendrezando ta [[$1]]',
'autosumm-new'     => "Pachina creyada con '$1'",

# Live preview
'livepreview-loading' => 'Cargando…',
'livepreview-ready'   => 'Cargando… ya!',
'livepreview-failed'  => "A previsualización a l'inte falló!
Prebe con a previsualización normal.",
'livepreview-error'   => 'No s\'ha puesto connectar: $1 "$2". Prebe con l\'anvista previa normal.',

# Friendlier slave lag warnings
'lag-warn-normal' => "Talment no s'amuestren en ista lista as edicions feitas en {{PLURAL:$1|o zaguer segundo|os zaguers $1 segundos}}.",
'lag-warn-high'   => "Por o retardo d'o servidor d'a base de datos, talment no s'amuestren en ista lista as edicions feitas en {{PLURAL:$1|o zaguer segundo|os zaguers $1 segundos}}.",

# Watchlist editor
'watchlistedit-numitems'       => 'A suya lista de seguimiento tiene {{PLURAL:$1|una pachina |$1 pachinas}}, sin contar-ie as pachinas de descusión.',
'watchlistedit-noitems'        => 'A suya lista de seguimiento ye bueda.',
'watchlistedit-normal-title'   => 'Editar a lista de seguimiento',
'watchlistedit-normal-legend'  => "Borrar títols d'a lista de seguimiento",
'watchlistedit-normal-explain' => "Contino s'amuestran os títols de pachinas d'a suya lista de seguimiento.
Ta sacar-ne una pachina, marque o quatrón que ye a o canto d'o suyo títol, y punche con o ratet en \"{{int:Watchlistedit-normal-submit}}\".
Tamién puede [[Special:Watchlist/raw|editar dreitament a lista]].",
'watchlistedit-normal-submit'  => 'Borrar pachinas',
'watchlistedit-normal-done'    => "{{PLURAL:$1|S'ha borrato 1 pachina|s'han borrato $1 pachinas}} d'a suya lista de seguimiento:",
'watchlistedit-raw-title'      => 'Editar a lista de seguimiento en formato texto',
'watchlistedit-raw-legend'     => 'Editar a lista de seguimiento en formato texto',
'watchlistedit-raw-explain'    => "Contino s'amuestran os títols d'as pachinas d'a suya lista de seguimiento. Puede editar ista lista adhibiendo u borrando líneas d'a lista; una pachina por linia.
Quan remate, punche \"{{int:Watchlistedit-raw-submit}}\".
Tamién puede fer servir o [[Special:Watchlist/edit|editor estándar]].",
'watchlistedit-raw-titles'     => 'Pachinas:',
'watchlistedit-raw-submit'     => 'Esviellar lista de seguimiento',
'watchlistedit-raw-done'       => "S'ha esviellato a suya lista de seguimiento.",
'watchlistedit-raw-added'      => "{{PLURAL:$1|S'ha esviellato una pachina|S'ha esviellato $1 pachinas}}:",
'watchlistedit-raw-removed'    => "{{PLURAL:$1|S'ha borrato una pachina|S'ha borrato $1 pachinas}}:",

# Watchlist editing tools
'watchlisttools-view' => 'Amostrar cambeos',
'watchlisttools-edit' => 'Veyer y editar a lista de seguimiento',
'watchlisttools-raw'  => 'Editar a lista de seguimiento en formato texto',

# Core parser functions
'unknown_extension_tag' => 'Etiqueta d\'estensión "$1" esconoixita',
'duplicate-defaultsort' => "Pare cuenta: A clau d'ordenación por defecto «$2» anula l'anterior clau d'ordenación por defecto «$1».",

# Special:Version
'version'                          => 'Versión',
'version-extensions'               => 'Estensions instalatas',
'version-specialpages'             => 'Pachinas especials',
'version-parserhooks'              => "Grifios d'o parser (parser hooks)",
'version-variables'                => 'Variables',
'version-other'                    => 'Atros',
'version-mediahandlers'            => 'Maneyador de fichers multimedia',
'version-hooks'                    => 'Grifios (Hooks)',
'version-extension-functions'      => "Funcions d'a estensión",
'version-parser-extensiontags'     => "Etiquetas d'estensión d'o parseyador",
'version-parser-function-hooks'    => "Grifios d'as funcions d'o parseyador",
'version-skin-extension-functions' => "Funcions d'estensión de l'aparencia (Skin)",
'version-hook-name'                => "Nombre d'o grifio",
'version-hook-subscribedby'        => 'Suscrito por',
'version-version'                  => '(Versión $1)',
'version-license'                  => 'Licencia',
'version-poweredby-credits'        => "Iste wiki funciona gracias a '''[http://www.mediawiki.org/ MediaWiki]''', copyright © 2001-$1 $2.",
'version-poweredby-others'         => 'atros',
'version-license-info'             => "MediaWiki ye software libre, puet redistribuyir-lo y/u modificar-lo baixo os terminos d'a Licencia Publica Cheneral GNU publicada por a Free Software Foundation, ya siga d'a suya versión 2 u (a la suya esleción) qualsiquier versión posterior. 

MediaWiki se distribuye con l'asperanza d'estar d'utilidat, pero SIN GARRA GUARANCIA; nian a guarancia implicita de COMERCIALIZACIÓN u ADEQUACIÓN TA UNA FINALIDAT DETERMINADA. En trobará más detalles en a Licencia Publica General GNU.

Con iste programa ha d'haber recibiu [{{SERVER}}{{SCRIPTPATH}}/COPYING una copia d'a Licencia Publica Cheneral GNU]; si no ye asinas, endrece-se a la Free Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA u bien [http://www.gnu.org/licenses/old-licenses/gpl-2.0.html la leiga en linia].",
'version-software'                 => 'Software instalato',
'version-software-product'         => 'Producto',
'version-software-version'         => 'Versión',

# Special:FilePath
'filepath'         => "Camín d'o fichero",
'filepath-page'    => 'Fichero:',
'filepath-submit'  => 'Ir-ie',
'filepath-summary' => "Ista pachina especial le retorna o camín completo d'un fichero.
As imachens s'amuestran en resolución completa, a resta de fichers fan encetar dreitament os suyos programas asociatos.

Escriba o nombre d'o fichero sin o prefixo \"{{ns:file}}:\".",

# Special:FileDuplicateSearch
'fileduplicatesearch'          => 'Mirar fichers duplicatos',
'fileduplicatesearch-summary'  => 'Mirar archivos duplicatos basatos en a suya valura hash.

Escriba o nombre d\'o fichero sin o prefixo "{{ns:file}}:".',
'fileduplicatesearch-legend'   => 'Mirar duplicatos',
'fileduplicatesearch-filename' => "Nombre d'o fichero:",
'fileduplicatesearch-submit'   => 'Mirar',
'fileduplicatesearch-info'     => "$1 × $2 pixels<br />Grandaria d'o fichero: $3<br />tipo MIME: $4",
'fileduplicatesearch-result-1' => 'O fichero "$1" no en tiene de duplicaus identicos.',
'fileduplicatesearch-result-n' => 'O fichero "$1" tiene {{PLURAL:$2|1 duplicau identico|$2 duplicaus identicos}}.',

# Special:SpecialPages
'specialpages'                   => 'Pachinas especials',
'specialpages-note'              => '----
* Pachinas especials normals.
* <strong class="mw-specialpagerestricted">Pachinas especials restrinchitas.</strong>',
'specialpages-group-maintenance' => 'Informes de mantenimiento',
'specialpages-group-other'       => 'Atras pachinas especials',
'specialpages-group-login'       => 'Inicio de sesión / rechistro',
'specialpages-group-changes'     => 'Zaguers cambios y rechistros',
'specialpages-group-media'       => 'Informes de fichers multimedias y cargas',
'specialpages-group-users'       => 'Usuarios y dreitos',
'specialpages-group-highuse'     => 'Pachinas con muito uso',
'specialpages-group-pages'       => 'Listas de pachinas',
'specialpages-group-pagetools'   => 'Ferramientas de pachinas',
'specialpages-group-wiki'        => 'Datos sobre a wiki y ferramientas',
'specialpages-group-redirects'   => 'Reendrezando as pachinas especials',
'specialpages-group-spam'        => 'Ferramientas de spam',

# Special:BlankPage
'blankpage'              => 'Pachina en blanco',
'intentionallyblankpage' => "Esta pachina s'ha deixato en blanco aldredes y se fa servir ta fer prebatinas, ezt.",

# External image whitelist
'external_image_whitelist' => "  #Deixe ista linia sin cambiar-la<pre>
#Meta debaixo fragmentos d'esprisions regulars (nomás a parte que be entre //)
#Se mirará si istas concuerdan con os URLs d'imáchens externas (hotlinked)
#As que concorden s'amostrarán como imáchens, en as que no, nomás s'amostrará un vinclo t'a imachen
#As ringleras que prencipian por «#» se consideran comentarios
#Tot isto ye insensible a las mayusclas/minusclas

#Meta totz os fragmentos de regex dencima d'ista ringlera. No faiga cambeos en ista linia</pre>",

# Special:Tags
'tags'                    => 'Cambior as etiquetas emplegadas',
'tag-filter'              => 'Filtrar as [[Special:Tags|etiquetas]]:',
'tag-filter-submit'       => 'Filtrar',
'tags-title'              => 'Etiquetas',
'tags-intro'              => 'Ista pachina amuestra as etiquetas con que o software puet sinyalar una edición, y o suyo significau.',
'tags-tag'                => "Nombre d'a etiqueta",
'tags-display-header'     => 'Aparencia en as listas de cambeos',
'tags-description-header' => "Descripción completa d'o significau",
'tags-hitcount-header'    => 'Cambeos etiquetatos',
'tags-edit'               => 'editar',
'tags-hitcount'           => '$1 {{PLURAL:$1|cambeo|cambeos}}',

# Special:ComparePages
'comparepages'     => 'Contimparar pachinas',
'compare-selector' => "Contimparar as versions d'as pachinas",
'compare-page1'    => 'Pachina 1',
'compare-page2'    => 'Pachina 2',
'compare-rev1'     => 'Versión 1',
'compare-rev2'     => 'Versión 2',
'compare-submit'   => 'Contimparar',

# Database error messages
'dberr-header'      => 'Iste wiki tiene un problema',
'dberr-problems'    => 'Lo sentimos. Iste sitio ye experimentando dificultatz tecnicas.',
'dberr-again'       => 'Mire de recargar en bells menutos.',
'dberr-info'        => "(No s'ha puesto contactar con o servidor d'a base de datos: $1)",
'dberr-usegoogle'   => 'Entremistanto puet preba a mirar a traviés de Google.',
'dberr-outofdate'   => "Pare cuenta que o suyo endice d'o nuestro conteniu puet que no siga esviellau.",
'dberr-cachederror' => "A siguient pachina ye una pachina alzada d'a pachina solicitada, y podría no estar actualizada.",

# HTML forms
'htmlform-invalid-input'       => "Bi ha problemas con belún d'os datos que ha escrito",
'htmlform-select-badoption'    => 'A valura especificada no ye una opción conforme.',
'htmlform-int-invalid'         => 'A valura que especificó no ye un entero.',
'htmlform-float-invalid'       => 'A valura que ha especificato no ye un entero.',
'htmlform-int-toolow'          => "A valura que ha especificato ye por debaixo d'o menimo de $1",
'htmlform-int-toohigh'         => "A valura que ha especificato ye alto d'o maximo de $1",
'htmlform-required'            => 'Ista valura ye necesaria',
'htmlform-submit'              => 'Ninviar',
'htmlform-reset'               => 'Desfer cambios',
'htmlform-selectorother-other' => 'Atros',

# SQLite database support
'sqlite-has-fts' => '$1, con soporte de busca de texto integro',
'sqlite-no-fts'  => '$1, sin soporte de busca de texto integro',

);
