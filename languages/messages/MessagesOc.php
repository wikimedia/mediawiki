<?php
/** Occitan (Occitan)
 *
 * @addtogroup Language
 *
 * @author Cedric31
 * @author Nike
 * @author Горан Анђелковић
 * @author Spacebirdy
 * @author SPQRobin
 * @author Siebrand
 * @author לערי ריינהארט
 * @author ChrisPtDe
 * @author Jon Harald Søby
 */

$skinNames = array(
	'standard'    => 'Estandard',
	'nostalgia'   => 'Nostalgia',
	'cologneblue' => 'Colonha Blau',
	'monobook'    => 'Monobook',
	'myskin'      => 'Mon interfàcia',
	'chick'       => 'Poleton',
	'simple'      => 'Simple',
	'modern'      => 'Modèrn',
);

$bookstoreList = array(
	'Amazon.fr' => 'http://www.amazon.fr/exec/obidos/ISBN=$1'
);

$namespaceNames = array(
	NS_MEDIA          => 'Mèdia',
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
	'Discutida_Imatge'     => NS_IMAGE_TALK,
	'Mediaòiqui'           => NS_MEDIAWIKI,
	'Discussion_Mediaòiqui' => NS_MEDIAWIKI_TALK,
	'Discutida_Mediaòiqui' => NS_MEDIAWIKI_TALK,
	'Discutida_Modèl'      => NS_TEMPLATE_TALK,
	'Discutida_Ajuda'      => NS_HELP_TALK,
	'Discutida_Categoria'  => NS_CATEGORY_TALK,
);

$specialPageAliases = array(
	'DoubleRedirects'           => array( 'Redireccions_doblas' ),
	'BrokenRedirects'           => array( 'Redireccions_copadas' ),
	'Disambiguations'           => array( 'Omonimia' ),
	'Userlogin'                 => array( 'Nom_d\'utilizaire' ),
	'Userlogout'                => array( 'Desconnexion' ),
	'CreateAccount'             => array( 'Crear_un_compte', 'CrearUnCompte', 'CrearCompte' ),
	'Preferences'               => array( 'Preferéncias' ),
	'Watchlist'                 => array( 'Lista_de_seguit' ),
	'Recentchanges'             => array( 'Darrièrs_cambiaments' ),
	'Upload'                    => array( 'Telecargament' ),
	'Imagelist'                 => array( 'Lista_dels_imatges' ),
	'Newimages'                 => array( 'Imatges_novèls' ),
	'Listusers'                 => array( 'Lista_dels_utilizaires' ),
	'Listgrouprights'           => array( 'Lista_dels_gropes_utilizaire', 'ListadelsGropesUtilizaire', 'ListaGropesUtilizaire', 'Tièra_dels_gropes_utilizaire', 'TièradelsGropesUtilizaire', 'TièraGropesUtilizaire' ),
	'Statistics'                => array( 'Estatisticas' ),
	'Randompage'                => array( 'Pagina_a_l\'azard' ),
	'Lonelypages'               => array( 'Paginas_orfanèlas' ),
	'Uncategorizedpages'        => array( 'Paginas_sens_categoria' ),
	'Uncategorizedcategories'   => array( 'Categorias_sens_categoria' ),
	'Uncategorizedimages'       => array( 'Imatges_sens_categoria' ),
	'Uncategorizedtemplates'    => array( 'Modèls_sens_categoria' ),
	'Unusedcategories'          => array( 'Categorias_inutilizadas' ),
	'Unusedimages'              => array( 'Imatges_inutilizats' ),
	'Wantedpages'               => array( 'Paginas_demandadas' ),
	'Wantedcategories'          => array( 'Categorias_demandadas' ),
	'Mostlinked'                => array( 'Imatges_mai_utilizats' ),
	'Mostlinkedcategories'      => array( 'Categorias__mai_utilizadas' ),
	'Mostlinkedtemplates'       => array( 'Modèls__mai_utilizats' ),
	'Mostcategories'            => array( 'Mai_de_categorias' ),
	'Mostimages'                => array( 'Mai_d\'imatges' ),
	'Mostrevisions'             => array( 'Mai_de_revisions' ),
	'Fewestrevisions'           => array( 'Mens_de_revisions' ),
	'Shortpages'                => array( 'Articles_brèus' ),
	'Longpages'                 => array( 'Articles_longs' ),
	'Newpages'                  => array( 'Paginas_novèlas' ),
	'Ancientpages'              => array( 'Paginas_ancianas' ),
	'Deadendpages'              => array( 'Paginas_sul_camin_d\'enlòc' ),
	'Protectedpages'            => array( 'Paginas_protegidas' ),
	'Protectedtitles'           => array( 'Títols_protegits', 'Títols_protegits', 'Títolsprotegits', 'Títolsprotegits' ),
	'Allpages'                  => array( 'Totas_las_paginas' ),
	'Prefixindex'               => array( 'Indèx' ),
	'Ipblocklist'               => array( 'Utilizaires_blocats' ),
	'Specialpages'              => array( 'Paginas_especialas' ),
	'Contributions'             => array( 'Contribucions' ),
	'Emailuser'                 => array( 'Corrièr_electronic', 'Email', 'Emèl', 'Emèil' ),
	'Confirmemail'              => array( 'Confirmar_lo_corrièr_electronic', 'Confirmarlocorrièrelectronic', 'ConfirmarCorrièrElectronic' ),
	'Whatlinkshere'             => array( 'Paginas_ligadas' ),
	'Recentchangeslinked'       => array( 'Seguit_dels_ligams' ),
	'Movepage'                  => array( 'Tornar_nomenar', 'Renomenatge' ),
	'Blockme'                   => array( 'Blocatz_me', 'Blocatzme' ),
	'Booksources'               => array( 'Obratge_de_referéncia', 'Obratges_de_referéncia' ),
	'Categories'                => array( 'Categorias' ),
	'Export'                    => array( 'Exportar', 'Exportacion' ),
	'Version'                   => array( 'Version' ),
	'Allmessages'               => array( 'Messatge_sistèma', 'Messatge_del_sistèma' ),
	'Log'                       => array( 'Jornal', 'Jornals' ),
	'Blockip'                   => array( 'Blocar', 'Blocatge' ),
	'Undelete'                  => array( 'Restablir', 'Restabliment' ),
	'Import'                    => array( 'Impòrt', 'Importacion' ),
	'Lockdb'                    => array( 'Varrolhar_la_banca' ),
	'Unlockdb'                  => array( 'Desvarrolhar_la_banca' ),
	'Userrights'                => array( 'Dreches', 'Permission' ),
	'MIMEsearch'                => array( 'Recèrca_MIME' ),
	'FileDuplicateSearch'       => array( 'Recèrca_fichièr_en_doble', 'RecèrcaFichièrEnDoble' ),
	'Unwatchedpages'            => array( 'Paginas_pas_seguidas' ),
	'Listredirects'             => array( 'Lista_de_las_redireccions', 'Listadelasredireccions', 'Lista_dels_redirects', 'Listadelsredirects', 'Lista_redireccions', 'Listaredireccions', 'Lista_redirects', 'Listaredirects' ),
	'Revisiondelete'            => array( 'Versions_suprimidas' ),
	'Unusedtemplates'           => array( 'Modèls_inutilizats', 'Modèlsinutilizats', 'Models_inutilizats', 'Modelsinutilizats', 'Modèls_pas_utilizats', 'Modèlspasutilizats', 'Models_pas_utilizats', 'Modelspasutilizats' ),
	'Randomredirect'            => array( 'Redireccion_a_l\'azard', 'Redirect_a_l\'azard' ),
	'Mypage'                    => array( 'Ma_pagina', 'Mapagina' ),
	'Mytalk'                    => array( 'Mas_discussions', 'Masdiscussions' ),
	'Mycontributions'           => array( 'Mas_contribucions', 'Mascontribucions' ),
	'Listadmins'                => array( 'Lista_dels_administrators', 'Listadelsadministrators', 'Lista_dels_admins', 'Listadelsadmins', 'Lista_admins', 'Listaadmins' ),
	'Listbots'                  => array( 'Lista_dels_Bòts', 'ListadelsBòts' ),
	'Popularpages'              => array( 'Paginas_mai_visitadas', 'Paginas_las_mai_visitadas', 'Paginasmaivisitadas' ),
	'Search'                    => array( 'Recèrca', 'Recercar', 'Cercar' ),
	'Resetpass'                 => array( 'Reïnicializacion_del_senhal', 'Reinicializaciondelsenhal' ),
	'Withoutinterwiki'          => array( 'Sens_interwiki', 'Sensinterwiki', 'Sens_interwikis', 'Sensinterwikis' ),
	'MergeHistory'              => array( 'Fusionar_l\'istoric', 'Fusionarlistoric' ),
	'Filepath'                  => array( 'Camin_del_Fichièr', 'CamindelFichièr', 'CaminFichièr' ),
	'Invalidateemail'           => array( 'Invalidar_Corrièr_electronic', 'InvalidarCorrièrElectronic' ),
);

$magicWords = array(
	'redirect'            => array( '0', '#REDIRECT', '#REDIRECCION' ),
	'notoc'               => array( '0', '__NOTOC__', '__CAPDETAULA__' ),
	'nogallery'           => array( '0', '__NOGALLERY__', '__CAPDEGALARIÁ__' ),
	'forcetoc'            => array( '0', '__FORCETOC__', '__FORÇARTAULA__' ),
	'toc'                 => array( '0', '__TOC__', '__TAULA__' ),
	'noeditsection'       => array( '0', '__NOEDITSECTION__', '__SECCIONNONEDITABLA__' ),
	'currentmonth'        => array( '1', 'CURRENTMONTH', 'MESCORRENT' ),
	'currentmonthname'    => array( '1', 'CURRENTMONTHNAME', 'NOMMESCORRENT' ),
	'currentday'          => array( '1', 'CURRENTDAY', 'JORNCORRENT' ),
	'currentday2'         => array( '1', 'CURRENTDAY2', 'JORNCORRENT2' ),
	'currentdayname'      => array( '1', 'CURRENTDAYNAME', 'NOMJORNCORRENT' ),
	'currentyear'         => array( '1', 'CURRENTYEAR', 'ANNADACORRENTA' ),
	'currenttime'         => array( '1', 'CURRENTTIME', 'DATACORRENTA' ),
	'currenthour'         => array( '1', 'CURRENTHOUR', 'ORACORRENTA' ),
	'numberofpages'       => array( '1', 'NUMBEROFPAGES', 'NOMBREPAGINAS' ),
	'numberofarticles'    => array( '1', 'NUMBEROFARTICLES', 'NOMBREARTICLES' ),
	'numberoffiles'       => array( '1', 'NUMBEROFFILES', 'NOMBREFICHIÈRS' ),
	'numberofusers'       => array( '1', 'NUMBEROFUSERS', 'NOMBREUTILIZAIRES' ),
	'numberofedits'       => array( '1', 'NUMBEROFEDITS', 'NOMBREEDICIONS' ),
	'pagename'            => array( '1', 'PAGENAME', 'NOMPAGINA' ),
	'namespace'           => array( '1', 'NAMESPACE', 'ESPACINOMENATGE' ),
	'talkspace'           => array( '1', 'TALKSPACE', 'ESPACIDISCUSSION' ),
	'img_right'           => array( '1', 'right', 'drecha', 'dreta' ),
	'img_left'            => array( '1', 'left', 'esquèrra', 'senèstra' ),
	'img_framed'          => array( '1', 'framed', 'enframed', 'frame', 'quadre' ),
	'img_frameless'       => array( '1', 'frameless', 'sens_quadre' ),
	'img_border'          => array( '1', 'border', 'bordadura' ),
	'server'              => array( '0', 'SERVER', 'SERVEIRE' ),
	'servername'          => array( '0', 'SERVERNAME', 'NOMSERVEIRE' ),
	'scriptpath'          => array( '0', 'SCRIPTPATH', 'CAMINESCRIPT' ),
	'grammar'             => array( '0', 'GRAMMAR:', 'GRAMATICA:' ),
	'currentweek'         => array( '1', 'CURRENTWEEK', 'SETMANACORRENTA' ),
	'revisionid'          => array( '1', 'REVISIONID', 'NUMÈROVERSION' ),
	'revisionday'         => array( '1', 'REVISIONDAY', 'DATAVERSION' ),
	'revisionday2'        => array( '1', 'REVISIONDAY2', 'DATAVERSION2' ),
	'revisionmonth'       => array( '1', 'REVISIONMONTH', 'MESREVISION' ),
	'revisionyear'        => array( '1', 'REVISIONYEAR', 'ANNADAREVISION' ),
	'revisiontimestamp'   => array( '1', 'REVISIONTIMESTAMP', 'ORAREVISION' ),
	'plural'              => array( '0', 'PLURAL:' ),
	'raw'                 => array( '0', 'RAW:', 'LINHA:' ),
	'displaytitle'        => array( '1', 'DISPLAYTITLE', 'AFICHARTÍTOL' ),
	'newsectionlink'      => array( '1', '__NEWSECTIONLINK__', '__LIGAMSECCIONNOVÈLA__' ),
	'currentversion'      => array( '1', 'CURRENTVERSION', 'VERSIONACTUALA' ),
	'currenttimestamp'    => array( '1', 'CURRENTTIMESTAMP', 'ORAACTUALA' ),
	'localtimestamp'      => array( '1', 'LOCALTIMESTAMP', 'ORALOCALA' ),
	'language'            => array( '0', '#LANGUAGE:', '#LENGA:' ),
	'numberofadmins'      => array( '1', 'NUMBEROFADMINS', 'NOMBREADMINS' ),
	'formatnum'           => array( '0', 'FORMATNUM', 'FORMATNOMBRE' ),
	'defaultsort'         => array( '1', 'DEFAULTSORT:', 'DEFAULTSORTKEY:', 'DEFAULTCATEGORYSORT:', 'ORDENA:' ),
	'filepath'            => array( '0', 'FILEPATH:', 'CAMIN:' ),
	'tag'                 => array( '0', 'tag', 'balisa' ),
	'hiddencat'           => array( '1', '__HIDDENCAT__', '__CATAMAGADA__' ),
	'pagesincategory'     => array( '1', 'PAGESINCATEGORY', 'PAGESINCAT', 'PAGINASDINSCAT' ),
);

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

$linkTrail = "/^([a-zàâçéèêîôû]+)(.*)\$/sDu";

$messages = array(
# User preference toggles
'tog-underline'               => 'Soslinhar los ligams :',
'tog-highlightbroken'         => 'Los ligams suls subjèctes non creats aparéisson en roge',
'tog-justify'                 => 'Paragrafs justificats',
'tog-hideminor'               => 'Amagar los <i>Darrièrs cambiaments</i> menors',
'tog-extendwatchlist'         => 'Lista de seguit melhorada',
'tog-usenewrc'                => 'Darrièrs cambiaments melhorats<br /> (pas per totes los navigaires)',
'tog-numberheadings'          => 'Numerotacion automatica dels títols',
'tog-showtoolbar'             => "Mostrar la barra de menut d'edicion",
'tog-editondblclick'          => 'Editar las paginas amb un doble clic (JavaScript)',
'tog-editsection'             => 'Editar una seccion via los ligams [editar]',
'tog-editsectiononrightclick' => 'Editar una seccion en clicant a drecha<br /> sul títol de la seccion',
'tog-showtoc'                 => "Afichar l'ensenhador<br /> (pels articles de mai de 3 seccions)",
'tog-rememberpassword'        => 'Se remembrar de mon senhal (cookie)',
'tog-editwidth'               => "La fenèstra d'edicion s'aficha en plena largor",
'tog-watchcreations'          => 'Apondre las paginas que suprimissi de ma lista de seguit',
'tog-watchdefault'            => 'Seguir los articles que crei o modifiqui',
'tog-watchmoves'              => 'Apondre las paginas que tòrni nomenar a ma lista de seguit',
'tog-watchdeletion'           => 'Apondre las paginas que suprimissi de ma lista de seguit',
'tog-minordefault'            => 'Mas modificacions son consideradas<br /> coma menoras per defaut',
'tog-previewontop'            => "Mostrar la previsualizacion<br />al dessús de la bóstia d'edicion",
'tog-previewonfirst'          => 'Mostrar la previsualizacion al moment de la primièra edicion',
'tog-nocache'                 => "Desactivar l'amagatal de paginas",
'tog-enotifwatchlistpages'    => 'Autorizar lo mandadís de corrièr electronic quand una pagina de vòstra lista de seguit es modificada',
'tog-enotifusertalkpages'     => "Desiri recebre un corrièr electronic quand ma pagina d'utilizaire es modificada.",
'tog-enotifminoredits'        => "Mandatz-me un corrièr electronic quitament per d'edicions menoras de las paginas",
'tog-enotifrevealaddr'        => 'Afichatz mon adreça electronica dins la notificacion dels corrièrs electronics',
'tog-shownumberswatching'     => "Afichar lo nombre d'utilizaires que seguisson aquesta pagina",
'tog-fancysig'                => 'Signatura bruta (sens ligam automatic)',
'tog-externaleditor'          => 'Utilizar un editor extèrn per defaut',
'tog-externaldiff'            => 'Utilizar un comparator extèrn per defaut',
'tog-showjumplinks'           => 'Activar los ligams « navigacion » e « recèrca » en naut de pagina (aparéncias Myskin e autres)',
'tog-uselivepreview'          => 'Utilizar la vista rapida (JavaScript) (Experimental)',
'tog-forceeditsummary'        => "M'avertir quand ai pas modificat lo contengut de la bóstia de resumit.",
'tog-watchlisthideown'        => 'Amagar mas pròprias modificacions dins la lista de seguit',
'tog-watchlisthidebots'       => 'Amagar los cambiaments faches pels bòts dins la lista de seguit',
'tog-watchlisthideminor'      => 'Amagar las modificacions menoras dins la lista de seguit',
'tog-nolangconversion'        => 'Desactivar la conversion de las variantas de lenga',
'tog-ccmeonemails'            => 'Mandatz-me una còpia dels corrièrs electronics que mandi als autres utilizaires',
'tog-diffonly'                => 'Mostrar pas lo contengut de las paginas jos las difs',
'tog-showhiddencats'          => 'Afichar las categorias amagadas',

'underline-always'  => 'Totjorn',
'underline-never'   => 'Pas jamai',
'underline-default' => 'Segon lo navigaire',

'skinpreview' => '(Previsualizar)',

# Dates
'sunday'        => 'dimenge',
'monday'        => 'diluns',
'tuesday'       => 'dimars',
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
'aug'           => "d'agost",
'sep'           => 'de set',
'oct'           => "d'oct",
'nov'           => 'de nov',
'dec'           => 'de dec',

# Categories related messages
'categories'                     => '{{PLURAL:$1|Categoria|Categorias}} de la pagina',
'categoriespagetext'             => 'Las categorias seguentas contenen de paginas o de mèdias.',
'special-categories-sort-count'  => 'triada per compte',
'special-categories-sort-abc'    => 'triada alfabetica',
'pagecategories'                 => '{{PLURAL:$1|Categoria|Categorias}} de la pagina',
'category_header'                => 'Articles dins la categoria "$1"',
'subcategories'                  => 'Soscategorias',
'category-media-header'          => 'Fichièrs multimèdia dins la categoria "$1"',
'category-empty'                 => "''Actualament, aquesta categoria conten pas cap d'articles o de mèdia.''",
'hidden-categories'              => '{{PLURAL:$1|Categoria amagada|Categorias amagadas}}',
'hidden-category-category'       => 'Categorias amagadas', # Name of the category where hidden categories will be listed
'category-subcat-count'          => '{{PLURAL:$2|Aquesta categoria dispausa pas que de la soscategoria seguenta.|Aquesta categoria dispausa de {{PLURAL:$1|soscategoria|$1 soscategorias}}, sus una soma de $2.}}',
'category-subcat-count-limited'  => 'Aquesta categoria dispausa {{PLURAL:$1|d’una soscategoria|de $1 soscategorias}}.',
'category-article-count'         => '{{PLURAL:$2|Aquesta categoria conten unicament la pagina seguenta.|{{PLURAL:$1|La pagina seguenta figura|Las $1 paginas seguentas figuran}} dins aquesta categoria, sus una soma de $2.}}',
'category-article-count-limited' => '{{PLURAL:$1|La pagina seguenta figura|Las $1 paginas seguentas figuran}} dins la presenta categoria.',
'category-file-count'            => '{{PLURAL:$2|Aquesta categoria conten unicament lo fichièr seguent.|{{PLURAL:$1|Lo fichièr seguent figura|los $1 fichièrs seguents figuran}} dins aquesta categoria, sus una soma de $2.}}',
'category-file-count-limited'    => '{{PLURAL:$1|Lo fichièr seguent figura|Los $1 fichièrs seguents figuran}} dins la presenta categoria.',
'listingcontinuesabbrev'         => '(seguida)',

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
'and'            => 'e',

# Metadata in edit box
'metadata_help' => 'Metadonadas:',

'errorpagetitle'    => 'Error',
'returnto'          => 'Tornar a la pagina $1.',
'tagline'           => 'Un article de {{SITENAME}}.',
'help'              => 'Ajuda',
'search'            => 'Recercar',
'searchbutton'      => 'Recercar',
'go'                => 'Legir',
'searcharticle'     => 'Consultar',
'history'           => 'Istoric',
'history_short'     => 'Istoric',
'updatedmarker'     => 'modificat dempuèi ma darrièra visita',
'info_short'        => 'Entresenhas',
'printableversion'  => 'Version imprimibla',
'permalink'         => 'Ligam permanent',
'print'             => 'Imprimir',
'edit'              => 'Editar',
'create'            => 'Crear',
'editthispage'      => 'Modificar aquesta pagina',
'create-this-page'  => 'Crear aquesta pagina',
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
'postcomment'       => 'Apondre un comentari',
'articlepage'       => "Vejatz l'article",
'talk'              => 'Discussion',
'views'             => 'Afichatges',
'toolbox'           => "Bóstia d'espleches",
'userpage'          => "Pagina d'utilizaire",
'projectpage'       => 'Pagina meta',
'imagepage'         => 'Pagina del mèdia',
'mediawikipage'     => 'Vejatz la pagina del messatge',
'templatepage'      => 'Vejatz la pagina del modèl',
'viewhelppage'      => "Vejatz la pagina d'ajuda",
'categorypage'      => 'Vejatz la pagina de las categorias',
'viewtalkpage'      => 'Pagina de discussion',
'otherlanguages'    => 'Autras lengas',
'redirectedfrom'    => '(Redirigit dempuèi $1)',
'redirectpagesub'   => 'Pagina de redireccion',
'lastmodifiedat'    => "Darrièr cambiament d'aquesta pagina : $2, $1.", # $1 date, $2 time
'viewcount'         => 'Aquesta pagina es estada consultada {{plural:$1|un còp|$1 còps}}.',
'protectedpage'     => 'Pagina protegida',
'jumpto'            => 'Anar a :',
'jumptonavigation'  => 'navigacion',
'jumptosearch'      => 'Recercar',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'            => 'A prepaus de {{SITENAME}}',
'aboutpage'            => 'Project:A prepaus',
'bugreports'           => "Rapòrt d'errors",
'bugreportspage'       => "Project:Rapòrt d'errors",
'copyright'            => 'Lo contengut es disponible segon los tèrmes de la licéncia $1.',
'copyrightpagename'    => 'Licéncia {{SITENAME}}',
'copyrightpage'        => '{{ns:project}}:Copyright',
'currentevents'        => 'Actualitats',
'currentevents-url'    => 'Project:Actualitats',
'disclaimers'          => 'Avertiments',
'disclaimerpage'       => 'Project:Avertiments generals',
'edithelp'             => 'Ajuda',
'edithelppage'         => 'Help:Cossí editar una pagina',
'faq'                  => 'FdeQ',
'faqpage'              => 'Project:FAQ',
'helppage'             => 'Help:Ajuda',
'mainpage'             => 'Acuèlh',
'mainpage-description' => 'Acuèlh',
'policy-url'           => 'Project:Policy',
'portal'               => 'Comunautat',
'portal-url'           => 'Project:Acuèlh',
'privacy'              => 'Politica de confidencialitat',
'privacypage'          => 'Project:Confidencialitat',
'sitesupport'          => 'Participar en fasent un don',
'sitesupport-url'      => 'Project:Fasètz un don',

'badaccess'        => 'Error de permission',
'badaccess-group0' => 'Avètz pas los dreches sufisents per realizar l’accion que demandatz.',
'badaccess-group1' => "L'accion qu'ensajatz de realizar es accessibla pas qu'als utilizaires del grop $1.",
'badaccess-group2' => "L'accion qu'ensajatz de realizar es accessibla pas qu'als utilizaires dels gropes $1.",
'badaccess-groups' => "L'accion qu'ensajatz de realizar es accessibla pas qu'als utilizaires dels gropes $1.",

'versionrequired'     => 'Version $1 de MediaWiki necessària',
'versionrequiredtext' => 'La version $1 de MediaWiki es necessària per utilizar aquesta pagina. Consultatz [[Special:Version|la pagina de las versions]]',

'ok'                      => "D'acòrdi",
'retrievedfrom'           => 'Recuperada de "$1"',
'youhavenewmessages'      => 'Avètz $1 ($2).',
'newmessageslink'         => 'messatge(s) novèl(s)',
'newmessagesdifflink'     => 'darrièr cambiament',
'youhavenewmessagesmulti' => 'Avètz de messatges novèls sus $1',
'editsection'             => 'modificar',
'editold'                 => 'modificar',
'viewsourceold'           => 'veire la font',
'editsectionhint'         => 'Modificar la seccion : $1',
'toc'                     => 'Somari',
'showtoc'                 => 'mostrar',
'hidetoc'                 => 'amagar',
'thisisdeleted'           => 'Afichar o restablir $1?',
'viewdeleted'             => 'Veire $1?',
'restorelink'             => '{{PLURAL:$1|una edicion escafada|$1 edicions escafadas}}',
'feedlinks'               => 'Flus :',
'feed-invalid'            => 'Tipe de flus invalid.',
'feed-unavailable'        => 'Los fluses de sindicacion son pas disponibles sus {{SITENAME}}',
'site-rss-feed'           => 'Flus RSS de $1',
'site-atom-feed'          => 'Flus Atom de $1',
'page-rss-feed'           => 'Flus RSS de "$1"',
'page-atom-feed'          => 'Flus Atom de "$1"',
'red-link-title'          => '$1 (pas encara redigit)',

# Short words for each namespace, by default used in the namespace tab in monobook
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
'error'                => 'Error',
'databaseerror'        => 'Error de la banca de donadas',
'dberrortext'          => 'Error de sintaxi dins la banca de donadas. La darrièra requèsta tractada per la banca de donadas èra :
<blockquote><tt>$1</tt></blockquote>
dempuèi la foncion "<tt>$2</tt>".
MySQL a renviat l\'error "<tt>$3: $4</tt>".',
'dberrortextcl'        => 'Una requèsta a la banca de donadas compòrta una error de sintaxi.
La darrièra requèsta mandada èra :
« $1 »
efectuada per la foncion « $2 ».
MySQL a retornat l\'error "$3: $4"',
'noconnect'            => 'O planhèm ! En seguida a de problèmas tecnics, es impossible de se connectar a la banca de donadas pel moment. <br />
$1',
'nodb'                 => 'Seleccion impossibla de la banca de donadas $1',
'cachederror'          => 'Aquò es una còpia de la pagina demandada e pòt pas èsser mesa a jorn',
'laggedslavemode'      => 'Atencion, aquesta pagina pòt conténer pas totes los darrièrs cambiaments efectuats',
'readonly'             => 'Mesas a jorn blocadas sus la banca de donadas',
'enterlockreason'      => 'Indicatz la rason del blocatge, e mai una estimacion de la durada de blocatge',
'readonlytext'         => "Los ajusts e mesas a jorn sus la banca de donadas {{SITENAME}} son actualament blocats, probablament per permetre la mantenença de la banca, aprèp aquò, tot dintrarà dins l'òrdre. Vaquí la rason per laquala l'administrator a blocat la banca :
<p>$1",
'missingarticle'       => 'La banca de donadas a pas pogut trobar lo tèxt d\'una pagina existenta, que lo títol es "$1".
Es pas una error de la banca de donadas, mas mai probablament un bog del logicial {{SITENAME}}.
Raportatz aquesta error a un administrator, en li indicant l\'adreça de la pagina fautiva.',
'missingarticle-rev'   => '(revision#: $1)',
'missingarticle-diff'  => '(Diff: $1, $2)',
'readonly_lag'         => 'La banca de donadas es estada automaticament clavada pendent que los serveires segondaris ratrapan lor retard sul serveire principal.',
'internalerror'        => 'Error intèrna',
'internalerror_info'   => 'Error intèrna: $1',
'filecopyerror'        => 'Impossible de copiar "$1" vèrs "$2".',
'filerenameerror'      => 'Impossible de tornar nomenar "$1" en "$2".',
'filedeleteerror'      => 'Impossible de suprimir "$1".',
'directorycreateerror' => 'Impossible de crear lo dorsièr « $1 ».',
'filenotfound'         => 'Fichièr "$1" introbable.',
'fileexistserror'      => 'Impossible d’escriure dins lo dorsièr « $1 » : lo fiquièr existís',
'unexpected'           => 'Valor imprevista : "$1"="$2".',
'formerror'            => 'Error: Impossible de sometre lo formulari',
'badarticleerror'      => 'Aquesta accion pòt pas èsser efectuada sus aquesta pagina.',
'cannotdelete'         => "Impossible de suprimir la pagina o l'imatge indicat.",
'badtitle'             => 'Títol marrit',
'badtitletext'         => 'Lo títol de la pagina demandada es invalid, void o lo ligam interlenga es invalid',
'perfdisabled'         => 'O planhèm ! Aquesta foncionalitat es temporàriament desactivada
perque alentís la banca de donadas a un punt tal que degun
pòt pas mai utilizar lo wiki.',
'perfcached'           => 'Aquò es una version en amagatal e es benlèu pas a jorn.',
'perfcachedts'         => 'Las donadas seguentas son en amagatal, son doncas pas obligatòriament a jorn. La darrièra actualizacion data del $1.',
'querypage-no-updates' => 'Las mesas a jorn per aquesta pagina son actualamnt desactivadas. Las donadas çaijós son pas mesas a jorn.',
'wrong_wfQuery_params' => 'Paramètres incorrèctes sus wfQuery()<br />
Foncion : $1<br />
Requèsta : $2',
'viewsource'           => 'Vejatz lo tèxt font',
'viewsourcefor'        => 'per $1',
'actionthrottled'      => 'Accion limitada',
'actionthrottledtext'  => "Per luchar contra lo spam, l’utilizacion d'aquesta accion es limitada a un cèrt nombre de còps dins una sosta pro corta. S'avèra qu'avètz depassat aquesta limita. Ensajatz tornamai dins qualques minutas.",
'protectedpagetext'    => 'Aquesta pagina es estada protegida per empachar sa modificacion.',
'viewsourcetext'       => 'Podètz veire e copiar son còde font :',
'protectedinterface'   => 'Aquesta pagina fornís de tèxt d’interfàcia pel logicial e es protegida per evitar los abuses.',
'editinginterface'     => "'''Atencion :''' sètz a editar una pagina utilizada per crear lo tèxt de l’interfàcia del logicial. Los cambiaments se repercutaràn, segon lo contèxt, sus totas o cèrtas paginas visiblas pels autres utilizaires. Per las traduccions, vos convidam a utilizar lo projècte Mediawiki d'internacionalizacion dels messatges [http://translatewiki.net/wiki/Main_Page?setlang=oc Betawiki].",
'sqlhidden'            => '(Requèsta SQL amagada)',
'cascadeprotected'     => 'Aquesta pagina es actualament protegida perque es inclusa dins {{PLURAL:$1|la pagina seguenta|las paginas seguentas}}, , que son estadas protegidas amb l’opcion « proteccion en cascada » activada :
$2',
'namespaceprotected'   => "Avètz pas la permission de modificar las paginas de l’espaci de noms « '''$1''' ».",
'customcssjsprotected' => "Avètz pas la permission d'editar aquesta pagina perque conten de preferéncias d’autres utilizaires.",
'ns-specialprotected'  => 'Las paginas dins l’espaci de noms « {{ns:special}} » pòdon pas èsser modificadas',
'titleprotected'       => "Aqueste títol es estat protegit a la creacion per [[User:$1|$1]].
Lo motiu avançat es « ''$2'' ».",

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
'nav-login-createaccount'    => 'Identificacion',
'loginprompt'                => 'Vos cal activar los cookies per vos connectar a {{SITENAME}}.',
'userlogin'                  => 'Identificacion',
'logout'                     => 'Desconnexion',
'userlogout'                 => 'Desconnexion',
'notloggedin'                => 'Vos sètz pas identificat(ada)',
'nologin'                    => 'Avètz pas de compte ? $1.',
'nologinlink'                => 'Creatz un compte',
'createaccount'              => 'Crear un compte novèl',
'gotaccount'                 => 'Ja avètz un compte ? $1.',
'gotaccountlink'             => 'Identificatz-vos',
'createaccountmail'          => 'per corrièr electronic',
'badretype'                  => "Los senhals qu'avètz picats son pas identics.",
'userexists'                 => "Lo nom d'utilizaire qu'avètz picat es ja utilizat. Causissètz-ne un autre.",
'youremail'                  => 'Mon adreça electronica :',
'username'                   => 'Nom d’utilizaire :',
'uid'                        => 'Numèro d’utilizaire :',
'yourrealname'               => 'Nom vertadièr *',
'yourlanguage'               => "Lenga de l'interfàcia :",
'yourvariant'                => 'Varianta lingüistica :',
'yournick'                   => 'Mon escais (per las signaturas)',
'badsig'                     => 'Signatura bruta incorrècta, verificatz vòstras balisas HTML.',
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
Verificatz qu\'avètz plan ortografiat lo nom, o utilizatz lo formulari çaijós per crear un compte d\'utilizaire novèl.',
'nosuchusershort'            => 'I a pas de contributor amb lo nom « <nowiki>$1</nowiki> ». Verificatz l’ortografia.',
'nouserspecified'            => "Vos cal especificar vòstre nom d'utilizaire.",
'wrongpassword'              => 'Lo senhal es incorrècte. Ensajatz tornarmai.',
'wrongpasswordempty'         => 'Avètz pas entrat de senhal. Ensajatz tornarmai.',
'passwordtooshort'           => 'Vòstre senhal es tròp cort. Deu conténer almens $1 caractèrs.',
'mailmypassword'             => 'Mandatz-me un senhal novèl',
'passwordremindertitle'      => 'Senhal temporari novèl sus {{SITENAME}}',
'passwordremindertext'       => 'Qualqu\'un (probablament vos) que son adreça IP es $1 a demandat qu\'un senhal novèl vos siá mandat per vòstre accès a {{SITENAME}} ($4).
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
'acct_creation_throttle_hit' => "O planhèm, ja avètz $1 comptes creats. Ne podètz pas crear d'autres.",
'emailauthenticated'         => 'Vòstra adreça de corrièr electronic es estada autentificada lo $1.',
'emailnotauthenticated'      => 'Vòstra adreça de corrièr electronic es <strong>pas encara autentificada</strong>. Cap corrièr serà pas mandat per caduna de las foncions seguentas.',
'noemailprefs'               => "Fornissetz una adreça de corrièr electronic pel bon foncionament d'aquestas foncionalitats.",
'emailconfirmlink'           => 'Confirmatz vòstra adreça de corrièr electronic',
'invalidemailaddress'        => 'Aquesta adreça de corrièr electronic pòt pas èsser acceptada perque sembla aver un format invalid. Entratz una adreça valida o daissatz aqueste camp void.',
'accountcreated'             => 'Compte creat.',
'accountcreatedtext'         => "Lo compte d'utilizaire de $1 es estat creat.",
'createaccount-title'        => "Creacion d'un compte per {{SITENAME}}",
'createaccount-text'         => "Qualqu'un a creat un compte per vòstra adreça de corrièr electronic sus {{SITENAME}} ($4) intitolat « $2 », amb per senhal « $3 ». Deuriaz dobrir una sessilha e cambiar, tre ara, aqueste senhal.

Ignoratz aqueste messatge se aqueste compte es estat creat per error.",
'loginlanguagelabel'         => 'Lenga: $1',

# Password reset dialog
'resetpass'               => 'Remesa a zèro del senhal',
'resetpass_announce'      => 'Vos sètz enregistrat amb un senhal temporari mandat per corrièr electronic. Per acabar l’enregistrament, vos cal picar un senhal novèl aicí :',
'resetpass_text'          => '<!-- Ajust de tèxt aicí -->',
'resetpass_header'        => 'Remesa a zèro del senhal',
'resetpass_submit'        => 'Cambiar lo senhal e s’enregistrar',
'resetpass_success'       => 'Vòstre senhal es estat cambiat amb succès ! Enregistrament en cors...',
'resetpass_bad_temporary' => 'Senhal temporari invalid. Benlèu que ja avètz cambiat vòstre senhal amb succès, o demandat un senhal temporari novèl.',
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
'image_tip'       => 'Fichièr inserit',
'media_sample'    => 'Exemple.ogg',
'media_tip'       => 'Ligam vèrs lo fichièr',
'sig_tip'         => 'Vòstra signatura amb la data',
'hr_tip'          => "Linha orizontala (n'abusetz pas)",

# Edit pages
'summary'                           => 'Resumit',
'subject'                           => 'Subjècte/títol',
'minoredit'                         => 'Cambiament menor.',
'watchthis'                         => 'Seguir aqueste article',
'savearticle'                       => 'Salvagardar',
'preview'                           => 'Previsualizar',
'showpreview'                       => 'Previsualizacion',
'showlivepreview'                   => 'Previsualizacion',
'showdiff'                          => 'Cambiaments en cors',
'anoneditwarning'                   => "'''Atencion :''' sètz pas identificat(ada).
Vòstra adreça IP serà enregistrada dins l’istoric d'aquesta pagina.",
'missingsummary'                    => "'''Atencion :''' avètz pas modificat lo resumit de vòstra modificacion. Se clicatz tornarmai sul boton « Salvagardar », la salvagarda serà facha sens avertiment novèl.",
'missingcommenttext'                => 'Mercé de metre un comentari çaijós.',
'missingcommentheader'              => "'''Rampèl :''' Avètz pas provesit de subjècte/títol per aqueste comentari. Se clicatz tornamai sus ''Salvagardar'', vòstra edicion serà enregistrada sens aquò.",
'summary-preview'                   => 'Previsualizacion del resumit',
'subject-preview'                   => 'Previsualizacion del subjècte/títol',
'blockedtitle'                      => 'Utilizaire blocat',
'blockedtext'                       => "<big>'''Vòstre compte d'utilizaire (o vòstra adreça IP) es estat blocat'''</big>

Lo blocatge es estat efectuat per $1 per la rason seguenta : ''$2''.

Podètz contactar $1 o un autre [[{{MediaWiki:Grouppage-sysop}}|administrator]] per ne discutir. Podètz pas utilizar la foncion « Mandar un corrièr electronic a aqueste utilizaire » que se una adreça de corrièr valida es especificada dins vòstras [[Special:Preferences|preferéncias]]. Vòstra adreça IP actuala es $3 e vòstre identificant de blocatge es #$5. Incluissètz aquesta adreça dins tota requèsta.
* Començament del blocatge : $8
* Expiracion del blocatge : $6
* Compte blocat : $7.",
'autoblockedtext'                   => 'Vòstra adreça IP es estada blocada automaticament perque es estada utilizada per un autre utilizaire, ele-meteis blocat per $1. La rason balhada es :

:\'\'$2\'\'

* Començament del blocatge : $8
* Expiracion del blocatge : $6

Podètz contactar $1 o un dels autres [[{{MediaWiki:Grouppage-sysop}}|administrators]] per discutir d\'aqueste blocatge.

Notatz que podètz pas utilizar la foncion "Mandar un messatge a aqueste utilizaire" a mens qu’aguessetz balhat una adreça e-mail valida dins vòstras [[Special:Preferences|preferéncias]].

Vòstre identificant de blocatge es $5. Precizatz-lo dins tota requèsta.',
'blockednoreason'                   => 'Cap de rason balhada',
'blockedoriginalsource'             => "Lo còde font de '''$1''' es indicat çaijós :",
'blockededitsource'                 => "Lo tèxt de '''vòstras edicions''' sus '''$1''' es afichat çaijós :",
'whitelistedittitle'                => 'Enregistrament necessari per modificar lo contengut',
'whitelistedittext'                 => 'Vos devètz $1 per editar las paginas.',
'whitelistreadtitle'                => 'Enregistrament necessari per legir lo contengut',
'whitelistreadtext'                 => 'Vos devètz [[Special:Userlogin|identificar]] per legir las paginas.',
'whitelistacctitle'                 => 'Vos es pas permés de crear un compte',
'whitelistacctext'                  => 'Per poder crear un compte dins aqueste Wiki, vos cal [[Special:Userlogin|identificar]] e aver las autorizacions apropriadas.',
'confirmedittitle'                  => "Confirmacion de l'adreça electronica demandada per editar",
'confirmedittext'                   => "Vos cal confirmar vòstra adreça electronica abans de modificar l'enciclopèdia. Picatz e validatz vòstra adreça electronica amb l'ajuda de la pagina [[Special:Preferences|preferéncias]].",
'nosuchsectiontitle'                => 'Seccion mancanta',
'nosuchsectiontext'                 => "Avètz ensajat de modificar una seccion qu’existís pas. Coma i a pas de seccion $1, i a pas d'endrech ont salvagardar vòstras modificacions.",
'loginreqtitle'                     => 'Enregistrament necessari',
'loginreqlink'                      => 'connectar',
'loginreqpagetext'                  => 'Vos devètz $1 per veire las autras paginas.',
'accmailtitle'                      => 'Senhal mandat.',
'accmailtext'                       => "Lo senhal de '$1' es estat mandat a $2.",
'newarticle'                        => '(Novèl)',
'newarticletext'                    => 'Picatz aicí lo tèxt de vòstre article.',
'anontalkpagetext'                  => "---- ''Aquò es la pagina de discussion per un utilizaire anonim qu'a pas encara creat un compte o que l'utiliza pas. Per aqueste rason, devèm utilizar l'adreça IP numerica per l'identificar. Una adreça d'aqueste tipe pòt èsser partejada entre mantuns utilizaires. Se sètz un utilizaire anonim e se constatatz que de comentaris que vos concernisson pas vos son estats adreçats, vos podètz [[Special:Userlogin|crear un compte o connectar]] per evitar tota confusion venenta.",
'noarticletext'                     => "Pel moment, i a pas cap de tèxt sus aquesta pagina ; podètz [[Special:Search/{{PAGENAME}}|aviar una recèrca sul títol d'aquesta pagina]] o [{{fullurl:{{NAMESPACE}}:{{FULLPAGENAME}}|action=edit}} modificar aquesta pagina].",
'userpage-userdoesnotexist'         => "Lo compte d'utilizaire « $1 » es pas enregistrat. Indicatz se volètz crear o editar aquesta pagina.",
'clearyourcache'                    => 'Nòta : Aprèp aver salvagardat, vos cal forçar lo recargament de la pagina per veire los cambiaments : Mozilla / Konqueror / Firefox : ctrl-shift-r, IE / Opera : ctrl-f5, Safari : cmd-r.',
'usercssjsyoucanpreview'            => "'''Astúcia :''' utilizatz lo boton '''Previsualizacion''' per testar vòstre fuèlh novèl css/js abans de l'enregistrar.<br />Per importar vòstre fuèlh monobook dempuèi una URL, utilizatz ''@import url (VÒSTRA_URL_AICÍ&action=raw&ctype=text/css)''",
'usercsspreview'                    => "'''Remembratz-vos que sètz a previsualizar vòstre pròpri fuèlh CSS e qu’encara es pas estat enregistrat !'''",
'userjspreview'                     => "'''Remembrat-vos que sètz a visualizar o testar vòstre còde JavaScript e qu’es pas encara estat enregistrat !'''",
'userinvalidcssjstitle'             => "'''Atencion :''' existís pas d'estil « $1 ». Remembratz-vos que las paginas personalas amb extensions .css e .js utilizan de títols en minusculas aprèp lo nom d'utilizaire e la barra de fraccion /.<br />Atal, {{ns:user}}:Foo/monobook.css es valid, alara que {{ns:user}}:Foo/Monobook.css serà una fuèlha d'estil invalida.",
'updated'                           => '(Mes a jorn)',
'note'                              => '<strong>Nòta :</strong>',
'previewnote'                       => "Atencion, aqueste tèxt es pas qu'una previsualizacion e es pas encara estat salvagardat !",
'previewconflict'                   => "La previsualizacion mòstra lo tèxt d'aquesta pagina tal coma apareisserà un còp salvagardat.",
'session_fail_preview'              => '<strong>O planhèm ! Podèm pas enregistrar vòstra modificacion a causa d’una pèrda d’informacions concernent vòstra sesilha. Ensajatz tornarmai. Se aquò capita pas encara, desconnectatz-vos, puèi connectatz-vos tornamai.</strong>',
'session_fail_preview_html'         => "<strong>O planhèm ! Podèm pas enregistrar vòstra modificacion a causa d’una pèrda d’informacions concernent vòstra sesilha.</strong>

''L’HTML brut essent activat sus {{SITENAME}}, la previsualizacion es estada amagada per prevenir un atac per JavaScript.''

<strong>Se la temptativa de modificacion èra legitima, ensajatz encara. Se aquò capita pas un còp de mai, desconnectatz-vos, puèi connectatz-vos tornamai.</strong>",
'token_suffix_mismatch'             => '<strong>Vòstra edicion es pas estada acceptada perque vòstre navigaire a mesclat los caractèrs de ponctuacion dins l’identificant d’edicion. L’edicion es estada regetada per empachar la corrupcion del tèxt de l’article. Aqueste problèma se produtz quand utilizatz un proxy anonim amb problèma.</strong>',
'editing'                           => 'modificacion de $1',
'editingsection'                    => 'Modificacion de $1 (seccion)',
'editingcomment'                    => 'Modificacion de $1 (comentari)',
'editconflict'                      => 'Conflicte de modificacion : $1',
'explainconflict'                   => "Aqueste pagina es estada salvagardada aprèp qu'avètz començat de la modificar.
La zòna d'edicion superiora conten lo tèxt tal coma es enregistrat actualament dins la banca de donadas.
Vòstras modificacions apareisson dins la zòna d'edicion inferiora.
Anatz dever aportar vòstras modificacions al tèxt existent.
'''Sol''' lo tèxt de la zòna superiora serà salvagardat.",
'yourtext'                          => 'Vòstre tèxt',
'storedversion'                     => 'Version enregistrada',
'nonunicodebrowser'                 => '<strong>Atencion : Vòstre navigaire supòrta pas l’unicode. Una solucion temporària es estada trobada per vos permetre de modificar un article en tota seguretat : los caractèrs non-ASCII apareisseràn dins vòstra bóstia de modificacion en tant que còdes exadecimals. Deuriatz utilizar un navigaire mai recent.</strong>',
'editingold'                        => "<strong>Atencion : sètz a modificar una version obsolèta d'aquesta pagina. Se salvagardatz, totas las modificacions efectuadas dempuèi aquesta version seràn perdudas.</strong>",
'yourdiff'                          => 'Diferéncias',
'copyrightwarning'                  => "Totas las contribucions a {{SITENAME}} son consideradas coma publicadas jols tèrmes de la $2 (vejatz $1 per mai de detalhs). Se desiratz pas que vòstres escriches sián modificats e distribuits a volontat, mercés de los sometre pas aicí.<br /> Nos prometètz tanben qu'avètz escrich aquò vos-meteis, o que l’avètz copiat d’una font provenent del domeni public, o d’una ressorsa liura.<strong>UTILIZETZ PAS DE TRABALHS JOS COPYRIGHT SENS AUTORIZACION EXPRÈSSA !</strong>",
'copyrightwarning2'                 => "Totas las contribucions a {{SITENAME}} pòdon èsser modificadas o suprimidas per d’autres utilizaires. Se desiratz pas que vòstres escriches sián modificats e distribuits a volontat, mercés de los sometre pas aicí.<br /> Tanben nos prometètz qu'avètz escrich aquò vos-meteis, o que l’avètz copiat d’una font provenent del domeni public, o d’una ressorsa liura. (vejatz $1 per mai de detalhs). <strong>UTILIZETZ PAS DE TRABALHS JOS COPYRIGHT SENS AUTORIZACION EXPRÈSSA !</strong>",
'longpagewarning'                   => "<strong>AVERTIMENT : aquesta pagina a una longor de $1 ko. De delà de 32 ko, es preferible per d'unes navigaires de devesir aquesta pagina en seccions mai pichonas.</strong>",
'longpageerror'                     => "<strong>ERROR: Lo tèxt qu'avètz mandat es de $1 Ko, e despassa doncas lo limit autorizat dels $2 Ko. Lo tèxt pòt pas èsser salvagardat.</strong>",
'readonlywarning'                   => "<strong>AVERTIMENT : '''aquesta pagina es <span style=\"color:red\">protegida</span> <u>temporàriament</u> e <u>automaticament</u> per mantenença.'''<br />Doncas, i poiretz pas salvagardar vòstras modificacions ara. Podètz copiar lo tèxt dins un fichièr e lo salvagardar per mai tard.</strong>",
'protectedpagewarning'              => "<strong>ATENCION : Aquesta pagina es protegida. Sols los utilizaires amb l'estatut d'administrator la pòdon modificar. Asseguratz-vos que seguissètz las directivas concernent las paginas protegidas.</strong>",
'semiprotectedpagewarning'          => "'''Nòta:''' Aquesta pagina es estada blocada, pòt èsser editada pas que pels utiliaires enregistats.",
'cascadeprotectedwarning'           => '<strong>ATENCION : Aquesta pagina es estada protegida de biais que sols los [[{{MediaWiki:Grouppage-sysop}}|administrators]] pòscan l’editar. Aquesta proteccion es estada facha perque aquesta pagina es inclusa dins {{PLURAL:$1|una pagina protegida|de paginas protegidas}} amb la « proteccion en cascada » activada.</strong>',
'titleprotectedwarning'             => '<strong>ATENCION : Aquesta pagina es estada protegida de tal biais que sols cèrts utilizaires pòscan la crear.</strong>',
'templatesused'                     => 'Modèls utilizats sus aquesta pagina :',
'templatesusedpreview'              => 'Modèls utilizats dins aquesta previsualizacion :',
'templatesusedsection'              => 'Modèls utilizats dins aquesta seccion :',
'template-protected'                => '(protegit)',
'template-semiprotected'            => '(semiprotegit)',
'hiddencategories'                  => "{{PLURAL:$1|Categoria amagada|Categorias amagadas}} qu'aquesta pagina ne fa partida :",
'edittools'                         => '<!-- Tot tèxt picat aicí serà afichat jos las boitas de modificacion o d’impòrt de fichièr. -->',
'nocreatetitle'                     => 'Creacion de pagina limitada',
'nocreatetext'                      => '{{SITENAME}} a restrencha la possibilitat de crear de paginas novèlas.
Podètz tonar en rèire e modificar una pagina existenta, [[Special:Userlogin|vos connectar o crear un compte]].',
'nocreate-loggedin'                 => 'Avètz pas la permission de crear de paginas novèlas sus aqueste wiki.',
'permissionserrors'                 => 'Error de permissions',
'permissionserrorstext'             => 'Avètz pas la permission d’efectuar l’operacion demandada per {{PLURAL:$1|la rason seguenta|las rasons seguentas}} :',
'recreate-deleted-warn'             => "'''Atencion : sètz a tornar crear una pagina qu'es estada suprimida precedentament.'''

Demandatz-vos se es vertadièrament apropriat de la tornar crear en vos referissent al jornal de las supressions afichat çaijós :",
'expensive-parserfunction-warning'  => 'Atencion : Aquesta pagina conten tròp d’apèls dispendioses de foncions parsaires.

Ne deurià aver mens de $2 sul nombre actual $1.',
'expensive-parserfunction-category' => 'Paginas amb tròp d’apèls dispendioses de foncions parsaires',

# "Undo" feature
'undo-success' => 'Aquesta modificacion es estada desfacha. Confirmatz, e salvagardatz los cambiaments çaijós.',
'undo-failure' => 'Aquesta modificacion a pas pogut èsser desfacha a causa de conflictes amb de modificacions intermediàrias.',
'undo-norev'   => 'La modificacion a pas pogut èsser desfacha perque siá es inexistenta siá es estada suprimida.',
'undo-summary' => 'Anullacion de las modificacions $1 de [[Special:Contributions/$2|$2]] ([[User talk:$2|discutir]])',

# Account creation failure
'cantcreateaccounttitle' => 'Podètz pas crear de compte.',
'cantcreateaccount-text' => "La creacion de compte dempuèi aquesta adreça IP ('''$1''') es estada blocada per [[User:$3|$3]].
La rason donada per $3 èra ''$2''.",

# History pages
'viewpagelogs'        => "Vejatz lo jornal d'aquesta pagina",
'nohistory'           => "Existís pas d'istoric per aquesta pagina.",
'revnotfound'         => 'Version introbabla',
'revnotfoundtext'     => "La version precedenta d'aquesta pagina a pas pogut èsser retrobada. Verificatz l'URL qu'avètz utilizat per accedir a aquesta pagina.",
'currentrev'          => 'Version actuala',
'revisionasof'        => 'Version del $1',
'revision-info'       => 'Version del $1 per $2',
'previousrevision'    => '←Version precedenta',
'nextrevision'        => 'Version seguenta →',
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
'rev-deleted-event'           => '(entrada suprimida)',
'rev-deleted-text-permission' => '<div class="mw-warning plainlinks">
Aquesta version de la pagina es estada levada dels archius publics.
Pòt i aver de detalhs dins lo [{{fullurl:Special:Log/delete|page={{FULLPAGENAMEE}}}} jornal de las supressions].
</div>',
'rev-deleted-text-view'       => '<div class="mw-warning plainlinks"> Aquesta version de la pagina es estada levada dels archius publics. En tant qu’administrator d\'aqueste sit, la podètz visualizar ; i pòt aver de detahls dins lo [{{fullurl:Special:Log/delete|page={{PAGENAMEE}}}} jornal de las supressions]. </div>',
'rev-delundel'                => 'afichar/amagar',
'revisiondelete'              => 'Suprimir/Restablir de versions',
'revdelete-nooldid-title'     => 'Cibla per la revision invalida',
'revdelete-nooldid-text'      => "Avètz pas precisat la o las revision(s) cibla(s) per utilizar aquesta foncion, la revision cibla existís pas, o alara la revision cibla es la qu'es en cors.",
'revdelete-selected'          => '{{PLURAL:$2|Version seleccionada|Versions seleccionadas}} de [[:$1]] :',
'logdelete-selected'          => '{{PLURAL:$1|Eveniment de jornal seleccionat|Eveniments de jornal seleccionats}} :',
'revdelete-text'              => "Las versions suprimidas apareisseràn encara dins l’istoric de l’article, mas lor contengut textual serà inaccessible al public.

D’autres administrators sus {{SITENAME}} poiràn totjorn accedir al contengut amagat e lo restablir tornarmai a travèrs d'aquesta meteissa interfàcia, a mens qu’una restriccion suplementària siá mesa en plaça pels operators del sit.",
'revdelete-legend'            => 'Metre en plaça de restriccions de version :',
'revdelete-hide-text'         => 'Amagar lo tèxt de la version',
'revdelete-hide-name'         => 'Amagar l’accion e la cibla',
'revdelete-hide-comment'      => 'Amagar lo comentari de modificacion',
'revdelete-hide-user'         => 'Amagar lo pseudonim o l’adreça IP del contributor.',
'revdelete-hide-restricted'   => 'Aplicar aquestas restriccions als administrators e varrolhar aquesta interfàcia',
'revdelete-suppress'          => 'Suprimir las donadas dels administrators e dels autres',
'revdelete-hide-image'        => 'Amagar lo contengut del fichièr',
'revdelete-unsuppress'        => 'Levar las restriccions sus las versions restablidas',
'revdelete-log'               => 'Comentari pel jornal :',
'revdelete-submit'            => 'La visibilitat de la version es estada modificada per [[$1]]',
'revdelete-logentry'          => 'La visibilitat de la version es estada modificada per [[$1]]',
'logdelete-logentry'          => 'La visibilitat de l’eveniment es estada modificada per [[$1]]',
'revdelete-success'           => "'''Visibilitat de las versions cambiadas amb succès.'''",
'logdelete-success'           => "'''Visibilitat dels eveniments cambiada amb succès.'''",
'revdel-restore'              => 'Modificar la visibilitat',
'pagehist'                    => 'Istoric de la pagina',
'deletedhist'                 => 'Istoric de las supressions',
'revdelete-content'           => 'contengut',
'revdelete-summary'           => 'modificar lo somari',
'revdelete-uname'             => 'nom d’utilizaire',
'revdelete-restricted'        => 'aplicar las restriccions als administrators',
'revdelete-unrestricted'      => 'restriccions levadas pels administrators',
'revdelete-hid'               => 'amagar $1',
'revdelete-unhid'             => 'afichar $1',
'revdelete-log-message'       => '$1 per $2 {{PLURAL:$2|revision|revisions}}',
'logdelete-log-message'       => '$1 sus $2 {{PLURAL:$2|eveniment|eveniments}}',

# Suppression log
'suppressionlog'     => 'Jornal de las supressions',
'suppressionlogtext' => 'Çaijós, se tròba la tièra de las supressions e dels blocatges que comprenon las revisions amagadas als administrators. Vejatz [[Special:Ipblocklist|la lista dels blocatges de las IP]] per la lista dels fòrabandiments e dels blocatges operacionals.',

# History merging
'mergehistory'                     => "Fusion dels istorics d'una pagina",
'mergehistory-header'              => "Aquesta pagina vos permet de fusionar las revisions de l'istoric d'una pagina d'origina vèrs una novèla.
Asseguratz-vos qu'aqueste cambiament pòsca conservar la continuitat de l'istoric.",
'mergehistory-box'                 => 'Fusionar las versions de doas paginas :',
'mergehistory-from'                => "Pagina d'origina :",
'mergehistory-into'                => 'Pagina de destinacion :',
'mergehistory-list'                => 'Edicion dels istorics fusionables',
'mergehistory-merge'               => "Las versions seguentas de [[:$1]] pòdon èsser fusionadas amb [[:$2]]. Utilizatz lo boton ràdio de la colomna per fusionar unicament las versions creadas del començament fins a la data indicada. Notatz plan que l'utilizacion dels ligams de navigacion reïnicializarà la colomna.",
'mergehistory-go'                  => 'Veire las edicions fusionablas',
'mergehistory-submit'              => 'Fusionar las revisions',
'mergehistory-empty'               => 'Cap de revision pòt pas èsser fusionada.',
'mergehistory-success'             => '$3 {{PLURAL:$3|revision|revisions}} de [[:$1]] {{PLURAL:$3|fusionada|fusionadas}} amb succès amb [[:$2]].',
'mergehistory-fail'                => 'Impossible de procedir a la fusion dels istorics. Seleccionatz  tornamai la pagina e mai los paramètres de data.',
'mergehistory-no-source'           => "La pagina d'origina $1 existís pas.",
'mergehistory-no-destination'      => 'La pagina de destinacion $1 existís pas.',
'mergehistory-invalid-source'      => 'La pagina d’origina deu aver un títol valid.',
'mergehistory-invalid-destination' => 'La pagina de destinacion deu aver un títol valid.',
'mergehistory-autocomment'         => '[[:$1]] fusionat amb [[:$2]]',
'mergehistory-comment'             => '[[:$1]] fusionat amb [[:$2]] : $3',

# Merge log
'mergelog'           => 'Jornal de las fusions',
'pagemerge-logentry' => '[[$1]] fusionada amb [[$2]] (revisions fins al $3)',
'revertmerge'        => 'Separar',
'mergelogpagetext'   => "Vaquí, çaijós, la lista de las fusions las mai recentas de l'istoric d'una pagina amb una autra.",

# Diffs
'history-title'           => 'Istoric de las versions de « $1 »',
'difference'              => '(Diferéncias entre las versions)',
'lineno'                  => 'Linha $1 :',
'compareselectedversions' => 'Comparar las versions seleccionadas',
'editundo'                => 'desfar',
'diff-multi'              => '({{plural:$1|Una revision intermediària amagada|$1 revisions intermediàrias amagadas}})',

# Search results
'searchresults'             => 'Resultat de la recèrca',
'searchresulttext'          => "Per mai d'informacions sus la recèrca dins {{SITENAME}}, vejatz [[Projècte:Recèrca|Cercar dins {{SITENAME}}]].",
'searchsubtitle'            => 'Per la requèsta "[[:$1]]"',
'searchsubtitleinvalid'     => 'Per la requèsta "$1"',
'noexactmatch'              => 'Cap de pagina amb lo títol "$1" existís pas, ensajatz amb la recèrca complèta. Si que non, podètz [[:$1|crear aquesta pagina]]',
'noexactmatch-nocreate'     => "'''I a pas de pagina intitolada \"\$1\".'''",
'toomanymatches'            => 'Tròp d’occuréncias son estadas trobadas, sètz pregat de sometre una requèsta diferenta.',
'titlematches'              => 'Correspondéncias dins los títols',
'notitlematches'            => "Cap de títol d'article conten pas lo(s) mot(s) demandat(s)",
'textmatches'               => 'Correspondéncias dins los tèxtes',
'notextmatches'             => "Cap de tèxt d'article conten pas lo(s) mot(s) demandat(s)",
'prevn'                     => '$1 precedents',
'nextn'                     => '$1 seguents',
'viewprevnext'              => 'Veire ($1) ($2) ($3).',
'search-result-size'        => '$1 ({{PLURAL:$2|1 mot|$2 mots}})',
'search-result-score'       => 'Pertinéncia : $1%',
'search-redirect'           => '(redireccion vèrs $1)',
'search-section'            => '(seccion $1)',
'search-suggest'            => 'Avètz volgut dire : $1',
'search-interwiki-caption'  => 'Projèctes fraires',
'search-interwiki-default'  => '$1 resultats :',
'search-interwiki-more'     => '(mai)',
'search-mwsuggest-enabled'  => 'amb suggestions',
'search-mwsuggest-disabled' => 'sens suggestion',
'search-relatedarticle'     => 'Relatat',
'mwsuggest-disable'         => 'Desactivar las suggestions AJAX',
'searchrelated'             => 'relatat',
'searchall'                 => 'Totes',
'showingresults'            => "Afichatge {{PLURAL:$1|d''''1''' resultat|de '''$1''' resultats}} a partir del #'''$2'''.",
'showingresultsnum'         => "Afichatge {{PLURAL:$3|d''''1''' resultat|de '''$3''' resultats}} a partir del #'''$2'''.",
'showingresultstotal'       => "Visionament dels resultats çaijós '''$1 - $2''' de '''$3'''",
'nonefound'                 => '<strong>Nòta</strong>: l\'abséncia de resultat es sovent deguda a l\'emplec de tèrmes de recèrca tròp corrents, coma "a" o "de",
que son pas indexats, o a l\'emplec de mantun tèrme de recèrca (solas las paginas que
contenon totes los tèrmes apareisson dins los resultats).',
'powersearch'               => 'Recèrca avançada',
'powersearch-legend'        => 'Recèrca avançada',
'powersearchtext'           => 'Recercar dins los espacis :<br />
$1<br />
$2 Enclure las paginas de redireccions   Recercar $3 $9',
'search-external'           => 'Recèrca extèrna',
'searchdisabled'            => 'La recèrca sus {{SITENAME}} es desactivada.
En esperant la reactivacion, podètz efectuar una recèrca via Google.
Atencion, lor indexacion de contengut {{SITENAME}} benlèu es pas a jorn.',

# Preferences page
'preferences'              => 'Preferéncias',
'mypreferences'            => 'Mas preferéncias',
'prefs-edits'              => 'Nombre d’edicions :',
'prefsnologin'             => 'Vos sètz pas identificat(ada)',
'prefsnologintext'         => "Vos cal èsser [[Special:Userlogin|connectat(ada)]]
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
'prefs-personal'           => 'Entresenhas personalas',
'prefs-rc'                 => 'Darrièrs cambiaments',
'prefs-watchlist'          => 'Lista de seguit',
'prefs-watchlist-days'     => 'Nombre de jorns de mostrar dins la lista de seguit :',
'prefs-watchlist-edits'    => "Nombre de modificacions d'afichar dins la lista de seguit espandida :",
'prefs-misc'               => 'Preferéncias divèrsas',
'saveprefs'                => 'Enregistrar las preferéncias',
'resetprefs'               => 'Reïnicializar',
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
'stub-threshold'           => 'Limita superiora pels <a href="#" class="stub">ligams vèrs los esbòses</a> (octets) :',
'recentchangesdays'        => 'Jorns de mostrar dins los darrièrs cambiaments :',
'recentchangescount'       => 'Nombre de títols dins los darrièrs cambiaments',
'savedprefs'               => 'Las preferéncias son estadas salvagardadas.',
'timezonelegend'           => 'Fus orari',
'timezonetext'             => "¹Se precisatz pas de decalatge orari, es l'ora d'Euròpa de l'oèst que serà utilizada.",
'localtime'                => 'Ora locala',
'timezoneoffset'           => 'Decalatge orari',
'servertime'               => 'Ora del serveire',
'guesstimezone'            => 'Utilizar la valor del navigaire',
'allowemail'               => 'Autorizar lo mandadís de corrièr electronic venent d’autres utilizaires',
'defaultns'                => 'Per defaut, recercar dins aquestes espacis :',
'default'                  => 'defaut',
'files'                    => 'Fichièrs',

# User rights
'userrights'                       => "Gestion dels dreches d'utilizaire", # Not used as normal message but as header for the special page itself
'userrights-lookup-user'           => "Gestion dels dreches d'utilizaire",
'userrights-user-editname'         => 'Entrar un nom d’utilizaire :',
'editusergroup'                    => "Modificacion dels gropes d'utilizaires",
'editinguser'                      => "Cambiament dels dreches de l'utilizaire '''[[User:$1|$1]]''' ([[User talk:$1|{{int:talkpagelinktext}}]] | [[Special:Contributions/$1|{{int:contribslink}}]])",
'userrights-editusergroup'         => 'Modificar los gropes de l’utilizaire',
'saveusergroups'                   => "Salvagardar los gropes d'utilizaires",
'userrights-groupsmember'          => 'Membre de :',
'userrights-groupsremovable'       => 'Gropes suprimibles :',
'userrights-groupsavailable'       => 'Gropes disponibles :',
'userrights-groups-help'           => "Podètz modificar los gropes alsquals aparten aqueste utilizaire.
Una casa marcada significa que l'utilizaire se tròba dins aqueste grop.
Una casa pas marcada significa, al contrari, que s’i tròba pas.
Una * indica que podretz pas levar aqueste grop un còp que l'auretz apondut e vice-versa.",
'userrights-reason'                => 'Motiu del cambiament :',
'userrights-available-none'        => 'Podètz pas cambiar l’apartenéncia als diferents gropes.',
'userrights-available-add'         => "Podètz apondre d'utilizaires a {{PLURAL:$2|aqueste grop|aquestes gropes}} : $1.",
'userrights-available-remove'      => "Podètz levar d'utilizaires d'{{PLURAL:$2|aqueste grop|aquestes gropes}} : $1.",
'userrights-available-add-self'    => 'Vos podètz apondre vos-meteis dins {{PLURAL:$2|aqueste grop|aquestes gropes}} : $1.',
'userrights-available-remove-self' => "Vos podètz levar vos-meteis d'{{PLURAL:$2|aqueste grop|aquestes gropes}} : $1.",
'userrights-no-interwiki'          => "Sètz pas abilitat per modificar los dreches dels utilizaires sus d'autres wikis.",
'userrights-nodatabase'            => 'La banca de donadas « $1 » existís pas o es pas en local.',
'userrights-nologin'               => "Vos devètz [[Special:Userlogin|connectar]] amb un compte d'administrator per balhar los dreches d'utilizaire.",
'userrights-notallowed'            => "Vòstre compte es pas abilitat per balhar de dreches d'utilizaire.",
'userrights-changeable-col'        => 'Gropes que podètz cambiar',
'userrights-unchangeable-col'      => 'Gropes que podètz pas cambiar',

# Groups
'group'               => 'Grop :',
'group-user'          => 'Utilizaires',
'group-autoconfirmed' => 'Utilizaires enregistrats',
'group-bot'           => 'Bòts',
'group-sysop'         => 'Administrators',
'group-bureaucrat'    => 'Burocratas',
'group-suppress'      => 'Supervisors',
'group-all'           => '(totes)',

'group-user-member'          => 'Utilizaire',
'group-autoconfirmed-member' => 'Utilizaire enregistrat',
'group-bot-member'           => 'Bòt',
'group-sysop-member'         => 'Administrator',
'group-bureaucrat-member'    => 'Burocrata',
'group-suppress-member'      => 'Supervisaire',

'grouppage-user'          => '{{ns:project}}:Utilizaires',
'grouppage-autoconfirmed' => '{{ns:project}}:Utilizaires enregistrats',
'grouppage-bot'           => '{{ns:project}}:Bòts',
'grouppage-sysop'         => '{{ns:project}}:Administrators',
'grouppage-bureaucrat'    => '{{ns:project}}:Burocratas',
'grouppage-suppress'      => '{{ns:project}}:Supervisaire',

# Rights
'right-read'                 => 'Legir las paginas',
'right-edit'                 => 'Modificar las paginas',
'right-createpage'           => 'Crear de paginas (que son pas de paginas de discussion)',
'right-createtalk'           => 'Crear de paginas de discussion',
'right-createaccount'        => "Crear de comptes d'utilizaire novèls",
'right-minoredit'            => 'Marcar de cambiaments coma menors',
'right-move'                 => 'Tornar nomenar de paginas',
'right-suppressredirect'     => 'Crear pas de redireccion dempuèi la pagina anciana en renomenant la pagina',
'right-upload'               => 'Telecargar de fichièrs',
'right-reupload'             => 'Espotir un fichièr existent',
'right-reupload-own'         => 'Espotir un fichièr telecargat pel meteis utilizaire',
'right-reupload-shared'      => 'Espotir localament un fichièr present sus un depaus partejat',
'right-upload_by_url'        => 'Importar un fichièr dempuèi una adreça URL',
'right-purge'                => "Purgar l'amagatal de las paginas sens l'aver de confirmar",
'right-autoconfirmed'        => 'Modificar las paginas semiprotegidas',
'right-bot'                  => 'Èsser tractat coma un procediment automatizat',
'right-nominornewtalk'       => 'Desenclavar pas lo bendèl "Avètz de messatges novèls" al moment d\'un cambiament menor sus una pagina de discussion d\'un utilizaire',
'right-apihighlimits'        => "Utilizar los limits superiors de l'API",
'right-delete'               => 'Suprimir de paginas',
'right-bigdelete'            => "Suprimir de paginas amb d'istorics grands",
'right-deleterevision'       => "Suprimir e restablir una revision especifica d'una pagina",
'right-deletedhistory'       => 'Veire las entradas dels istorics suprimits mas sens lor tèxt',
'right-browsearchive'        => 'Recercar de paginas suprimidas',
'right-undelete'             => 'Restablir una pagina',
'right-hiderevision'         => 'Examinar e restablir las revisions amagadas als administrators',
'right-suppress'             => 'Veire los jornals privats',
'right-block'                => "Blocar d'autres utilizaires en escritura",
'right-blockemail'           => 'Empachar un utilizaire de mandar de corrièrs electronics',
'right-hideuser'             => 'Blocar un utilizaire en amagant son nom al public',
'right-ipblock-exempt'       => "Èsser pas afectat per las IP blocadas, los blocatges automatics e los blocatges de plajas d'IP",
'right-proxyunbannable'      => 'Èsser pas afectat pels blocatges automatics de serveires mandataris',
'right-protect'              => 'Modificar lo nivèl de proteccion de las paginas e modificar las paginas protegidas',
'right-editprotected'        => 'Modificar las paginas protegidas (sens proteccion en cascada)',
'right-editinterface'        => "Modificar l'interfàcia d'utilizaire",
'right-editusercssjs'        => "Modificar los fichièrs CSS e JS d'autres utilizaires",
'right-rollback'             => "Revocacion rapida del darrièr utilizaire qu'a modificat una pagina particulara",
'right-markbotedits'         => 'Marcar los cambiaments revocats coma de cambiaments que son estats fachs per de robòts',
'right-import'               => "Importar de paginas dempuèi d'autres wikis",
'right-importupload'         => 'Importar de paginas dempuèi un fichièr',
'right-patrol'               => 'Marcar de cambiaments coma verificats',
'right-autopatrol'           => 'Aver sos cambiaments marcats automaticament coma verificats',
'right-unwatchedpages'       => 'Veire la tièra de las paginas pas seguidas',
'right-trackback'            => 'Fusionar los istorics de las paginas',
'right-mergehistory'         => 'Fusionar los istorics de las paginas',
'right-userrights'           => "Modificar totes los dreches d'un utilizaire",
'right-userrights-interwiki' => "Modificar los dreches d'utilizaires que son sus un autre wiki",
'right-siteadmin'            => 'Varrolhar e desvarrolhar la banca de donadas',

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
'rcnote'                            => "Vaquí {{PLURAL:$1|lo darrièr cambiament|los '''$1''' darrièrs cambiaments}} dempuèi {{PLURAL:$2|lo darrièr jorn|los '''$2''' darrièrs jorns}}, determinat{{PLURAL:$1||s}} aqueste $3.",
'rcnotefrom'                        => "Vaquí los cambiamtns efectuats dempuèi lo '''$2''' ('''$1''' al maximom).",
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
'newpageletter'                     => 'N',
'boteditletter'                     => 'b',
'number_of_watching_users_pageview' => '[$1 {{PLURAL:$1|utilizaire seguent|utilizaires seguents}}]',
'rc_categories'                     => 'Limit de las categorias (separacion amb « | »)',
'rc_categories_any'                 => 'Totas',
'newsectionsummary'                 => '/* $1 */ seccion novèla',

# Recent changes linked
'recentchangeslinked'          => 'Seguit dels ligams',
'recentchangeslinked-title'    => 'Seguit dels ligams associats a "$1"',
'recentchangeslinked-noresult' => 'Cap de cambiament sus las paginas ligadas pendent lo periòde causit.',
'recentchangeslinked-summary'  => "Aquesta pagina especiala mòstra los darrièrs cambiaments sus las paginas que son ligadas. Las paginas de [[Special:Watchlist|vòstra tièra de seguit]] son '''en gras'''.",
'recentchangeslinked-page'     => 'Nom de la pagina :',
'recentchangeslinked-to'       => 'Afichar los cambiaments vèrs las paginas ligadas al luòc de la pagina donada',

# Upload
'upload'                      => 'Copiar sul serveire',
'uploadbtn'                   => 'Copiar un fichièr',
'reupload'                    => 'Copiar tornarmai',
'reuploaddesc'                => 'Anullar lo cargament e tornar al formulari.',
'uploadnologin'               => 'Vos sètz pas identificat(ada)',
'uploadnologintext'           => 'Vos cal èsser [[Special:Userlogin|connectat(ada)]]
per copiar de fichièrs sul serveire.',
'upload_directory_read_only'  => 'Lo serveire Web pòt escriure dins lo dorsièr cibla ($1).',
'uploaderror'                 => 'Error',
'uploadtext'                  => "Utilizatz lo formulari çaijós per mandar de fichièrs sul serveire.
Per veire o recercar d'imatges precedentament mandats, consultatz [[Special:Imagelist|la lista dels imatges]]. Las còpias e las supressions tanben son enregistradas dins lo [[Special:Log/upload|jornal dels uploads]].

Per inclure un imatge dins una pagina, utilizatz un ligam de la forma
'''<nowiki>[[</nowiki>{{ns:image}}<nowiki>:Fichièr.jpg]]</nowiki></b>,
'''<nowiki>[[</nowiki>{{ns:image}}<nowiki>:Fichièr.png|tèxt alternatiu]]</nowiki></b> o
'''<nowiki>[[</nowiki>{{ns:media}}<nowiki>:Fichièr.ogg]]</nowiki></b> per ligar dirèctament vèrs lo fichièr.",
'upload-permitted'            => 'Formats de fichièrs autorizats : $1.',
'upload-preferred'            => 'Formats de fichièrs preferits : $1.',
'upload-prohibited'           => 'Formats de fichièrs interdiches : $1.',
'uploadlog'                   => 'Jornals dels telecargaments (uploads)',
'uploadlogpage'               => 'Istoric de las importacions de fichièrs multimèdia',
'uploadlogpagetext'           => "Vaquí la lista dels darrièrs fichièrs copiats sul serveire.
L'ora indicada es la del serveire (UTC).",
'filename'                    => 'Nom',
'filedesc'                    => 'Descripcion',
'fileuploadsummary'           => 'Resumit :',
'filestatus'                  => "Estatut dels dreches d'autor :",
'filesource'                  => 'Font :',
'uploadedfiles'               => 'Fichièrs copiats',
'ignorewarning'               => 'Ignorar l’avertiment e salvagardar lo fichièr',
'ignorewarnings'              => "Ignorar los avertiments a l'ocasion de l’impòrt",
'minlength1'                  => 'Los noms de fichièrs devon comprendre almens una letra.',
'illegalfilename'             => 'Lo nom de fichièr « $1 » conten de caractèrs interdiches dins los títols de paginas. Mercé de lo tornar nomenar e de lo copiar tornarmai.',
'badfilename'                 => "L'imatge es estat renomenat « $1 ».",
'filetype-badmime'            => 'Los fichièrs del tipe MIME « $1 » pòdon pas èsser importats.',
'filetype-unwanted-type'      => "'''«.$1»''' es d'un format pas desirat. Los que son preferits son $2.",
'filetype-banned-type'        => "'''\".\$1\"''' es dins un format pas admes.  Los que son acceptats son \$2.",
'filetype-missing'            => "Lo fichièr a pas cap d'extension (coma « .jpg » per exemple).",
'large-file'                  => 'Los fichièrs importats deurián pas èsser mai gros que $1 ; aqueste fichièr fa $2.',
'largefileserver'             => "La talha d'aqueste fichièr es superiora al maximom autorizat.",
'emptyfile'                   => 'Lo fichièr que volètz importar sembla void. Aquò pòt èsser degut a una error dins lo nom del fichièr. Verificatz que desiratz vertadièrament copiar aqueste fichièr.',
'fileexists'                  => 'Un fichièr amb aqueste nom existís ja. Mercé de verificar $1. Sètz segur de voler modificar aqueste fichièr ?',
'filepageexists'              => "La pagina de descripcion per aqueste fichièr ja es estada creada aicí <strong><tt>$1</tt></strong>, mas cap de fichièr d'aqueste nom existís pas actualament. Lo resumit qu'anatz escriure remplaçarà pas lo tèxt precedent ; per aquò far, deuretz editar manualament la pagina.",
'fileexists-extension'        => "Un fichièr amb un nom similar existís ja :<br /> Nom del fichièr d'importar : <strong><tt>$1</tt></strong><br /> Nom del fichièr existent : <strong><tt>$2</tt></strong><br /> la sola diferéncia es la cassa (majusculas / minusculas) de l’extension. Verificatz que lo fichièr es diferent e cambiatz son nom.",
'fileexists-thumb'            => "<center>'''Imatge existent'''</center>",
'fileexists-thumbnail-yes'    => 'Lo fichièr sembla èsser un imatge en talha reducha <i>(thumbnail)</i>. Verificatz lo fichièr <strong><tt>$1</tt></strong>.<br /> Se lo fichièr verificat es lo meteis imatge (dins una resolucion melhora), es pas de besonh d’importar una version reducha.',
'file-thumbnail-no'           => 'Lo nom del fichièr comença per <strong><tt>$1</tt></strong>. Es possible que s’agisca d’una version reducha <i>(thumbnail)</i>. Se dispausatz del fichièr en resolucion nauta, importatz-lo, si que non cambiatz lo nom del fichièr.',
'fileexists-forbidden'        => 'Un fichièr amb aqueste nom existís ja ; mercé de tornar en arrièr e de copiar lo fichièr jos un nom novèl. [[Image:$1|thumb|center|$1]]',
'fileexists-shared-forbidden' => 'Un fichièr portant lo meteis nom existís ja dins la banca de donadas comuna ; tornatz en arrièr e mandatz-lo tornarmai jos un autre nom. [[Image:$1|thumb|center|$1]]',
'successfulupload'            => 'Còpia capitada',
'uploadwarning'               => 'Atencion !',
'savefile'                    => 'Salvagardar lo fichièr',
'uploadedimage'               => '«[[$1]]» copiat sul serveire',
'overwroteimage'              => 'a importat una version novèla de « [[$1]] »',
'uploaddisabled'              => 'O planhèm, lo mandadís de fichièr es desactivat.',
'uploaddisabledtext'          => 'La còpia de fichièrs es desactivada sus aqueste wiki.',
'uploadscripted'              => "Aqueste fichièr conten de còde HTML o un escript que poiriá èsser interpretat d'un biais incorrècte per un navigaire Internet.",
'uploadcorrupt'               => 'Aqueste fichièr es corromput, a una talha nulla o a una extension invalida. Verificatz lo fichièr.',
'uploadvirus'                 => 'Aqueste fichièr conten un virús ! Per mai de detalhs, consultatz : $1',
'sourcefilename'              => 'Nom del fichièr font :',
'destfilename'                => 'Nom jolqual lo fichièr serà enregistrat&nbsp;:',
'upload-maxfilesize'          => 'Talha maximala del fichièr : $1',
'watchthisupload'             => 'Seguir aquesta pagina',
'filewasdeleted'              => 'Un fichièr amb aqueste nom es estat copiat ja, puèi suprimit. Deuriatz verificar lo $1 abans de procedir a una còpia novèla.',
'upload-wasdeleted'           => "'''Atencion : Sètz a importar un fichièr que ja es estat suprimit deperabans.''' Deuriatz considerar se es oportun de contunhar l'impòrt d'aqueste fichièr. Lo jornal de las supressions vos donarà los elements d'informacion.",
'filename-bad-prefix'         => 'Lo nom del fichièr qu\'importatz comença per <strong>"$1"</strong> qu\'es un nom generalament donat pels aparelhs de fòto numerica e que decritz pas lo fichièr. Causissetz un nom de fichièr descrivent vòstre fichièr.',
'filename-prefix-blacklist'   => ' #<!-- daissatz aquesta linha coma es --> <pre>
# La sintaxi es la seguenta :
#   * Tot çò que seguís lo caractèr "#" fins a la fin de la linha es un comentari
#   * Tota linha non vioda es un prefix tipic de nom de fichièr assignat automaticament pels aparelhs numerics
CIMG # Casio
DSC_ # Nikon
DSCF # Fuji
DSCN # Nikon
DUW # some mobil phones
IMG # generic
JD # Jenoptik
MGP # Pentax
PICT # misc.
 #</pre> <!-- daissatz aquesta linha coma es -->',

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
'upload-curl-error28-text' => "Lo sit a mes tròp de temps per respondre. Verificatz que lo sit es en linha, esperatz un pauc e ensajatz tornarmai. Tanben podètz ensajar a una ora d'afluéncia mendra.",

'license'            => 'Licéncia&nbsp;:',
'nolicense'          => 'Cap de licéncia seleccionada',
'license-nopreview'  => '(Previsualizacion impossibla)',
'upload_source_url'  => ' (una URL valida e accessibla publicament)',
'upload_source_file' => '(un fichièr sus vòstre ordenador)',

# Special:Imagelist
'imagelist-summary'     => 'Aquesta pagina especiala mòstra totes los fichièrs importats.
Per defaut, las darrièrs fichièrs importats son afichats en naut de la lista.
Un clic en tèsta de colomna cambia l’òrdre d’afichatge.',
'imagelist_search_for'  => 'Recèrca pel mèdia nomenat :',
'imgfile'               => 'fichièr',
'imagelist'             => 'Lista dels imatges',
'imagelist_date'        => 'Data',
'imagelist_name'        => 'Nom',
'imagelist_user'        => 'Utilizaire',
'imagelist_size'        => 'Talha (en octets)',
'imagelist_description' => 'Descripcion',

# Image description page
'filehist'                       => 'Istoric del fichièr',
'filehist-help'                  => 'Clicar sus una data e una ora per veire lo fichièr tal coma èra a aqueste moment',
'filehist-deleteall'             => 'tot suprimir',
'filehist-deleteone'             => 'suprimir aquò',
'filehist-revert'                => 'revocar',
'filehist-current'               => 'actual',
'filehist-datetime'              => 'Data e ora',
'filehist-user'                  => 'Utilizaire',
'filehist-dimensions'            => 'Dimensions',
'filehist-filesize'              => 'Talha del fichièr',
'filehist-comment'               => 'Comentari',
'imagelinks'                     => "Ligams vèrs l'imatge",
'linkstoimage'                   => 'Las paginas çaijós compòrtan un ligam vèrs aqueste imatge :',
'nolinkstoimage'                 => 'Cap de pagina compòrta pas de ligam vèrs aqueste imatge.',
'sharedupload'                   => 'Aqueste fichièr es partejat e pòt èsser utilizat per d’autres projèctes.',
'shareduploadwiki'               => 'Reportatz-vos a la $1 per mai d’informacion.',
'shareduploadwiki-desc'          => 'La $1 del repertòri partejat compren la descripcion afichada çaijós.',
'shareduploadwiki-linktext'      => 'Pagina de descripcion del fichièr',
'shareduploadduplicate'          => "Aqueste fichièr es un doblon de $1 d'un depaus partejat.",
'shareduploadduplicate-linktext' => 'un autre fichièr',
'shareduploadconflict'           => "Aqueste fichièr a lo meteis nom que $1 qu'es dins un depaus partejat.",
'shareduploadconflict-linktext'  => 'un autre fichièr',
'noimage'                        => 'Cap de fichièr possedissent aqueste nom existís pas, podètz $1.',
'noimage-linktext'               => "n'importar un",
'uploadnewversion-linktext'      => "Copiar una version novèla d'aqueste fichièr",
'imagepage-searchdupe'           => 'Recèrca dels fichièrs en doble',

# File reversion
'filerevert'                => 'Revocar $1',
'filerevert-legend'         => 'Revocar lo fichièr',
'filerevert-intro'          => '<span class="plainlinks">Anatz revocar \'\'\'[[Media:$1|$1]]\'\'\' fins a [$4 la version del $2 a $3].</span>',
'filerevert-comment'        => 'Comentari :',
'filerevert-defaultcomment' => 'Revocat fins a la version del $1 a $2',
'filerevert-submit'         => 'Revocar',
'filerevert-success'        => '<span class="plainlinks">\'\'\'[[Media:$1|$1]]\'\'\' es estat revocat fins a [$4 la version del $2 a $3].</span>',
'filerevert-badversion'     => 'I a pas de version mai anciana del fichièr amb lo Timestamp donat.',

# File deletion
'filedelete'                  => 'Suprimís $1',
'filedelete-legend'           => 'Suprimir lo fichièr',
'filedelete-intro'            => "Sètz a suprimir '''[[Media:$1|$1]]'''.",
'filedelete-intro-old'        => '<span class="plainlinks">Sètz a escafar la version de \'\'\'[[Media:$1|$1]]\'\'\' del [$4 $2 a $3].</span>',
'filedelete-comment'          => "Motiu de l'escafament :",
'filedelete-submit'           => 'Suprimir',
'filedelete-success'          => "'''$1''' es estat suprimit.",
'filedelete-success-old'      => '<span class="plainlinks">La version de \'\'\'[[Media:$1|$1]]\'\'\' del $2 a $3 es estada suprimida.</span>',
'filedelete-nofile'           => "'''$1''' existís pas sus aqueste sit.",
'filedelete-nofile-old'       => "Existís pas cap de version archivada de '''$1''' amb los atributs indicats.",
'filedelete-iscurrent'        => "Sètz a ensajar de suprimir la version mai recenta d'aqueste fichièr. Vos cal, deperabans, restablir una version anciana d'aqueste.",
'filedelete-otherreason'      => 'Rason diferenta/suplementària :',
'filedelete-reason-otherlist' => 'Autra rason',
'filedelete-reason-dropdown'  => '*Motius de supression costumièrs
** Violacion de drech d’autor
** Fichièr duplicat',
'filedelete-edit-reasonlist'  => 'Modifica los motius de la supression',

# MIME search
'mimesearch'         => 'Recèrca per tipe MIME',
'mimesearch-summary' => 'Aquesta pagina especiala permet de cercar de fichièrs en foncion de lor tipe MIME. Entrada : tipe/sostipe, per exemple <tt>image/jpeg</tt>.',
'mimetype'           => 'Tipe MIME :',
'download'           => 'telecargament',

# Unwatched pages
'unwatchedpages' => 'Paginas pas seguidas',

# List redirects
'listredirects' => 'Lista de las redireccions',

# Unused templates
'unusedtemplates'     => 'Modèls inutilizats',
'unusedtemplatestext' => "Aquesta pagina lista totas las paginas de l’espaci de noms « Modèl » que son pas inclusas dins cap d'autra pagina. Doblidetz pas de verificar se i a pas d’autre ligam vèrs los modèls abans de los suprimir.",
'unusedtemplateswlh'  => 'autres ligams',

# Random page
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

Aquesta chifra inclutz las paginas \"discussion\", las paginas relativas a {{SITENAME}}, las paginas minimalas (\"esbòsses\"),  las paginas de redireccion, e mai d'autras paginas que pòdon sens dobte pas èsser consideradas coma d'articles.
Se s'exclutz aquestes paginas,  <b>{{PLURAL:\$2|'''\$2''' pagina es probablament un article vertadièr|'''\$2''' paginas son probablament d'articles vertadièrs}}.

{{PLURAL:\$8|'''\$8''' fichièr es estat telecargat|'''\$8''' fichièrs son estats telecargats}}.

{{PLURAL:\$3|'''1''' pagina es estada consultada|'''\$3''' paginas son estadas consultadas}} e {{PLURAL:\$4| '''1''' pagina modificada|'''\$4''' paginas modificadas}}.

Aquò representa una mejana de {{PLURAL:\$5|'''\$5''' modificacion|'''\$5''' modificacions}} per pagina e de {{PLURAL:\$6|'''\$6''' consultacion|'''\$6''' consultacions}} per una modificacion.

I a {{PLURAL:\$7|'''\$7''' article|'''\$7''' articles}} dins [[meta:Help:Job_queue|la fila de prètzfaches]].",
'userstatstext'          => "I a {{PLURAL:$1|'''$1''' [[Special:Listusers|utilizaire enregistrat]]. I a '''$2''' (o '''$4%''') que es $5 (vejatz $3).|'''$1''' [[Special:Listusers|utilizaires enregistrats]]. Demest eles, '''$2''' (o '''$4%''') son $5 (vejatz $3).}}",
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

'withoutinterwiki'         => 'Paginas sens ligams interlengas',
'withoutinterwiki-summary' => "Aquesta pagina a pas de ligams vèrs las versions dins d'autras lengas:",
'withoutinterwiki-legend'  => 'Prefix',
'withoutinterwiki-submit'  => 'Afichar',

'fewestrevisions' => 'Articles amb lo mens de revisions',

# Miscellaneous special pages
'nbytes'                  => '$1 {{PLURAL:$1|octet|octets}}',
'ncategories'             => '$1 {{PLURAL:$1|categoria|categorias}}',
'nlinks'                  => '$1 {{PLURAL:$1|ligam|ligams}}',
'nmembers'                => '$1 {{PLURAL:$1|membre|membres}}',
'nrevisions'              => '$1 {{PLURAL:$1|revision|revisions}}',
'nviews'                  => '$1 {{PLURAL:$1|consultacion|consultacions}}',
'specialpage-empty'       => 'Aquesta pagina es voida.',
'lonelypages'             => 'Paginas orfanèlas',
'lonelypagestext'         => 'Las paginas seguentas son pas ligadas a partir d’autras paginas de {{SITENAME}}.',
'uncategorizedpages'      => 'Paginas sens categorias',
'uncategorizedcategories' => 'Categorias sens categorias',
'uncategorizedimages'     => 'Fichièrs sens categorias',
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
'mostimages'              => 'Fichièrs mai utilizats',
'mostrevisions'           => 'Articles mai modificats',
'prefixindex'             => 'Totas las paginas per primièras letras',
'shortpages'              => 'Articles brèus',
'longpages'               => 'Articles longs',
'deadendpages'            => "Paginas sul camin d'enlòc",
'deadendpagestext'        => 'Las paginas seguentas contenon pas cap de ligam vèrs d’autras paginas de {{SITENAME}}.',
'protectedpages'          => 'Paginas protegidas',
'protectedpages-indef'    => 'Unicament las proteccions permanentas',
'protectedpagestext'      => 'Las paginas seguentas son protegidas contra las modificacions e/o lo renomenatge :',
'protectedpagesempty'     => 'Cap de pagina es pas protegida actualament.',
'protectedtitles'         => 'Títols protegits',
'protectedtitlestext'     => 'Los títols seguents son protegits a la creacion',
'protectedtitlesempty'    => 'Cap de títol es pas actualament protegit amb aquestes paramètres.',
'listusers'               => 'Lista dels participants',
'specialpages'            => 'Paginas especialas',
'spheading'               => 'Paginas especialas',
'restrictedpheading'      => 'Paginas especialas reservadas',
'newpages'                => 'Paginas novèlas',
'newpages-username'       => "Nom d'utilizaire :",
'ancientpages'            => 'Articles mai ancians',
'move'                    => 'Tornar nomenar',
'movethispage'            => 'Tornar nomenar la pagina',
'unusedimagestext'        => "Doblidetz pas que d'autres sits pòdon conténer un ligam dirèct vèrs aqueste imatge, e qu'aqueste pòt èsser plaçat dins aquesta lista alara qu'es en realitat utilizada.",
'unusedcategoriestext'    => "Las categorias seguentas existisson mas cap d'article o de categoria los utilizan pas.",
'notargettitle'           => 'Pas de cibla',
'notargettext'            => 'Indicatz una pagina cibla o un utilizaire cibla.',
'pager-newer-n'           => '{{PLURAL:$1|1 mai recenta|$1 mai recentas}}',
'pager-older-n'           => '{{PLURAL:$1|1 mai anciana|$1 mai ancianas}}',
'suppress'                => 'Supervisaire',

# Book sources
'booksources'               => 'Obratges de referéncia',
'booksources-search-legend' => "Recèrca demest d'obratges de referéncia",
'booksources-isbn'          => 'ISBN :',
'booksources-go'            => 'Validar',
'booksources-text'          => "Vaquí una lista de ligams vèrs d’autres sits que vendon de libres nous e d’occasion e sulsquals trobarètz benlèu d'entresenhas suls obratges que cercatz. {{SITENAME}} es pas ligada a cap d'aquestas societats, a pas l’intencion de ne far la promocion.",

# Special:Log
'specialloguserlabel'  => 'Utilizaire :',
'speciallogtitlelabel' => 'Títol :',
'log'                  => 'Jornals',
'all-logs-page'        => 'Totes los jornals',
'log-search-legend'    => "Recèrca d'istorics",
'log-search-submit'    => 'Anar',
'alllogstext'          => 'Afichatge combinat dels jornals de còpia, supression, proteccion, blocatge, e administrator. Podètz restrénher la vista en seleccionant un tipe de jornal, un nom d’utilizaire o la pagina concernida.',
'logempty'             => 'I a pas res dins l’istoric per aquesta pagina.',
'log-title-wildcard'   => 'Recercar de títols que començan per aqueste tèxt',

# Special:Allpages
'allpages'          => 'Totas las paginas',
'alphaindexline'    => '$1 a $2',
'nextpage'          => 'Pagina seguenta ($1)',
'prevpage'          => 'Pagina precedenta ($1)',
'allpagesfrom'      => 'Afichar las paginas a partir de :',
'allarticles'       => 'Totas las paginas',
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

# Special:Listgrouprights
'listgrouprights'          => "Dreches dels gropes d'utilizaires",
'listgrouprights-summary'  => "Aquesta pagina conten una tièra de gropes definits sus aqueste wiki e mai los dreches d'accès qu'i son associats. D'entresenhas complementàrias suls dreches pòdon èsser trobats [[{{int:Listgrouprights-helppage}}|aicí]].",
'listgrouprights-group'    => 'Grop',
'listgrouprights-rights'   => 'Dreches associats',
'listgrouprights-helppage' => 'Help:Dreches dels gropes',
'listgrouprights-members'  => '(lista de membres)',

# E-mail user
'mailnologin'     => "Pas d'adreça",
'mailnologintext' => 'Vos cal èsser [[Special:Userlogin|connectat(ada)]]
e aver indicat una adreça electronica valida dins vòstras [[Special:Preferences|preferéncias]]
per poder mandar un messatge a un autre utilizaire.',
'emailuser'       => 'Mandar un messatge a aqueste utilizaire',
'emailpage'       => 'Mandar un corrièr electronic a l’utilizaire',
'emailpagetext'   => 'Se aqueste utilizaire a indicat una adreça electronica valida dins sas preferéncias, lo formulari çaijós li mandarà un messatge.
L\'adreça electronica qu\'avètz indicada dins vòstras preferéncias apareisserà dins lo camp "Expeditor" de vòstre messatge, per que lo destinatari vos pòsca respondre.',
'usermailererror' => 'Error dins lo subjècte del corrièr electronic :',
'defemailsubject' => 'Corrièr electronic mandat dempuèi {{SITENAME}}',
'noemailtitle'    => "Pas d'adreça electronica",
'noemailtext'     => "Aquesta utilizaire a pas especificat d'adreça electronica valida o a causit de recebre pas de corrièr electronic dels autres utilizaires.",
'emailfrom'       => 'Expeditor',
'emailto'         => 'Destinatari',
'emailsubject'    => 'Subjècte',
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
'watchlistanontext'    => 'Per poder afichar o editar los elements de vòstra lista de seguit, vos devètz $1.',
'watchnologin'         => 'Vos sètz pas identificat(ada)',
'watchnologintext'     => 'Vos cal èsser [[Special:Userlogin|connectat(ada)]]
per modificar vòstra lista de seguit.',
'addedwatch'           => 'Ajustat a la lista',
'addedwatchtext'       => 'La pagina "<nowiki>$1</nowiki>" es estada ajustada a vòstra [[Special:Watchlist|lista de seguit]]. Las modificacions venentas d\'aquesta pagina e de la pagina de discussion associada seràn repertoriadas aicí, e la pagina apareisserà <b>en gras</b> dins la [[Special:Recentchanges|lista dels darrièrs cambiaments]] per èsser localizada mai aisidament. Per suprimir aquesta pagina de vòstra lista de seguit, clicatz sus "Arrestar de seguir" dins lo quadre de navigacion.',
'removedwatch'         => 'Suprimida de la lista de seguit',
'removedwatchtext'     => 'La pagina "[[:$1]]" es estada suprimida de vòstra lista de seguit.',
'watch'                => 'Seguir',
'watchthispage'        => 'Seguir aquesta pagina',
'unwatch'              => 'Arrestar de seguir',
'unwatchthispage'      => 'Arrestar de seguir',
'notanarticle'         => "Pas cap d'article",
'notvisiblerev'        => 'Version suprimida',
'watchnochange'        => 'Cap de las paginas que seguissètz son pas estadas modificadas pendent lo periòde afichat.',
'watchlist-details'    => 'Seguissètz {{PLURAL:$1|$1 pagina|$1 paginas}}, sens comptar las paginas de discussion.',
'wlheader-enotif'      => '* La notificacion per corrièr electronic es activada.',
'wlheader-showupdated' => '* Las paginas que son estadas modificadas dempuèi vòstra darrièra visita son mostradas en <b>gras</b>',
'watchmethod-recent'   => 'verificacion dels darrièrs cambiaments de las paginas seguidas',
'watchmethod-list'     => 'verificacion de las paginas seguidas per de modificacions recentas',
'watchlistcontains'    => 'Vòstra lista de seguit conten $1 {{PLURAL:$1|pagina|paginas}}.',
'iteminvalidname'      => "Problèma amb l'article '$1': lo nom es invalid...",
'wlnote'               => 'Çaijós se {{PLURAL:$1|tròba la darrièra modificacion|tròban las $1 darrièras modificacions}} dempuèi {{PLURAL:$2|la darrièra ora|las <b>$2</b> darrièras oras}}.',
'wlshowlast'           => 'Mostrar las darrièras $1 oras, los darrièrs $2 jorns, o $3.',
'watchlist-show-bots'  => 'Afichar las contribucions dels bòts',
'watchlist-hide-bots'  => 'Amagar las contribucions dels bòts',
'watchlist-show-own'   => 'Afichar mas modificacions',
'watchlist-hide-own'   => 'Amagar mas modificacions',
'watchlist-show-minor' => 'Afichar las modificacions menoras',
'watchlist-hide-minor' => 'Amagar las modificacions menoras',

# Displayed when you click the "watch" button and it is in the process of watching
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
{{fullurl:{{MediaWiki:Helppage}}}}',

# Delete/protect/revert
'deletepage'                  => 'Suprimir una pagina',
'confirm'                     => 'Confirmar',
'excontent'                   => "contenent '$1'",
'excontentauthor'             => 'lo contengut èra : « $1 » (e lo sol contributor èra « [[Special:Contributions/$2|$2]] »)',
'exbeforeblank'               => "lo contengut abans escafament èra :'$1'",
'exblank'                     => 'pagina voida',
'delete-confirm'              => 'Escafar «$1»',
'delete-legend'               => 'Escafar',
'historywarning'              => 'Atencion : La pagina que sètz a mand de suprimir a un istoric :',
'confirmdeletetext'           => "Sètz a mand de suprimir definitivament de la banca de donadas una pagina
o un imatge, e mai totas sas versions anterioras.
Confirmatz qu'es plan çò que volètz far, que ne comprenètz las consequéncias e que fasètz aquò en acòrdi amb las [[{{MediaWiki:Policy-url}}]].",
'actioncomplete'              => 'Supression efectuada',
'deletedtext'                 => '"<nowiki>$1</nowiki>" es estat suprimit.
Vejatz $2 per una lista de las supressions recentas.',
'deletedarticle'              => 'escafament de «[[$1]]»',
'suppressedarticle'           => 'amagat  « [[$1]] »',
'dellogpage'                  => 'Istoric dels escafaments',
'dellogpagetext'              => "Vaquí la lista de las supressions recentas.
L'ora indicada es la del serveire (UTC).
<ul>
</ul>",
'deletionlog'                 => 'istoric dels escafaments',
'reverted'                    => 'Restabliment de la version precedenta',
'deletecomment'               => 'Motiu de la supression :',
'deleteotherreason'           => 'Motius suplementaris o autres :',
'deletereasonotherlist'       => 'Autre motiu',
'deletereason-dropdown'       => "*Motius de supression mai corrents
** Demanda de l'autor
** Violacion dels dreches d'autor
** Vandalisme",
'delete-edit-reasonlist'      => 'Modifica los motius de la supression',
'delete-toobig'               => "Aquesta pagina dispausa d'un istoric important, depassant $1 versions. La supression de talas paginas es estada limitada per evitar de perturbacions accidentalas de {{SITENAME}}.",
'delete-warning-toobig'       => "Aquesta pagina dispausa d'un istoric important, depassant $1 versions. La suprimir pòt perturbar lo foncionament de la banca de donada de {{SITENAME}}. D'efectuar amb prudéncia.",
'rollback'                    => 'Anullar las modificacions',
'rollback_short'              => 'Anullar',
'rollbacklink'                => 'anullar',
'rollbackfailed'              => "L'anullacion a pas capitat",
'cantrollback'                => "Impossible de revocar : l'autor es la sola persona a aver efectuat de modificacions sus aqueste article",
'alreadyrolled'               => "Impossible de revocar la darrièra modificacion de [[:$1]]
per  [[User:$2|$2]] ([[User talk:$2|Discussion]]); qualqu'un d'autre ja a modificat o revocat l'article.

La darrièra modificacion èra de [[User:$3|$3]] ([[User talk:$3|Discussion]]).",
'editcomment'                 => 'Lo resumit de la modificacion èra: "<i>$1</i>".', # only shown if there is an edit comment
'revertpage'                  => 'restitucion de la darrièra modificacion de [[Special:Contributions/$2|$2]] ([[User talk:$2|Discussion]]); retorn a la darrièra version de [[User:$1|$1]]', # Additional available: $3: revid of the revision reverted to, $4: timestamp of the revision reverted to, $5: revid of the revision reverted from, $6: timestamp of the revision reverted from
'rollback-success'            => 'Anullacion de las modificacions de $1 ; retorn a la version de $2.',
'sessionfailure'              => 'Vòstra sesilha de connexion sembla aver de problèmas ;
aquesta accion es estada anullada en prevencion d’un piratatge de sesilha.
Clicatz sus « Precedent » e tornatz cargar la pagina d’ont venètz, puèi ensajatz tornarmai.',
'protectlogpage'              => 'Istoric de las proteccions',
'protectlogtext'              => 'Vejatz las [[Special:Protectedpages|directivas]] per mai d’informacion.',
'protectedarticle'            => 'a protegit « [[$1]] »',
'modifiedarticleprotection'   => 'a modificat lo nivèl de proteccion de « [[$1]] »',
'unprotectedarticle'          => 'a desprotegit « [[$1]] »',
'protect-title'               => 'Protegir « $1 »',
'protect-legend'              => 'Confirmar la proteccion',
'protectcomment'              => 'Rason de la proteccion',
'protectexpiry'               => 'Expiracion (expira pas per defaut)',
'protect_expiry_invalid'      => 'Lo temps d’expiracion es invalid',
'protect_expiry_old'          => 'Lo temps d’expiracion ja es passat.',
'protect-unchain'             => 'Desblocar las permissions de renomenatge',
'protect-text'                => 'Podètz consultar e modificar lo nivèl de proteccion de la pagina <strong><nowiki>$1</nowiki></strong>. Asseguratz-vos que seguissètz las règlas intèrnas.',
'protect-locked-blocked'      => 'Podètz pas modificar lo nivèl de proteccion tant que sètz blocat. Vaquí los reglatges actuals de la pagina <strong>$1</strong> :',
'protect-locked-dblock'       => 'Lo nivèl de proteccion pòt pas èsser modificat perque la banca de donadas es blocada. Vaquí los reglatges actuals de la pagina <strong>$1</strong> :',
'protect-locked-access'       => 'Avètz pas los dreches necessaris per modificar la proteccion de la pagina. Vaquí los reglatges actuals de la pagina <strong>$1</strong> :',
'protect-cascadeon'           => "Aquesta pagina es actualament protegida perque es inclusa dins {{PLURAL:$1|la pagina seguenta|las paginas seguentas}}, {{PLURAL:$1|qu'es estada protegida|que son estadas protegidas}} amb l’opcion « proteccion en cascada » activada. Podètz cambiar lo nivèl de proteccion d'aquesta pagina sens qu'aquò afècte la proteccion en cascada.",
'protect-default'             => 'Pas de proteccion',
'protect-fallback'            => 'Necessita l’abilitacion «$1»',
'protect-level-autoconfirmed' => 'Semiproteccion',
'protect-level-sysop'         => 'Administrators unicament',
'protect-summary-cascade'     => 'proteccion en cascada',
'protect-expiring'            => 'expira lo $1',
'protect-cascade'             => 'Proteccion en cascada - Protegís totas las paginas inclusas dins aquesta.',
'protect-cantedit'            => "Podètz pas modificar los nivèls de proteccion d'aquesta pagina perque avètz pas la permission de l'editar.",
'restriction-type'            => 'Permission :',
'restriction-level'           => 'Nivèl de restriccion',
'minimum-size'                => 'Talha minimom',
'maximum-size'                => 'Talha maximala :',
'pagesize'                    => '(octets)',

# Restrictions (nouns)
'restriction-edit'   => 'Modificacion',
'restriction-move'   => 'Renomenatge',
'restriction-create' => 'Crear',
'restriction-upload' => 'Importar',

# Restriction levels
'restriction-level-sysop'         => 'Proteccion complèta',
'restriction-level-autoconfirmed' => 'Semiproteccion',
'restriction-level-all'           => 'Totes',

# Undelete
'undelete'                     => 'Restablir la pagina escafada',
'undeletepage'                 => 'Veire e restablir la pagina escafada',
'undeletepagetitle'            => "'''La lista seguenta se compausa de versions suprimidas de [[:$1]]'''.",
'viewdeletedpage'              => 'Istoric de la pagina suprimida',
'undeletepagetext'             => 'Aquestas paginas son estadas escafadas e se tròban dins la corbelha, son totjorn dins la banca de donada e pòdon èsser restablidas.
La corbelha pòt èsser escafada periodicament.',
'undeleteextrahelp'            => "Per restablir totas las versions d'aquesta pagina, daissatz vèrjas totas las casas de marcar, puèi clicatz sus '''''Procedir al restabliment'''''.<br />Per procedir a un restabliment selectiu, marcatz las casas correspondent a las versions que son de restablir, puèi clicatz sus '''''Procedir a la restabliment'''''.<br />En clicant sul boton '''''Reïnicializar''''', la bóstia de resumit e las casas marcadas seràn remesas a zèro.",
'undeleterevisions'            => '$1 {{PLURAL:$1|revision archivada|revisions archivadas}}',
'undeletehistory'              => "Se restablissètz la pagina, totas las revisions seràn restablidas dins l'istoric.

Se una pagina novèla amb lo meteis nom es estada creada dempuèi la supression,
las revisions restablidas apareisseràn dins l'istoric anterior e la version correnta serà pas automaticament remplaçada.",
'undeleterevdel'               => 'Lo restabliment serà pas efectuat se, fin finala, la version mai recenta de la pagina es parcialament suprimida. Dins aqueste cas, vos cal deseleccionatz las versions mai recentas (en naut). Las versions dels fichièrs a lasqualas avètz pas accès seràn pas restablidas.',
'undeletehistorynoadmin'       => "Aqueste article es estat suprimit. Lo motiu de la supression es indicat dins lo resumit çaijós, amb los detalhs dels utilizaires que l’an modificat abans sa supression. Lo contengut d'aquestas versions es pas accessible qu’als administrators.",
'undelete-revision'            => 'Version suprimida de $1, (revision del $2) per $3 :',
'undeleterevision-missing'     => 'Version invalida o mancanta. Benlèu avètz un ligam marrit, o la version es estada restablida o suprimida de l’archiu.',
'undelete-nodiff'              => 'Pas de revision precedenta trobada.',
'undeletebtn'                  => 'Restablir !',
'undeletelink'                 => 'restablir',
'undeletereset'                => 'Reïnicializar',
'undeletecomment'              => 'Comentari :',
'undeletedarticle'             => 'a restablit « [[$1]] »',
'undeletedrevisions'           => '{{PLURAL:$1|1 revision restablida|$1 revisions restablidas}}',
'undeletedrevisions-files'     => '{{PLURAL:$1|1 revision|$1 revisions}} e {{PLURAL:$2|1 fichièr restablit|$2 fichièrs restablits}}',
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
'uctop'         => '(darrièra)',
'month'         => 'A partir del mes (e precedents) :',
'year'          => 'A partir de l’annada (e precedentas) :',

'sp-contributions-newbies'     => 'Mostrar pas que las contribucions dels utilizaires novèls',
'sp-contributions-newbies-sub' => 'Lista de las contribucions dels utilizaires novèls. Las paginas que son estadas suprimidas son pas afichadas.',
'sp-contributions-blocklog'    => 'Jornal dels blocatges',
'sp-contributions-search'      => 'Cercar las contribucions',
'sp-contributions-username'    => 'Adreça IP o nom d’utilizaire :',
'sp-contributions-submit'      => 'Cercar',

# What links here
'whatlinkshere'            => 'Paginas ligadas a aquesta',
'whatlinkshere-title'      => "Paginas qu'an de ligams puntant vèrs $1",
'whatlinkshere-page'       => 'Pagina :',
'linklistsub'              => '(Lista de ligams)',
'linkshere'                => "Las paginas çaijós contenon un ligam vèrs '''[[:$1]]''':",
'nolinkshere'              => "Cap de pagina conten pas de ligam vèrs '''[[:$1]]'''.",
'nolinkshere-ns'           => "Cap de pagina conten pas de ligam vèrs '''[[:$1]]''' dins l’espaci de nom causit.",
'isredirect'               => 'pagina de redireccion',
'istemplate'               => 'inclusion',
'whatlinkshere-prev'       => '{{PLURAL:$1|precedent|$1 precedents}}',
'whatlinkshere-next'       => '{{PLURAL:$1|seguent|$1 seguents}}',
'whatlinkshere-links'      => '← ligams',
'whatlinkshere-hideredirs' => '$1 redireccions',
'whatlinkshere-hidetrans'  => '$1 transclusions',
'whatlinkshere-hidelinks'  => '$1 ligams',
'whatlinkshere-filters'    => 'Filtres',

# Block/unblock
'blockip'                     => 'Blocar una adreça IP',
'blockip-legend'              => 'Blocar en escritura',
'blockiptext'                 => "Utilizatz lo formulari çaijós per blocar l'accès en escritura a partir d'una adreça IP donada.
Una tala mesura deu pas èsser presa pas que per empachar lo vandalisme e en acòrdi amb [[{{MediaWiki:Policy-url}}]].
Donatz çaijós una rason precisa (per exemple en indicant las paginas que son estadas vandalizadas).",
'ipaddress'                   => 'Adreça IP :',
'ipadressorusername'          => 'Adreça IP o nom d’utilizaire',
'ipbexpiry'                   => 'Durada del blocatge',
'ipbreason'                   => 'Motiu :',
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
'ipboptions'                  => '2 oras:2 hours,1 jorn:1 day,3 jorns:3 days,1 setmana:1 week,2 setmanas:2 weeks,1 mes:1 month,3 meses:3 months,6 meses:6 months,1 an:1 year,indefinidament:infinite', # display1:time1,display2:time2,...
'ipbotheroption'              => 'autre',
'ipbotherreason'              => 'Motiu diferent o suplementari',
'ipbhidename'                 => "Amagar lo nom d’utilizaire de l'istoric de blocatge, de la lista dels blocatges actius e de la lista dels utilizaires",
'badipaddress'                => "L'adreça IP es incorrècta",
'blockipsuccesssub'           => 'Blocatge capitat',
'blockipsuccesstext'          => 'L\'adreça IP "$1" es estada blocada.
<br />Podètz consultar sus aquesta [[Special:Ipblocklist|pagina]] la lista de las adreças IP blocadas.',
'ipb-edit-dropdown'           => 'Modificar los motius de blocatge per defaut',
'ipb-unblock-addr'            => 'Desblocar $1',
'ipb-unblock'                 => "Desblocar un compte d'utilizaire o una adreça IP",
'ipb-blocklist-addr'          => 'Vejatz los blocatges existents per $1',
'ipb-blocklist'               => 'Vejatz los blocatges existents',
'unblockip'                   => 'Desblocar una adreça IP',
'unblockiptext'               => "Utilizatz lo formulari çaijós per restablir l'accès en escritura
a partir d'una adreça IP precedentament blocada.",
'ipusubmit'                   => 'Desblocar aquesta adreça',
'unblocked'                   => '[[User:$1|$1]] es estat desblocat',
'unblocked-id'                => 'Lo blocatge $1 es estat levat',
'ipblocklist'                 => 'Lista de las adreças IP blocadas',
'ipblocklist-legend'          => 'Cercar un utilizaire blocat',
'ipblocklist-username'        => 'Nom de l’utilizaire o adreça IP :',
'ipblocklist-summary'         => 'La lista çaijós mòstra totes los utilizaires e adreças IP blocats, per òrdre anticronologic. Consultatz lo [[Special:Log/block|jornal de blocatge]] per veire las darrièras accions de blocatge e desblocatge efectuadas.',
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
'autoblocker'                 => 'Sètz estat autoblocat perque partejatz una adreça IP amb "[[User:$1|$1]]".
La rason balhada per $1 es: "\'\'\'$2\'\'\'".',
'blocklogpage'                => 'Istoric dels blocatges',
'blocklogentry'               => 'a blocat « [[$1]] » - durada : $2 $3',
'blocklogtext'                => "Aquò es l'istoric dels blocatges e desblocatges dels utilizaires. Las adreças IP automaticament blocadas son pas listadas. Consultatz la [[Special:Ipblocklist|lista dels utilizaires blocats]] per veire qui es actualament efectivament blocat.",
'unblocklogentry'             => 'a desblocat « $1 »',
'block-log-flags-anononly'    => 'utilizaires anonims solament',
'block-log-flags-nocreate'    => 'creacion de compte interdicha',
'block-log-flags-noautoblock' => 'autoblocatge de las IP desactivat',
'block-log-flags-noemail'     => 'e-mail blocat',
'range_block_disabled'        => "Lo blocatge de plajas d'IP es estat desactivat.",
'ipb_expiry_invalid'          => 'Temps d’expiracion invalid.',
'ipb_already_blocked'         => '« $1 » ja es blocat',
'ipb_cant_unblock'            => 'Error : Lo blocatge d’ID $1 existís pas. Es possible qu’un desblocatge ja siá estat efectuat.',
'ipb_blocked_as_range'        => "Error : L'adreça IP $1 es pas estada blocada dirèctament e doncas pòt pas èsser deblocada. Çaquelà, es estada blocada per la plaja $2 laquala pòt èsser deblocada.",
'ip_range_invalid'            => 'Blòt IP incorrècte.',
'blockme'                     => 'Blocatz-me',
'proxyblocker'                => 'Blocaire de proxy',
'proxyblocker-disabled'       => 'Aquesta foncion es desactivada.',
'proxyblockreason'            => "Vòstra ip es estada blocada perque s’agís d’un proxy dobert. Mercé de contactar vòstre fornidor d’accès internet o vòstre supòrt tecnic e de l’informar d'aqueste problèma de seguretat.",
'proxyblocksuccess'           => 'Acabat.',
'sorbsreason'                 => 'Vòstra adreça IP es listada en tant que proxy dobert DNSBL per {{SITENAME}}.',
'sorbs_create_account_reason' => 'Vòstra adreça IP es listada en tant que proxy dobert DNSBL per {{SITENAME}}. Podètz pas crear un compte',

# Developer tools
'lockdb'              => 'Varrolhar la banca',
'unlockdb'            => 'Desvarrolhar la banca',
'lockdbtext'          => "Lo clavatge de la banca de donadas empacharà totes los utilizaires de modificar las paginas, de salvagardar lors preferéncias, de modificar lor lista de seguit e d'efectuar totas las autras operacions necessitant de modificacions dins la banca de donadas.
Confirmatz qu'es plan çò que volètz far e que desblocarètz la banca tre que vòstra operacion de mantenença serà acabada.",
'unlockdbtext'        => "Lo desclavatge de la banca de donadas permetrà a totes los utilizaires de modificar tornarmai de paginas, de metre a jorn lors preferéncias e lor lista de seguit, e mai d'efectuar las autras operacions necessitant de modificacions dins la banca de donadas.
Confirmatz qu'es plan çò que volètz far.",
'lockconfirm'         => 'Òc, confirmi que desiri clavar la banca de donadas.',
'unlockconfirm'       => 'Òc, confirmi que desiri desvarrolhar la banca de donadas.',
'lockbtn'             => 'Varrolhar la banca',
'unlockbtn'           => 'Desvarrolhar la banca',
'locknoconfirm'       => 'Avètz pas marcat la casa de confirmacion.',
'lockdbsuccesssub'    => 'Varrolhatge de la banca capitat.',
'unlockdbsuccesssub'  => 'Banca desvarrolhada.',
'lockdbsuccesstext'   => 'La banca de donadas de {{SITENAME}} es varrolhada.

Doblidetz pas de la desvarrolhar quand auretz acabat vòstra operacion de mantenença.',
'unlockdbsuccesstext' => 'La banca de donadas de {{SITENAME}} es desvarrolhada.',
'lockfilenotwritable' => 'Lo fichièr de blocatge de la banca de donadas es pas inscriptible. Per blocar o desblocar la banca de donadas, vos cal poder escriure sul serveire web.',
'databasenotlocked'   => 'La banca de donadas es pas clavada.',

# Move page
'move-page'               => 'Desplaçar $1',
'move-page-legend'        => 'Tornar nomenar un article',
'movepagetext'            => "Utilizatz lo formulari çaijós per tornar nomenar una pagina, en desplaçant tot son istoric cap al nom novèl.
Lo títol vendrà una pagina de redireccion cap al títol novèl.
Los ligams vèrs lo títol de la pagina anciana seràn pas cambiats ;
verificatz qu'aqueste desplaçament a pas creat de redireccion dobla.
Vos devètz assegurar que los ligams contunhan de punter cap a lor destinacion supausada.

Una pagina serà pas desplaçada se la pagina del títol novèl existís ja, a mens qu'aquesta darrièra siá voida o en redireccion, e qu’aja pas d’istoric.
Aquò vòl dire que podètz tornar nomenar una pagina vèrs sa posicion d’origina s'avètz fach una error, mas que podètz pas escafar una pagina qu'existís ja amb aqueste procediment.

'''ATENCION !'''
Aquò pòt provocar un cambiament radical e imprevist per una pagina consultada frequentament.
Asseguratz-vos de n'avoir compres las consequéncias abans de contunhar.",
'movepagetalktext'        => "La pagina de discussion associada, se presenta, serà automaticament desplaçada amb '''en defòra de se:'''
*Desplaçatz una pagina vèrs un autre espaci,
*Una pagina de discussion ja existís amb lo nom novèl, o
*Avètz deseleccionat lo boton çaijós.

Dins aqueste cas, deuretz desplaçar o fusionar la pagina manualament se o volètz.",
'movearticle'             => "Tornar nomenar l'article",
'movenologin'             => 'Vos sètz pas identificat(ada)',
'movenologintext'         => "Per poder tornar nomenar un article, vos cal èsser [[Special:Userlogin|connectat(ada)]]
en tant qu'utilizaire enregistrat.",
'movenotallowed'          => 'Avètz pas la permission de tornar nomenar de paginas sus {{SITENAME}}.',
'newtitle'                => 'Títol novèl',
'move-watch'              => 'Seguir aquesta pagina',
'movepagebtn'             => "Tornar nomenar l'article",
'pagemovedsub'            => 'Renomenatge capitat',
'movepage-moved'          => 'La pagina « $1 » <small>([[Special:Whatlinkshere/$3|ligams]])</small> es estada renomenada en « $2 » <small>([[Special:Whatlinkshere/$4|ligams]])</small>. Verificatz qu’existís pas cap de redireccion dobla, e corregissetz-las se mestièr fa.', # The two titles are passed in plain text as $3 and $4 to allow additional goodies in the message.
'articleexists'           => "Existís ja un article que pòrta aqueste títol, o lo títol qu'avètz causit es pas valid.
Causissètz-ne un autre.",
'cantmove-titleprotected' => 'Avètz pas la possibilitat de desplaçar una pagina vèrs aqueste emplaçament perque lo títol es estat protegit a la creacion.',
'talkexists'              => "La pagina ela-meteissa es estada desplaçada amb succès, mas
la pagina de discussion a pas pogut èsser desplaçada perque n'existissiá ja una
jol nom novèl. Se vos plai, fusionatz-las manualament.",
'movedto'                 => 'renomenat en',
'movetalk'                => 'Tornar nomenar tanben la pagina "discussion", se fa mestièr.',
'talkpagemoved'           => 'La pagina de discussion correspondenta tanben es estada desplaçada.',
'talkpagenotmoved'        => 'La pagina de discussion correspondenta es <strong>pas</strong> estada desplaçada.',
'1movedto2'               => 'a renomenat [[$1]] en [[$2]]',
'1movedto2_redir'         => 'a redirigit [[$1]] vèrs [[$2]]',
'movelogpage'             => 'Istoric dels renomenatges',
'movelogpagetext'         => 'Vaquí la lista de las darrièras paginas renomenadas.',
'movereason'              => 'Motiu :',
'revertmove'              => 'anullar',
'delete_and_move'         => 'Suprimir e tornar nomenar',
'delete_and_move_text'    => '==Supression requesida==
L’article de destinacion « [[$1]] » existís ja.
Volètz lo suprimir per permetre lo renomenatge ?',
'delete_and_move_confirm' => 'Òc, accèpti de suprimir la pagina de destinacion per permetre lo renomenatge.',
'delete_and_move_reason'  => 'Pagina suprimida per permetre un renomenatge',
'selfmove'                => 'Los títols d’origina e de destinacion son los meteisses : impossible de tornar nomenar una pagina sus ela-meteissa.',
'immobile_namespace'      => 'Lo títol de destinacion es d’un tipe especial ; es impossible de tornar nomenar de paginas vèrs aqueste espaci de noms.',

# Export
'export'            => 'Exportar de paginas',
'exporttext'        => "Podètz exportar en XML lo tèxt e l’istoric d’una pagina o d’un ensemble de paginas; lo resultat pòt alara èsser importat dins un autre wiki foncionant amb lo logicial MediaWiki.

Per exportar de paginas, entratz lors títols dins la boita de tèxt çai jos, un títol per linha, e seleccionatz se o desiratz o pas la version actuala amb totas las versions ancianas, amb la pagina d’istoric, o simplament la pagina actuala amb d'informacions sus la darrièra modificacion.

Dins aqueste darrièr cas, podètz tanben utilizar un ligam, coma [[{{ns:special}}:Export/{{Mediawiki:mainpage}}]] per la pagina {{Mediawiki:mainpage}}.",
'exportcuronly'     => 'Exportar unicament la version correnta sens l’istoric complet',
'exportnohistory'   => "----
'''Nòta :''' l’exportacion complèta de l’istoric de las paginas amb l’ajuda d'aqueste formulari es estada desactivada per de rasons de performàncias.",
'export-submit'     => 'Exportar',
'export-addcattext' => 'Apondre las paginas de la categoria :',
'export-addcat'     => 'Apondre',
'export-download'   => 'Salvagardar en tant que fichièr',
'export-templates'  => 'Inclure los modèls',

# Namespace 8 related
'allmessages'               => 'Lista dels messatges del sistèma',
'allmessagesname'           => 'Nom del camp',
'allmessagesdefault'        => 'Messatge per defaut',
'allmessagescurrent'        => 'Messatge actual',
'allmessagestext'           => 'Aquò es la lista de totes los messatges disponibles dins l’espaci MediaWiki.
Visitatz la [http://www.mediawiki.org/wiki/Localisation Localizacion MèdiaWiki] e [http://translatewiki.net Betawiki] se desiratz contribuir a la localizacion MèdiaWiki generica.',
'allmessagesnotsupportedDB' => "'''{{ns:special}}:Allmessages''' es pas disponible perque '''\$wgUseDatabaseMessages''' es desactivat.",
'allmessagesfilter'         => 'Filtre d’expression racionala :',
'allmessagesmodified'       => 'Afichar pas que las modificacions',

# Thumbnails
'thumbnail-more'           => 'Agrandir',
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
'import-revision-count'      => '$1 {{PLURAL:$1|revision|revisions}}',
'importnopages'              => "Cap de pagina d'importar.",
'importfailed'               => 'Fracàs de l’impòrt : $1',
'importunknownsource'        => 'Tipe de la font d’impòrt desconegut',
'importcantopen'             => "Impossible de dobrir lo fichièr d'importar",
'importbadinterwiki'         => 'Marrit ligam interwiki',
'importnotext'               => 'Void o sens tèxt',
'importsuccess'              => 'Impòrt capitat!',
'importhistoryconflict'      => "I a un conflicte dins l'istoric de las versions (aquesta pagina a pogut èsser importada de per abans).",
'importnosources'            => 'Cap de font inter-wiki es pas estada definida e la còpia dirècta d’istoric es desactivada.',
'importnofile'               => 'Cap de fichièr es pas estat importat.',
'importuploaderrorsize'      => "Lo telecargament del fichièr d'importar a pas capitat. Sa talha es mai granda que la autorizada.",
'importuploaderrorpartial'   => "Lo telecargament del fichièr d'importar a pas capitat. Aqueste o es pas estat que parcialament.",
'importuploaderrortemp'      => "Lo telecargament del fichièr d'importar a pas capitat. Un dorsièr temporari es mancant.",
'import-parse-failure'       => "Ruptura dins l'analisi de l'impòrt XML",
'import-noarticle'           => "Pas de pagina d'importar !",
'import-nonewrevisions'      => 'Totas las revisions son estadas importadas deperabans.',
'xml-error-string'           => '$1 a la linha $2, col $3 (octet $4) : $5',

# Import log
'importlogpage'                    => 'Istoric de las importacions de paginas',
'importlogpagetext'                => 'Impòrts administratius de paginas amb l’istoric a partir dels autres wikis.',
'import-logentry-upload'           => 'a importat (telecargament) $1',
'import-logentry-upload-detail'    => '$1 {{PLURAL:$1|version|versions}}',
'import-logentry-interwiki'        => '$1 version(s) dempuèi $2',
'import-logentry-interwiki-detail' => '$1 {{PLURAL:$1|version|versions}} dempuèi $2',

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
'tooltip-ca-talk'                 => "Discussion a prepaus d'aquesta pagina",
'tooltip-ca-edit'                 => 'Podètz modificar aquesta pagina. Mercé de previsualizar abans d’enregistrar.',
'tooltip-ca-addsection'           => 'Apondre un comentari a aquesta discussion.',
'tooltip-ca-viewsource'           => 'Aquesta pagina es protegida. Çaquelà, ne podètz veire lo contengut.',
'tooltip-ca-history'              => "Los autors e versions precedentas d'aquesta pagina.",
'tooltip-ca-protect'              => 'Protegir aquesta pagina',
'tooltip-ca-delete'               => 'Suprimir aquesta pagina',
'tooltip-ca-undelete'             => 'Restablir aquesta pagina',
'tooltip-ca-move'                 => 'Tornar nomenar aquesta pagina',
'tooltip-ca-watch'                => 'Ajustatz aquesta pagina a vòstra lista de seguit',
'tooltip-ca-unwatch'              => 'Levatz aquesta pagina de vòstra lista de seguit',
'tooltip-search'                  => 'Cercar dins {{SITENAME}}',
'tooltip-search-go'               => 'Anar vèrs una pagina portant exactament aqueste nom se existís.',
'tooltip-search-fulltext'         => 'Recercar las paginas comportant aqueste tèxt.',
'tooltip-p-logo'                  => 'Pagina principala',
'tooltip-n-mainpage'              => 'Visitatz la pagina principala',
'tooltip-n-portal'                => 'A prepaus del projècte',
'tooltip-n-currentevents'         => "Trobar d'entresenhas suls eveniments actuals",
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
'tooltip-t-upload'                => 'Mandar un imatge o fichièr mèdia sul serveire',
'tooltip-t-specialpages'          => 'Lista de totas las paginas especialas',
'tooltip-t-print'                 => "Version imprimibla d'aquesta pagina",
'tooltip-t-permalink'             => 'Ligam permanent vèrs aquesta version de la pagina',
'tooltip-ca-nstab-main'           => 'Veire l’article',
'tooltip-ca-nstab-user'           => "Veire la pagina d'utilizaire",
'tooltip-ca-nstab-media'          => 'Veire la pagina del mèdia',
'tooltip-ca-nstab-special'        => 'Aquò es una pagina especiala, la podètz pas modificar.',
'tooltip-ca-nstab-project'        => 'Veire la pagina del projècte',
'tooltip-ca-nstab-image'          => 'Veire la pagina del fichièr',
'tooltip-ca-nstab-mediawiki'      => 'Vejatz lo messatge del sistèma',
'tooltip-ca-nstab-template'       => 'Vejatz lo modèl',
'tooltip-ca-nstab-help'           => 'Vejatz la pagina d’ajuda',
'tooltip-ca-nstab-category'       => 'Vejatz la pagina de la categoria',
'tooltip-minoredit'               => 'Marcar mas modificacions coma un cambiament menor',
'tooltip-save'                    => 'Salvagardar vòstras modificacions',
'tooltip-preview'                 => 'Mercé de previsualizar vòstras modificacions abans de salvagardar!',
'tooltip-diff'                    => "Permet de visualizar los cambiaments qu'avètz efectuats",
'tooltip-compareselectedversions' => "Afichar las diferéncias entre doas versions d'aquesta pagina",
'tooltip-watch'                   => 'Apondre aquesta pagina a vòstra lista de seguit',
'tooltip-recreate'                => 'Recrear la pagina, quitament se es estada escafada',
'tooltip-upload'                  => 'Amodar l’impòrt',

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
'anonymous'        => 'Utilizaire(s) anonim(s) de {{SITENAME}}',
'siteuser'         => 'Utilizaire $1 de {{SITENAME}}',
'lastmodifiedatby' => 'Aquesta pagina es estada modificada pel darrièr còp lo $1 a $2 per $3.', # $1 date, $2 time, $3 user
'othercontribs'    => "Contribucions de l'utilizaire $1.",
'others'           => 'autres',
'siteusers'        => 'Utilizaire(s) $1',
'creditspage'      => 'Pagina de crèdits',
'nocredits'        => 'I a pas d’informacions d’atribucion disponiblas per aquesta pagina.',

# Spam protection
'spamprotectiontitle' => 'Pagina protegida automaticament per causa de spam',
'spamprotectiontext'  => "La pagina qu'avètz ensajat de salvagardar es estada blocada pel filtre anti-spam. Aquò es probablament causat per un ligam vèrs un sit extèrn.",
'spamprotectionmatch' => 'Lo tèxt seguent a desenclavaat lo detector de spam : $1',
'spambot_username'    => 'Netejatge de spam MediaWiki',
'spam_reverting'      => 'Restauracion de la darrièra version contenent pas de ligam vèrs $1',
'spam_blanking'       => 'Totas las versions que contenon de ligams vèrs $1 son blanquidas',

# Info page
'infosubtitle'   => 'Entresenhas per la pagina',
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
'mw_math_modern' => 'Pels navigaires modèrnes',
'mw_math_mathml' => 'MathML',

# Patrolling
'markaspatrolleddiff'                 => 'Marcar coma essent pas un vandalisme',
'markaspatrolledtext'                 => 'Marcar aqueste article coma non vandalizat',
'markedaspatrolled'                   => 'Marcat coma non vandalizat',
'markedaspatrolledtext'               => 'La version seleccionada es estada marcada coma non vandalizada.',
'rcpatroldisabled'                    => 'La foncion de patrolha dels darrièrs cambiaments es pas activada.',
'rcpatroldisabledtext'                => 'La foncionalitat de susvelhança dels darrièrs cambiaments es pas activada.',
'markedaspatrollederror'              => 'Pòt pas èsser marcat coma non vandalizat',
'markedaspatrollederrortext'          => 'Vos cal seleccionar una version per poder la marcar coma non vandalizada.',
'markedaspatrollederror-noautopatrol' => 'Avètz pas lo drech de marcar vòstras pròprias modificacions coma susvelhadas.',

# Patrol log
'patrol-log-page' => 'Istoric de las versions patrolhadas',
'patrol-log-line' => 'a marcat la version $1 de $2 coma verificada $3',
'patrol-log-auto' => '(automatic)',
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
'newimages'             => 'Galariá de fichièrs novèls',
'imagelisttext'         => "Vaquí una lista de '''$1''' {{PLURAL:$1|fichièr|fichièrs}} classats $2.",
'newimages-summary'     => 'Aquesta pagina especiala aficha los darrièrs fichièrs importats',
'showhidebots'          => '($1 bòts)',
'noimages'              => "Cap imatge d'afichar.",
'ilsubmit'              => 'Cercar',
'bydate'                => 'per data',
'sp-newimages-showfrom' => 'Afichar los imatges importats dempuèi lo $2, $1',

# Bad image list
'bad_image_list' => "Lo format es lo seguent :

Solas las listas d'enumeracion (las linhas començant per *) son presas en compte. Lo primièr ligam d'una linha deu èsser cap a un imatge marrit.
Los autres ligams sus la meteissa linha son considerats coma d'excepcions, per exemple d'articles sulsquals l'imatge deu aparéisser.",

# Metadata
'metadata'          => 'Metadonadas',
'metadata-help'     => "Aqueste fichièr conten d'entresenhas suplementàrias probablament ajustadas per l’aparelh de fòto numeric o l'escanèr que las a aquesas. Se lo fichièr es estat modificat dempuèi son estat original, cèrts detalhs pòdon reflectís pas entièrament l’imatge modificat.",
'metadata-expand'   => 'Mostrar las entresenhas detalhadas',
'metadata-collapse' => 'Amagar las entresenhas detalhadas',
'metadata-fields'   => 'Los camps de metadonadas d’EXIF listats dins aqueste message seràn incluses dins la pagina de descripcion de l’imatge quand la taula de metadonadas serà reduccha. Los autres camps seràn amagats per defaut.
* make
* model
* datetimeoriginal
* exposuretime
* fnumber
* focallength', # Do not translate list items

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
'exif-flash'                       => 'Flash',
'exif-focallength'                 => 'Longor de focala',
'exif-subjectarea'                 => 'Emplaçament del subjècte',
'exif-flashenergy'                 => 'Energia del flash',
'exif-spatialfrequencyresponse'    => 'Responsa en frequéncia espaciala',
'exif-focalplanexresolution'       => 'Resolucion orizontala focala plana',
'exif-focalplaneyresolution'       => 'Resolucion verticala focala plana',
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
'exif-contrast'                    => 'Contraste',
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

'exif-orientation-1' => 'Normal', # 0th row: top; 0th column: left
'exif-orientation-2' => 'Inversada orizontalament', # 0th row: top; 0th column: right
'exif-orientation-3' => 'Virada de 180°', # 0th row: bottom; 0th column: right
'exif-orientation-4' => 'Inversada verticalament', # 0th row: bottom; 0th column: left
'exif-orientation-5' => 'Virada de 90° a esquèrra e inversada verticalament', # 0th row: left; 0th column: top
'exif-orientation-6' => 'Virada de 90° a drecha', # 0th row: right; 0th column: top
'exif-orientation-7' => 'Virada de 90° a drecha e inversada verticalament', # 0th row: right; 0th column: bottom
'exif-orientation-8' => 'Virada de 90° a esquèrra', # 0th row: left; 0th column: bottom

'exif-planarconfiguration-1' => 'Donadas atenentas',
'exif-planarconfiguration-2' => 'Donadas separadas',

'exif-colorspace-ffff.h' => 'Pas calibrat',

'exif-componentsconfiguration-0' => 'existís pas',
'exif-componentsconfiguration-5' => 'V',

'exif-exposureprogram-0' => 'Indefinit',
'exif-exposureprogram-1' => 'Manual',
'exif-exposureprogram-2' => 'Programa normal',
'exif-exposureprogram-3' => 'Prioritat a la dobertura',
'exif-exposureprogram-4' => 'Prioritat a l’obturacion',
'exif-exposureprogram-5' => 'Programa creacion (preferéncia a la prigondor de camp)',
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
'exif-lightsource-2'   => 'Fluorescent',
'exif-lightsource-3'   => 'Tungstèn (lum incandescent)',
'exif-lightsource-4'   => 'Flash',
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

'exif-filesource-3' => 'Aparelh fotografic numeric',

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

'exif-contrast-0' => 'Normal',
'exif-contrast-1' => 'Feble',
'exif-contrast-2' => 'Fòrt',

'exif-saturation-0' => 'Normal',
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
'monthsall'        => 'totes',

# E-mail address confirmation
'confirmemail'             => "Confirmar l'adreça de corrièr electronic",
'confirmemail_noemail'     => 'L’adreça de corrièr electronic configurada dins vòstras [[Special:Preferences|preferéncias]] es pas valida.',
'confirmemail_text'        => '{{SITENAME}} necessita la verificacion de vòstra adreça de corrièr electronic abans de poder utilizar tota foncion de messatjariá. Utilizatz lo boton çaijós per mandar un corrièr electronic de confirmacion a vòstra adreça. Lo corrièr contendrà un ligam contenent un còde, cargatz aqueste ligam dins vòstre navigaire per validar vòstra adreça.',
'confirmemail_pending'     => '<div class="error">
Un còde de confirmacion ja vos es estat mandat per corrièr electronic ; se venètz de crear vòstre compte, esperatz qualques minutas que l’e-mail arribe abans de demandar un còde novèl. </div>',
'confirmemail_send'        => 'Mandar un còde de confirmacion',
'confirmemail_sent'        => 'Corrièr electronic de confirmacion mandat.',
'confirmemail_oncreate'    => "Un còde de confirmacion es estat mandat a vòstra adreça de corrièr electronic.
Aqueste còde es pas requesit per se connectar, mas n'aurètz besonh per activar las foncionalitats ligadas als corrièrs electronics sus aqueste wiki.",
'confirmemail_sendfailed'  => 'Impossible de mandar lo corrièr electronic de confirmacion.

Verificatz vòstra adreça. Retorn del programa de corrièr electronic : $1',
'confirmemail_invalid'     => 'Còde de confirmacion incorrècte. Benlèu lo còde a expirat.',
'confirmemail_needlogin'   => 'Vos devètz $1 per confirmar vòstra adreça de corrièr electronic.',
'confirmemail_success'     => 'Vòstra adreça de corrièr electronic es confirmada. Ara, vos podètz connectar e aprofechar del wiki.',
'confirmemail_loggedin'    => 'Ara, vòstra adreça es confirmada',
'confirmemail_error'       => 'Un problèma es subrevengut en volent enregistrar vòstra confirmacion',
'confirmemail_subject'     => 'Confirmacion d’adreça de corrièr electronic per {{SITENAME}}',
'confirmemail_body'        => "Qualqu’un, probablament vos amb l’adreça IP $1, a enregistrat un compte « $2 » amb aquesta adreça de corrièr electronic sul sit {{SITENAME}}.

Per confirmar qu'aqueste compte vos aparten vertadièrament e activar las foncions de messatjariá sus {{SITENAME}}, seguissètz lo ligam çaijós dins vòstre navigaire :

$3

Se s’agís pas de vos, dobrissetz pas lo ligam.
Aqueste còde de confirmacion expirarà lo $4, seguissètz l’autre ligam çaijós dins vòstre navigaire :

$5

Aqueste còde de confirmacion expirarà lo $4.",
'confirmemail_invalidated' => 'Confirmacion de l’adreça de corrièr electronic anullada',
'invalidateemail'          => 'Anullar la confirmacion del corrièr electronic',

# Scary transclusion
'scarytranscludedisabled' => '[La transclusion interwiki es desactivada]',
'scarytranscludefailed'   => '[La recuperacion de modèl a pas capitat per $1 ; o planhèm]',
'scarytranscludetoolong'  => '[L’URL es tròp longa ; o planhèm]',

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
'confirmrecreate'     => "L'utilizaire [[User:$1|$1]] ([[User talk:$1|talk]]) a suprimit aquesta pagina, alara que l'aviatz començat d'editar, pel motiu seguent:
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
'useajaxsearch'    => 'Utilizar la recèrca AJAX',

# Separators for various lists, etc.
'colon-separator'    => '&nbsp;:&#32;',
'autocomment-prefix' => '-',

# Multipage image navigation
'imgmultipageprev' => '&larr; pagina precedenta',
'imgmultipagenext' => 'pagina seguenta &rarr;',
'imgmultigo'       => 'Accedir !',
'imgmultigotopre'  => 'Accedir a la pagina',

# Table pager
'ascending_abbrev'         => 'asc',
'descending_abbrev'        => 'desc',
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
'autosumm-new'     => 'Pagina novèla : $1',

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
'watchlistedit-normal-explain' => 'Las paginas de vòstra lista de seguit son visiblas çaijós, classadas per espaci de noms. Per levar una pagina (e sa pagina de discussion) de la lista, seleccionatz la casa al costat puèi clicatz sul boton en bas. Tanben podètz [[Special:Watchlist/raw|la modificar en mòde brut]].',
'watchlistedit-normal-submit'  => 'Levar las paginas seleccionadas',
'watchlistedit-normal-done'    => '{{PLURAL:$1|Una pagina es estada levada|$1 paginas son estadas levadas}} de vòstra lista de seguit :',
'watchlistedit-raw-title'      => 'Modificacion de la lista de seguit (mòde brut)',
'watchlistedit-raw-legend'     => 'Modificacion de la lista de seguit en mòde brut',
'watchlistedit-raw-explain'    => 'La lista de las paginas de vòstra lista de seguit es mostrada çaijós, sens las paginas de discussion (automaticament inclusas) e destriadas per espaci de noms. Podètz modificar la lista : ajustatz las paginas que volètz seguir (pauc impòrta ont), una pagina per linha, e levatz las paginas que volètz pas mai seguir. Quand avètz acabat, clicatz sul boton en bas per metre la lista a jorn. Tanben podètz utilizar [[Special:Watchlist/edit|l’editaire normal]].',
'watchlistedit-raw-titles'     => 'Títols :',
'watchlistedit-raw-submit'     => 'Metre a jorn la lista',
'watchlistedit-raw-done'       => 'Vòstra lista de seguit es estada mesa a jorn.',
'watchlistedit-raw-added'      => '{{PLURAL:$1|Una pagina es estada ajustada|$1 paginas son estadas ajustadas}} :',
'watchlistedit-raw-removed'    => '{{PLURAL:$1|Una pagina es estada levada|$1 paginas son estadas levadas}} :',

# Watchlist editing tools
'watchlisttools-view' => 'Lista de seguit',
'watchlisttools-edit' => 'Veire e modificar la lista de seguit',
'watchlisttools-raw'  => 'Modificar la lista (mòde brut)',

# Core parser functions
'unknown_extension_tag' => "Balisa d'extension « $1 » desconeguda",

# Special:Version
'version'                          => 'Version', # Not used as normal message but as header for the special page itself
'version-extensions'               => 'Extensions installadas',
'version-specialpages'             => 'Paginas especialas',
'version-parserhooks'              => 'Extensions del parsaire',
'version-variables'                => 'Variablas',
'version-other'                    => 'Autre',
'version-mediahandlers'            => 'Supòrts mèdia',
'version-hooks'                    => 'Croquets',
'version-extension-functions'      => 'Foncions de las extensions',
'version-parser-extensiontags'     => 'Balisas suplementàrias del parsaire',
'version-parser-function-hooks'    => 'Croquets de las foncions del parsaire',
'version-skin-extension-functions' => "Foncions d'extension de l'interfàcia",
'version-hook-name'                => 'Nom del croquet',
'version-hook-subscribedby'        => 'Definit per',
'version-version'                  => 'Version',
'version-license'                  => 'Licéncia',
'version-software'                 => 'Logicial installat',
'version-software-product'         => 'Produch',
'version-software-version'         => 'Version',

# Special:Filepath
'filepath'         => "Camin d'accès d'un fichièr",
'filepath-page'    => 'Fichièr:',
'filepath-submit'  => "Camin d'accès",
'filepath-summary' => "Aquesta pagina especiala balha lo camin d'accès complet d’un fichièr ; los imatges son mostrats en nauta resolucion, los fichièrs audiò e vidèo s’executisson amb lor programa associat. Picatz lo nom del fichièr sens lo prefix « {{ns:image}}: »",

# Special:FileDuplicateSearch
'fileduplicatesearch'          => 'Recèrca dels fichièrs en doble',
'fileduplicatesearch-summary'  => 'Recèrca per de fichièrs en doble sus la banca de valors fragmentàrias.

Picatz lo nom del fichièr sens lo prefix « {{ns:image}}: ».',
'fileduplicatesearch-legend'   => 'Recèrca d’un doble',
'fileduplicatesearch-filename' => 'Nom del fichièr :',
'fileduplicatesearch-submit'   => 'Recercar',
'fileduplicatesearch-info'     => '$1 × $2 pixels<br />Talha del fichièr : $3<br />MIME type : $4',
'fileduplicatesearch-result-1' => 'Lo fichièr « $1 » a pas de doble identic.',
'fileduplicatesearch-result-n' => 'Lo fichièr « $1 » a {{PLURAL:$2|1 doble identic|$2 dobles identics}}.',

# Special:SpecialPages
'specialpages-group-maintenance' => 'Rapòrts de mantenença',
'specialpages-group-other'       => 'Autras paginas especialas',
'specialpages-group-login'       => 'Se connectar / s’enregistrar',
'specialpages-group-changes'     => 'Darrièrs cambiaments e jornals',
'specialpages-group-media'       => 'Rapòrts dels fichièrs de mèdias e dels impòrts',
'specialpages-group-users'       => 'Utilizaires e dreches estacats',
'specialpages-group-needy'       => 'Paginas que necessitan de trabalhs',
'specialpages-group-highuse'     => 'Utilizacion intensa de las paginas',
'specialpages-group-permissions' => 'Permissions dels utilizaires',

);
