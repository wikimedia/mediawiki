<?php
/** Galician (Galego)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Alma
 * @author Gallaecio
 * @author Lameiro
 * @author Prevert
 * @author Toliño
 * @author Xosé
 * @author לערי ריינהארט
 */

$fallback = 'pt';

$namespaceNames = array(
	NS_MEDIA            => 'Media',
	NS_SPECIAL          => 'Especial',
	NS_TALK             => 'Conversa',
	NS_USER             => 'Usuario',
	NS_USER_TALK        => 'Conversa_usuario',
	NS_PROJECT_TALK     => 'Conversa_$1',
	NS_FILE             => 'Ficheiro',
	NS_FILE_TALK        => 'Conversa_ficheiro',
	NS_MEDIAWIKI        => 'MediaWiki',
	NS_MEDIAWIKI_TALK   => 'Conversa_MediaWiki',
	NS_TEMPLATE         => 'Modelo',
	NS_TEMPLATE_TALK    => 'Conversa_modelo',
	NS_HELP             => 'Axuda',
	NS_HELP_TALK        => 'Conversa_axuda',
	NS_CATEGORY         => 'Categoría',
	NS_CATEGORY_TALK    => 'Conversa_categoría',
);

$namespaceAliases = array(
	'Conversa_Usuario' => NS_USER_TALK,
	'Imaxe' => NS_FILE,
	'Conversa_Imaxe' => NS_FILE_TALK,
	'Conversa_Modelo' => NS_TEMPLATE_TALK,
	'Conversa_Axuda' => NS_HELP_TALK,
	'Conversa_Categoría' => NS_CATEGORY_TALK,
);

$defaultDateFormat = 'dmy';

$dateFormats = array(
	'dmy time' => 'H:i',
	'dmy date' => 'j \d\e F \d\e Y',
	'dmy both' => 'H:i\,\ j \d\e F \d\e Y',
);

$specialPageAliases = array(
	'DoubleRedirects'           => array( 'Redireccións dobres' ),
	'BrokenRedirects'           => array( 'Redireccións rotas' ),
	'Disambiguations'           => array( 'Homónimos' ),
	'Userlogin'                 => array( 'Rexistro' ),
	'Userlogout'                => array( 'Saír ao anonimato' ),
	'CreateAccount'             => array( 'Crear unha conta' ),
	'Preferences'               => array( 'Preferencias' ),
	'Watchlist'                 => array( 'Lista de vixilancia' ),
	'Recentchanges'             => array( 'Cambios recentes' ),
	'Upload'                    => array( 'Cargar' ),
	'Listfiles'                 => array( 'Lista de imaxes' ),
	'Newimages'                 => array( 'Imaxes novas' ),
	'Listusers'                 => array( 'Lista de usuarios' ),
	'Listgrouprights'           => array( 'Lista de dereitos segundo o grupo' ),
	'Statistics'                => array( 'Estatísticas' ),
	'Randompage'                => array( 'Ao chou', 'Páxina aleatoria' ),
	'Lonelypages'               => array( 'Páxinas orfas' ),
	'Uncategorizedpages'        => array( 'Páxinas sen categoría' ),
	'Uncategorizedcategories'   => array( 'Categorías sen categoría' ),
	'Uncategorizedimages'       => array( 'Imaxes sen categoría' ),
	'Uncategorizedtemplates'    => array( 'Modelos sen categoría' ),
	'Unusedcategories'          => array( 'Categorías sen uso' ),
	'Unusedimages'              => array( 'Imaxes sen uso' ),
	'Wantedpages'               => array( 'Páxinas requiridas', 'Ligazóns rotas' ),
	'Wantedcategories'          => array( 'Categorías requiridas' ),
	'Wantedfiles'               => array( 'Ficheiros requiridos' ),
	'Wantedtemplates'           => array( 'Modelos requiridos' ),
	'Mostlinked'                => array( 'Páxinas máis ligadas' ),
	'Mostlinkedcategories'      => array( 'Categorías máis ligadas' ),
	'Mostlinkedtemplates'       => array( 'Modelos máis enlazados' ),
	'Mostimages'                => array( 'Máis imaxes' ),
	'Mostcategories'            => array( 'Máis categorías' ),
	'Mostrevisions'             => array( 'Máis revisións' ),
	'Fewestrevisions'           => array( 'Menos revisións' ),
	'Shortpages'                => array( 'Páxinas curtas' ),
	'Longpages'                 => array( 'Páxinas longas' ),
	'Newpages'                  => array( 'Páxinas novas' ),
	'Ancientpages'              => array( 'Páxinas máis antigas' ),
	'Deadendpages'              => array( 'Páxinas mortas' ),
	'Protectedpages'            => array( 'Páxinas protexidas' ),
	'Protectedtitles'           => array( 'Títulos protexidos' ),
	'Allpages'                  => array( 'Todas as páxinas' ),
	'Prefixindex'               => array( 'Índice de prefixos' ),
	'Ipblocklist'               => array( 'Lista dos bloqueos a enderezos IP' ),
	'Specialpages'              => array( 'Páxinas especiais' ),
	'Contributions'             => array( 'Contribucións' ),
	'Emailuser'                 => array( 'Correo electrónico de usuario' ),
	'Confirmemail'              => array( 'Confirmar correo electrónico' ),
	'Whatlinkshere'             => array( 'Páxinas que ligan con esta' ),
	'Recentchangeslinked'       => array( 'Cambios relacionados' ),
	'Movepage'                  => array( 'Mover páxina' ),
	'Blockme'                   => array( 'Bloquearme' ),
	'Booksources'               => array( 'Fontes bibliográficas' ),
	'Categories'                => array( 'Categorías' ),
	'Export'                    => array( 'Exportar' ),
	'Version'                   => array( 'Versión' ),
	'Allmessages'               => array( 'Todas as mensaxes' ),
	'Log'                       => array( 'Rexistros' ),
	'Blockip'                   => array( 'Bloquear enderezo IP' ),
	'Undelete'                  => array( 'Restaurar' ),
	'Import'                    => array( 'Importar' ),
	'Lockdb'                    => array( 'Pechar a base de datos' ),
	'Unlockdb'                  => array( 'Abrir a base de datos' ),
	'Userrights'                => array( 'Dereitos de usuario' ),
	'MIMEsearch'                => array( 'Procura MIME' ),
	'FileDuplicateSearch'       => array( 'Procura de ficheiros duplicados' ),
	'Unwatchedpages'            => array( 'Páxinas sen vixiar' ),
	'Listredirects'             => array( 'Lista de redireccións' ),
	'Revisiondelete'            => array( 'Revisións borradas' ),
	'Unusedtemplates'           => array( 'Modelos non usados' ),
	'Randomredirect'            => array( 'Redirección aleatoria' ),
	'Mypage'                    => array( 'A miña páxina de usuario' ),
	'Mytalk'                    => array( 'A miña conversa' ),
	'Mycontributions'           => array( 'As miñas contribucións' ),
	'Listadmins'                => array( 'Lista de administradores' ),
	'Listbots'                  => array( 'Lista de bots' ),
	'Popularpages'              => array( 'Páxinas populares' ),
	'Search'                    => array( 'Procurar' ),
	'Resetpass'                 => array( 'Cambiar contrasinal' ),
	'Withoutinterwiki'          => array( 'Sen interwiki' ),
	'MergeHistory'              => array( 'Fusionar Historiais' ),
	'Filepath'                  => array( 'Enderezo de ficheiro' ),
	'Invalidateemail'           => array( 'Invalidar o enderezo de correo electrónico' ),
	'Blankpage'                 => array( 'Baleirar a páxina' ),
	'LinkSearch'                => array( 'Buscar ligazóns web' ),
	'DeletedContributions'      => array( 'Contribucións borradas' ),
	'Tags'                      => array( 'Etiquetas' ),
);

$magicWords = array(
	'redirect'              => array( '0', '#REDIRECCIÓN', '#REDIRECIONAMENTO', '#REDIRECT' ),
	'notoc'                 => array( '0', '__SENÍNDICE__', '__SEMTDC__', '__SEMSUMÁRIO__', '__NOTOC__' ),
	'nogallery'             => array( '0', '__SENGALERÍA__', '__SEMGALERIA__', '__NOGALLERY__' ),
	'forcetoc'              => array( '0', '__FORZAROÍNDICE__', '__FORCARTDC__', '__FORCARSUMARIO__', '__FORÇARTDC__', '__FORÇARSUMÁRIO__', '__FORCETOC__' ),
	'toc'                   => array( '0', '__Índice__', '__TDC__', '__SUMÁRIO__', '__TOC__' ),
	'noeditsection'         => array( '0', '__SECCIÓNSNONEDITABLES__', '__NÃOEDITARSEÇÃO__', '__SEMEDITARSEÇÃO__', '__NOEDITSECTION__' ),
	'currentmonth'          => array( '1', 'MESACTUAL', 'MESATUAL', 'MESATUAL2', 'CURRENTMONTH', 'CURRENTMONTH2' ),
	'currentmonthname'      => array( '1', 'NOMEDOMESACTUAL', 'NOMEDOMESATUAL', 'CURRENTMONTHNAME' ),
	'currentmonthabbrev'    => array( '1', 'ABREVIATURADOMESACTUAL', 'MESATUALABREV', 'MESATUALABREVIADO', 'ABREVIATURADOMESATUAL', 'CURRENTMONTHABBREV' ),
	'currentday'            => array( '1', 'DÍAACTUAL', 'DIAATUAL', 'CURRENTDAY' ),
	'currentday2'           => array( '1', 'DÍAACTUAL2', 'DIAATUAL2', 'CURRENTDAY2' ),
	'currentdayname'        => array( '1', 'NOMEDODÍAACTUAL', 'NOMEDODIAATUAL', 'CURRENTDAYNAME' ),
	'currentyear'           => array( '1', 'ANOACTUAL', 'ANOATUAL', 'CURRENTYEAR' ),
	'currenthour'           => array( '1', 'HORAACTUAL', 'HORAATUAL', 'CURRENTHOUR' ),
	'localmonth'            => array( '1', 'MESLOCAL', 'LOCALMONTH', 'LOCALMONTH2' ),
	'localmonthname'        => array( '1', 'NOMEDOMESLOCAL', 'LOCALMONTHNAME' ),
	'localmonthabbrev'      => array( '1', 'ABREVIATURADOMESLOCAL', 'MESLOCALABREV', 'MESLOCALABREVIADO', 'LOCALMONTHABBREV' ),
	'localday'              => array( '1', 'DÍALOCAL', 'DIALOCAL', 'LOCALDAY' ),
	'localday2'             => array( '1', 'DÍALOCAL2', 'DIALOCAL2', 'LOCALDAY2' ),
	'localdayname'          => array( '1', 'NOMEDODÍALOCAL', 'NOMEDODIALOCAL', 'LOCALDAYNAME' ),
	'localyear'             => array( '1', 'ANOLOCAL', 'LOCALYEAR' ),
	'localhour'             => array( '1', 'HORALOCAL', 'LOCALHOUR' ),
	'numberofpages'         => array( '1', 'NÚMERODEPÁXINAS', 'NUMERODEPAGINAS', 'NÚMERODEPÁGINAS', 'NUMBEROFPAGES' ),
	'numberofarticles'      => array( '1', 'NÚMERODEARTIGOS', 'NUMERODEARTIGOS', 'NUMBEROFARTICLES' ),
	'numberoffiles'         => array( '1', 'NÚMERODEFICHEIROS', 'NUMERODEARQUIVOS', 'NÚMERODEARQUIVOS', 'NUMBEROFFILES' ),
	'numberofusers'         => array( '1', 'NÚMERODEUSUARIOS', 'NUMERODEUSUARIOS', 'NÚMERODEUSUÁRIOS', 'NUMBEROFUSERS' ),
	'numberofedits'         => array( '1', 'NÚMERODEEDICIÓNS', 'NUMERODEEDICOES', 'NÚMERODEEDIÇÕES', 'NUMBEROFEDITS' ),
	'pagename'              => array( '1', 'NOMEDAPÁXINA', 'NOMEDAPAGINA', 'NOMEDAPÁGINA', 'PAGENAME' ),
	'namespace'             => array( '1', 'ESPAZODENOMES', 'DOMINIO', 'DOMÍNIO', 'ESPACONOMINAL', 'ESPAÇONOMINAL', 'NAMESPACE' ),
	'fullpagename'          => array( '1', 'NOMECOMPLETODAPÁXINA', 'NOMECOMPLETODAPAGINA', 'NOMECOMPLETODAPÁGINA', 'FULLPAGENAME' ),
	'subpagename'           => array( '1', 'NOMEDASUBPÁXINA', 'NOMEDASUBPAGINA', 'NOMEDASUBPÁGINA', 'SUBPAGENAME' ),
	'basepagename'          => array( '1', 'NOMEDAPÁXINABASE', 'NOMEDAPAGINABASE', 'NOMEDAPÁGINABASE', 'BASEPAGENAME' ),
	'talkpagename'          => array( '1', 'NOMEDAPÁXINADECONVERSA', 'NOMEDAPAGINADEDISCUSSAO', 'NOMEDAPÁGINADEDISCUSSÃO', 'TALKPAGENAME' ),
	'img_manualthumb'       => array( '1', 'miniatura=$1', 'mini=$1', 'miniaturadaimagem=$1', 'thumbnail=$1', 'thumb=$1' ),
	'img_right'             => array( '1', 'dereita', 'direita', 'right' ),
	'img_left'              => array( '1', 'esquerda', 'left' ),
	'img_none'              => array( '1', 'ningún', 'nenhum', 'none' ),
	'img_center'            => array( '1', 'centro', 'center', 'centre' ),
	'img_page'              => array( '1', 'páxina=$1', 'páxina $1', 'página=$1', 'página $1', 'page=$1', 'page $1' ),
	'img_border'            => array( '1', 'borde', 'borda', 'border' ),
	'grammar'               => array( '0', 'GRAMÁTICA:', 'GRAMMAR:' ),
	'displaytitle'          => array( '1', 'AMOSAROTÍTULO', 'EXIBETITULO', 'EXIBETÍTULO', 'DISPLAYTITLE' ),
	'newsectionlink'        => array( '1', '__LIGAZÓNDANOVASECCIÓN__', '__LINKDENOVASECAO__', '__LINKDENOVASEÇÃO__', '__LIGACAODENOVASECAO__', '__LIGAÇÃODENOVASEÇÃO__', '__NEWSECTIONLINK__' ),
	'language'              => array( '0', '#LINGUA:', '#IDIOMA:', '#LANGUAGE:' ),
	'numberofadmins'        => array( '1', 'NÚMERODEADMINISTRADORES', 'NUMERODEADMINISTRADORES', 'NUMBEROFADMINS' ),
	'special'               => array( '0', 'especial', 'special' ),
	'tag'                   => array( '0', 'etiqueta', 'tag' ),
	'hiddencat'             => array( '1', '__CATEGORÍAOCULTA__', '__CATEGORIAOCULTA__', '__CATOCULTA__', '__HIDDENCAT__' ),
	'pagesincategory'       => array( '1', 'PÁXINASNACATEGORÍA', 'PAGINASNACATEGORIA', 'PÁGINASNACATEGORIA', 'PAGINASNACAT', 'PÁGINASNACAT', 'PAGESINCATEGORY', 'PAGESINCAT' ),
	'pagesize'              => array( '1', 'TAMAÑODAPÁXINA', 'TAMANHODAPAGINA', 'TAMANHODAPÁGINA', 'PAGESIZE' ),
);

$separatorTransformTable = array(',' => '.', '.' => ',' );

$messages = array(
# User preference toggles
'tog-underline'               => 'Subliñar as ligazóns:',
'tog-highlightbroken'         => 'Darlles formato ás ligazóns crebadas <a href="" class="new">deste xeito</a> (alternativa: así<a href="" class="internal">?</a>).',
'tog-justify'                 => 'Xustificar os parágrafos',
'tog-hideminor'               => 'Agochar as edicións pequenas na páxina de cambios recentes',
'tog-hidepatrolled'           => 'Agochar as edicións patrulladas nos cambios recentes',
'tog-newpageshidepatrolled'   => 'Agochar as páxinas revisadas da lista de páxinas novas',
'tog-extendwatchlist'         => 'Expandir a lista de vixilancia para mostrar todos os cambios e non só os máis recentes',
'tog-usenewrc'                => 'Usar os cambios recentes avanzados (require JavaScript)',
'tog-numberheadings'          => 'Numerar automaticamente as cabeceiras',
'tog-showtoolbar'             => 'Mostrar a caixa de ferramentas de edición (JavaScript)',
'tog-editondblclick'          => 'Editar as páxinas logo de facer dobre clic (JavaScript)',
'tog-editsection'             => 'Permitir a edición de seccións vía as ligazóns [editar]',
'tog-editsectiononrightclick' => 'Permitir a edición de seccións premendo co botón dereito <br /> nos títulos das seccións (JavaScript)',
'tog-showtoc'                 => 'Mostrar o índice (para páxinas con máis de tres cabeceiras)',
'tog-rememberpassword'        => 'Lembrar o meu contrasinal neste ordenador (ata $1 {{PLURAL:$1|día|días}})',
'tog-watchcreations'          => 'Engadir as páxinas creadas por min á miña lista de artigos vixiados',
'tog-watchdefault'            => 'Engadir as páxinas que edite á miña lista de vixilancia',
'tog-watchmoves'              => 'Engadir as páxinas que mova á miña lista de vixilancia',
'tog-watchdeletion'           => 'Engadir as páxinas que borre á miña lista de vixilancia',
'tog-minordefault'            => 'Marcar por omisión todas as edicións como pequenas',
'tog-previewontop'            => 'Mostrar o botón de vista previa antes da caixa de edición e non despois dela',
'tog-previewonfirst'          => 'Mostrar a vista previa na primeira edición',
'tog-nocache'                 => 'Deshabilitar a memoria caché das páxinas',
'tog-enotifwatchlistpages'    => 'Enviádeme unha mensaxe de correo electrónico cando unha páxina da miña lista de vixilancia cambie',
'tog-enotifusertalkpages'     => 'Enviádeme unha mensaxe de correo electrónico cando a miña páxina de conversa cambie',
'tog-enotifminoredits'        => 'Enviádeme tamén unha mensaxe de correo electrónico cando se produzan edicións pequenas nas páxinas',
'tog-enotifrevealaddr'        => 'Revelar o meu enderezo de correo electrónico nos correos de notificación',
'tog-shownumberswatching'     => 'Mostrar o número de usuarios que están a vixiar',
'tog-oldsig'                  => 'Vista previa da sinatura actual:',
'tog-fancysig'                => 'Tratar a sinatura como se fose texto wiki (sen ligazón automática)',
'tog-externaleditor'          => 'Usar un editor externo por omisión (só para expertos, precisa duns parámetros especiais no seu computador)',
'tog-externaldiff'            => 'Usar diferenzas externas (dif) por omisión (só para expertos, precisa duns parámetros especiais no seu computador)',
'tog-showjumplinks'           => 'Permitir as ligazóns de accesibilidade "ir a"',
'tog-uselivepreview'          => 'Usar a vista previa en tempo real (necesita JavaScript) (experimental)',
'tog-forceeditsummary'        => 'Avisádeme cando o campo resumo estea baleiro',
'tog-watchlisthideown'        => 'Agochar as edicións propias na lista de vixilancia',
'tog-watchlisthidebots'       => 'Agochar as edicións dos bots na lista de vixilancia',
'tog-watchlisthideminor'      => 'Agochar as edicións pequenas na lista de vixilancia',
'tog-watchlisthideliu'        => 'Agochar as edicións dos usuarios rexistrados na lista de vixilancia',
'tog-watchlisthideanons'      => 'Agochar as edicións dos usuarios anónimos na lista de vixilancia',
'tog-watchlisthidepatrolled'  => 'Agochar as edicións patrulladas na lista de vixilancia',
'tog-nolangconversion'        => 'Desactivar a conversión de variantes',
'tog-ccmeonemails'            => 'Enviar ao meu enderezo copia das mensaxes que envíe a outros usuarios',
'tog-diffonly'                => 'Non mostrar o contido da páxina debaixo das diferenzas entre edicións (dif)',
'tog-showhiddencats'          => 'Mostrar as categorías ocultas',
'tog-noconvertlink'           => 'Desactivar a conversión dos títulos de ligazón',
'tog-norollbackdiff'          => 'Omitir as diferenzas despois de levar a cabo unha reversión de edicións',

'underline-always'  => 'Sempre',
'underline-never'   => 'Nunca',
'underline-default' => 'Opción do propio navegador',

# Font style option in Special:Preferences
'editfont-style'     => 'Tipo de letra da caixa de edición:',
'editfont-default'   => 'Tipo de letra por defecto do navegador',
'editfont-monospace' => 'Tipo de letra monoespazada',
'editfont-sansserif' => 'Tipo de letra sans-serif',
'editfont-serif'     => 'Tipo de letra serif',

# Dates
'sunday'        => 'Domingo',
'monday'        => 'Luns',
'tuesday'       => 'Martes',
'wednesday'     => 'Mércores',
'thursday'      => 'Xoves',
'friday'        => 'Venres',
'saturday'      => 'Sábado',
'sun'           => 'Dom',
'mon'           => 'Lun',
'tue'           => 'Mar',
'wed'           => 'Mér',
'thu'           => 'Xov',
'fri'           => 'Ven',
'sat'           => 'Sáb',
'january'       => 'xaneiro',
'february'      => 'febreiro',
'march'         => 'marzo',
'april'         => 'abril',
'may_long'      => 'maio',
'june'          => 'xuño',
'july'          => 'xullo',
'august'        => 'agosto',
'september'     => 'setembro',
'october'       => 'outubro',
'november'      => 'novembro',
'december'      => 'decembro',
'january-gen'   => 'Xaneiro',
'february-gen'  => 'Febreiro',
'march-gen'     => 'Marzo',
'april-gen'     => 'Abril',
'may-gen'       => 'Maio',
'june-gen'      => 'Xuño',
'july-gen'      => 'Xullo',
'august-gen'    => 'Agosto',
'september-gen' => 'Setembro',
'october-gen'   => 'Outubro',
'november-gen'  => 'Novembro',
'december-gen'  => 'Decembro',
'jan'           => 'Xan',
'feb'           => 'Feb',
'mar'           => 'Mar',
'apr'           => 'Abr',
'may'           => 'Mai',
'jun'           => 'Xuñ',
'jul'           => 'Xul',
'aug'           => 'Ago',
'sep'           => 'Set',
'oct'           => 'Out',
'nov'           => 'Nov',
'dec'           => 'Dec',

# Categories related messages
'pagecategories'                 => '{{PLURAL:$1|Categoría|Categorías}}',
'category_header'                => 'Artigos na categoría "$1"',
'subcategories'                  => 'Subcategorías',
'category-media-header'          => 'Multimedia na categoría "$1"',
'category-empty'                 => "''Actualmente esta categoría non conta con ningunha páxina ou ficheiro multimedia.''",
'hidden-categories'              => '{{PLURAL:$1|Categoría oculta|Categorías ocultas}}',
'hidden-category-category'       => 'Categorías ocultas',
'category-subcat-count'          => '{{PLURAL:$2|Esta categoría só ten a seguinte subcategoría.|Esta categoría ten {{PLURAL:$1|a seguinte subcategoría|as seguintes $1 subcategorías}}, dun total de $2.}}',
'category-subcat-count-limited'  => 'Esta categoría ten {{PLURAL:$1|a seguinte subcategoría|as seguintes $1 subcategorías}}.',
'category-article-count'         => '{{PLURAL:$2|Esta categoría só contén a seguinte páxina.|{{PLURAL:$1|A seguinte páxina está|As seguintes $1 páxinas están}} nesta categoría, dun total de $2.}}',
'category-article-count-limited' => '{{PLURAL:$1|A seguinte páxina está|As seguintes $1 páxinas están}} na categoría actual.',
'category-file-count'            => '{{PLURAL:$2|Esta categoría só contén o seguinte ficheiro.|{{PLURAL:$1|O seguinte ficheiro está|Os seguintes $1 ficheiros están}} nesta categoría, dun total de $2.}}',
'category-file-count-limited'    => '{{PLURAL:$1|O seguinte ficheiro está|Os seguintes $1 ficheiros están}} na categoría actual.',
'listingcontinuesabbrev'         => 'cont.',
'index-category'                 => 'Páxinas indexadas',
'noindex-category'               => 'Páxinas non indexadas',

'mainpagetext'      => "'''MediaWiki instalouse correctamente.'''",
'mainpagedocfooter' => 'Consulte a [http://meta.wikimedia.org/wiki/Help:Contents Guía do usuario] para máis información sobre como usar o software wiki.

== Comezando ==
* [http://www.mediawiki.org/wiki/Manual:Configuration_settings Lista de opcións de configuración]
* [http://www.mediawiki.org/wiki/Manual:FAQ Preguntas frecuentes sobre MediaWiki]
* [https://lists.wikimedia.org/mailman/listinfo/mediawiki-announce Lista de correo das edicións de MediaWiki]',

'about'         => 'Acerca de',
'article'       => 'Artigo',
'newwindow'     => '(abre unha ventá nova)',
'cancel'        => 'Cancelar',
'moredotdotdot' => 'Máis...',
'mypage'        => 'A miña páxina',
'mytalk'        => 'A miña conversa',
'anontalk'      => 'Conversa con este enderezo IP',
'navigation'    => 'Navegación',
'and'           => '&#32;e',

# Cologne Blue skin
'qbfind'         => 'Procurar',
'qbbrowse'       => 'Navegar',
'qbedit'         => 'Editar',
'qbpageoptions'  => 'Esta páxina',
'qbpageinfo'     => 'Contexto',
'qbmyoptions'    => 'As miñas páxinas',
'qbspecialpages' => 'Páxinas especiais',
'faq'            => 'PMF',
'faqpage'        => 'Project:FAQ',

# Vector skin
'vector-action-addsection'   => 'Engadir un comentario',
'vector-action-delete'       => 'Borrar',
'vector-action-move'         => 'Mover',
'vector-action-protect'      => 'Protexer',
'vector-action-undelete'     => 'Restaurar',
'vector-action-unprotect'    => 'Desprotexer',
'vector-namespace-category'  => 'Categoría',
'vector-namespace-help'      => 'Páxina de axuda',
'vector-namespace-image'     => 'Ficheiro',
'vector-namespace-main'      => 'Páxina',
'vector-namespace-media'     => 'Páxina de multimedia',
'vector-namespace-mediawiki' => 'Mensaxe',
'vector-namespace-project'   => 'Páxina do proxecto',
'vector-namespace-special'   => 'Páxina especial',
'vector-namespace-talk'      => 'Conversa',
'vector-namespace-template'  => 'Modelo',
'vector-namespace-user'      => 'Páxina de usuario',
'vector-view-create'         => 'Crear',
'vector-view-edit'           => 'Editar',
'vector-view-history'        => 'Ver o historial',
'vector-view-view'           => 'Ler',
'vector-view-viewsource'     => 'Ver o código fonte',
'actions'                    => 'Accións',
'namespaces'                 => 'Espazos de nomes',
'variants'                   => 'Variantes',

'errorpagetitle'    => 'Erro',
'returnto'          => 'Volver a "$1".',
'tagline'           => 'De {{SITENAME}}',
'help'              => 'Axuda',
'search'            => 'Procura',
'searchbutton'      => 'Procurar',
'go'                => 'Artigo',
'searcharticle'     => 'Artigo',
'history'           => 'Historial da páxina',
'history_short'     => 'Historial',
'updatedmarker'     => 'actualizado desde a miña última visita',
'info_short'        => 'Información',
'printableversion'  => 'Versión para imprimir',
'permalink'         => 'Ligazón permanente',
'print'             => 'Imprimir',
'edit'              => 'Editar',
'create'            => 'Crear',
'editthispage'      => 'Editar esta páxina',
'create-this-page'  => 'Crear esta páxina',
'delete'            => 'Borrar',
'deletethispage'    => 'Borrar esta páxina',
'undelete_short'    => 'Restaurar {{PLURAL:$1|unha edición|$1 edicións}}',
'protect'           => 'Protexer',
'protect_change'    => 'cambiar',
'protectthispage'   => 'Protexer esta páxina',
'unprotect'         => 'desprotexer',
'unprotectthispage' => 'Desprotexer esta páxina',
'newpage'           => 'Páxina nova',
'talkpage'          => 'Conversar sobre esta páxina',
'talkpagelinktext'  => 'Conversa',
'specialpage'       => 'Páxina especial',
'personaltools'     => 'Ferramentas persoais',
'postcomment'       => 'Nova sección',
'articlepage'       => 'Ver a páxina de contido',
'talk'              => 'Conversa',
'views'             => 'Vistas',
'toolbox'           => 'Caixa de ferramentas',
'userpage'          => 'Ver a páxina de usuario',
'projectpage'       => 'Ver a páxina do proxecto',
'imagepage'         => 'Ver a páxina do ficheiro',
'mediawikipage'     => 'Ver a páxina da mensaxe',
'templatepage'      => 'Ver a páxina do modelo',
'viewhelppage'      => 'Ver a páxina de axuda',
'categorypage'      => 'Ver a páxina da categoría',
'viewtalkpage'      => 'Ver a conversa',
'otherlanguages'    => 'Outras linguas',
'redirectedfrom'    => '(Redirixido desde "$1")',
'redirectpagesub'   => 'Páxina de redirección',
'lastmodifiedat'    => 'A última modificación desta páxina foi o $1 ás $2.',
'viewcount'         => 'Esta páxina foi visitada {{PLURAL:$1|unha vez|$1 veces}}.',
'protectedpage'     => 'Páxina protexida',
'jumpto'            => 'Ir a:',
'jumptonavigation'  => 'navegación',
'jumptosearch'      => 'procura',
'view-pool-error'   => 'Sentímolo, os servidores están sobrecargados nestes intres.
Hai moitos usuarios intentando ver esta páxina.
Por favor, agarde un anaco antes de intentar acceder á páxina de novo.

$1',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'            => 'Acerca de {{SITENAME}}',
'aboutpage'            => 'Project:Acerca de',
'copyright'            => 'Todo o texto está dispoñíbel baixo $1.',
'copyrightpage'        => '{{ns:project}}:Dereitos de autor',
'currentevents'        => 'Actualidade',
'currentevents-url'    => 'Project:Actualidade',
'disclaimers'          => 'Advertencias',
'disclaimerpage'       => 'Project:Advertencia xeral',
'edithelp'             => 'Axuda de edición',
'edithelppage'         => 'Help:Como editar unha páxina',
'helppage'             => 'Help:Axuda',
'mainpage'             => 'Portada',
'mainpage-description' => 'Portada',
'policy-url'           => 'Project:Política e normas',
'portal'               => 'Portal da comunidade',
'portal-url'           => 'Project:Portal da comunidade',
'privacy'              => 'Política de protección de datos',
'privacypage'          => 'Project:Política de protección de datos',

'badaccess'        => 'Erro de permisos',
'badaccess-group0' => 'Non ten os permisos necesarios para executar a acción que solicitou.',
'badaccess-groups' => 'A acción que solicitou está limitada aos usuarios que están {{PLURAL:$2|neste grupo|nalgún destes grupos}}: $1.',

'versionrequired'     => 'Necesítase a versión $1 de MediaWiki',
'versionrequiredtext' => 'Necesítase a versión $1 de MediaWiki para utilizar esta páxina. Vexa [[Special:Version|a páxina da versión]].',

'ok'                      => 'Aceptar',
'retrievedfrom'           => 'Traído desde "$1"',
'youhavenewmessages'      => 'Ten $1 ($2).',
'newmessageslink'         => 'mensaxes novas',
'newmessagesdifflink'     => 'diferenzas coa revisión anterior',
'youhavenewmessagesmulti' => 'Ten mensaxes novas en $1',
'editsection'             => 'editar',
'editold'                 => 'editar',
'viewsourceold'           => 'ver o código fonte',
'editlink'                => 'editar',
'viewsourcelink'          => 'ver o código fonte',
'editsectionhint'         => 'Editar a sección: "$1"',
'toc'                     => 'Índice',
'showtoc'                 => 'amosar',
'hidetoc'                 => 'agochar',
'thisisdeleted'           => 'Quere ver ou restaurar $1?',
'viewdeleted'             => 'Quere ver $1?',
'restorelink'             => '{{PLURAL:$1|unha edición borrada|$1 edicións borradas}}',
'feedlinks'               => 'Sindicalización:',
'feed-invalid'            => 'Tipo de fonte de novas inválido.',
'feed-unavailable'        => 'As fontes de noticias non están dispoñibles',
'site-rss-feed'           => 'Fonte de novas RSS de $1',
'site-atom-feed'          => 'Fonte de novas Atom de $1',
'page-rss-feed'           => 'Fonte de novas RSS de "$1"',
'page-atom-feed'          => 'Fonte de novas Atom de "$1"',
'red-link-title'          => '$1 (a páxina aínda non existe)',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'Artigo',
'nstab-user'      => 'Páxina de usuario',
'nstab-media'     => 'Páxina multimedia',
'nstab-special'   => 'Páxina especial',
'nstab-project'   => 'Páxina do proxecto',
'nstab-image'     => 'Ficheiro',
'nstab-mediawiki' => 'Mensaxe',
'nstab-template'  => 'Modelo',
'nstab-help'      => 'Axuda',
'nstab-category'  => 'Categoría',

# Main script and global functions
'nosuchaction'      => 'Non existe esa acción',
'nosuchactiontext'  => 'A acción especificada polo enderezo URL é inválida.
Pode que non o escribise ben ou que seguise unha ligazón incorrecta.
Isto tamén podería indicar un erro en {{SITENAME}}.',
'nosuchspecialpage' => 'Non existe esa páxina especial',
'nospecialpagetext' => '<strong>Solicitou unha páxina especial que non está recoñecida polo wiki.</strong>

Pode atopar unha lista coas páxinas especiais válidas en [[Special:SpecialPages|{{int:specialpages}}]].',

# General errors
'error'                => 'Erro',
'databaseerror'        => 'Erro na base de datos',
'dberrortext'          => 'Ocorreu un erro de sintaxe na consulta á base de datos.
Isto pódese deber a un erro no programa.
A última consulta á base de datos foi:
<blockquote><tt>$1</tt></blockquote>
desde a función "<tt>$2</tt>".
A base de datos devolveu o erro "<tt>$3: $4</tt>".',
'dberrortextcl'        => 'Ocorreu un erro de sintaxe na consulta.
A última consulta á base de datos foi:
"$1"
desde a función "$2".
A base de datos devolveu o erro "$3: $4"',
'laggedslavemode'      => "'''Aviso:''' a páxina pode non conter as actualizacións recentes.",
'readonly'             => 'Base de datos fechada',
'enterlockreason'      => 'Dea unha razón para o fechamento, incluíndo unha estimación de até cando se manterá.',
'readonlytext'         => 'Nestes intres a base de datos está pechada a novas entradas e outras modificacións, probabelmente debido a rutinas de mantemento, tras as que volverá á normalidade.

O administrador que a pechou deu esta explicación: $1',
'missing-article'      => 'A base de datos non atopa o texto da páxina chamada "$1" $2, que debera ter atopado.

Normalmente, isto é causado por seguir unha ligazón cara a unha diferenza vella ou a unha páxina que foi borrada.

Se este non é o caso, pode ter atopado un erro no software.
Por favor, comuníquello a un [[Special:ListUsers/sysop|administrador]] tomando nota do enderezo URL.',
'missingarticle-rev'   => '(revisión#: $1)',
'missingarticle-diff'  => '(Dif: $1, $2)',
'readonly_lag'         => 'A base de datos bloqueouse automaticamente mentres os servidores escravos da base de datos se actualizan desde o máster',
'internalerror'        => 'Erro interno',
'internalerror_info'   => 'Erro interno: $1',
'fileappenderrorread'  => 'Non foi posible ler "$1" durante a inserción.',
'fileappenderror'      => 'Non se puido engadir "$1" a "$2".',
'filecopyerror'        => 'Non se deu copiado o ficheiro "$1" a "$2".',
'filerenameerror'      => 'Non se pode cambiar o nome do ficheiro "$1" a "$2".',
'filedeleteerror'      => 'Non se deu borrado o ficheiro "$1".',
'directorycreateerror' => 'Non se puido crear o directorio "$1".',
'filenotfound'         => 'Non se deu atopado o ficheiro "$1".',
'fileexistserror'      => 'Resultou imposíbel escribir no ficheiro "$1": o ficheiro xa existe',
'unexpected'           => 'Valor inesperado: "$1"="$2".',
'formerror'            => 'Erro: non se pode enviar o formulario',
'badarticleerror'      => 'Non pode efectuarse esta acción nesta páxina.',
'cannotdelete'         => 'Non se puido borrar a páxina ou imaxe "$1".
Se cadra, xa foi borrada por alguén.',
'badtitle'             => 'Título incorrecto',
'badtitletext'         => 'O título da páxina pedida non era válido, estaba baleiro ou proviña dunha ligazón interlingua ou interwiki incorrecta.
Pode conter un ou máis caracteres dos que non se poden empregar nos títulos.',
'perfcached'           => 'A información seguinte é da memoria caché e pode ser que non estea completamente actualizada.',
'perfcachedts'         => 'Esta información é da memoria caché. Última actualización: $1.',
'querypage-no-updates' => 'Neste momento están desactivadas as actualizacións nesta páxina. O seu contido non se modificará.',
'wrong_wfQuery_params' => 'Parámetros incorrectos para wfQuery()<br />
Función: $1<br />
Dúbida: $2',
'viewsource'           => 'Ver o código fonte',
'viewsourcefor'        => 'de "$1"',
'actionthrottled'      => 'Acción ocasional',
'actionthrottledtext'  => "Como unha medida de loita contra o ''spam'', limítase a realización desta acción a un número determinado de veces nun curto espazo de tempo, e vostede superou este límite.
Inténteo de novo nuns minutos.",
'protectedpagetext'    => 'Esta páxina foi protexida para evitar a edición.',
'viewsourcetext'       => 'Pode ver e copiar o código fonte desta páxina:',
'protectedinterface'   => 'Esta páxina fornece o texto da interface do software e está protexida para evitar o seu abuso.',
'editinginterface'     => "'''Aviso:''' está editando unha páxina usada para fornecer o texto da interface do software.
Os cambios nesta páxina afectarán á aparencia da interface para os outros usuarios.
Para traducións, considere usar [http://translatewiki.net/wiki/Main_Page?setlang=gl translatewiki.net], o proxecto de localización de MediaWiki.",
'sqlhidden'            => '(Procura SQL agochada)',
'cascadeprotected'     => 'Esta páxina foi protexida fronte á edición debido a que está incluída {{PLURAL:$1|na seguinte páxina protexida, que ten|nas seguintes páxinas protexidas, que teñen}} a "protección en serie" activada:
$2',
'namespaceprotected'   => "Non dispón de permisos para modificar páxinas no espazo de nomes '''$1'''.",
'customcssjsprotected' => 'Non dispón de permisos para modificar esta páxina, dado que contén a configuración persoal doutro usuario.',
'ns-specialprotected'  => 'Non se poden editar as páxinas no espazo de nomes {{ns:special}}.',
'titleprotected'       => "Este título foi protexido da creación por [[User:$1|$1]].
A razón dada foi ''$2''.",

# Virus scanner
'virus-badscanner'     => "Configuración errónea: escáner de virus descoñecido: ''$1''",
'virus-scanfailed'     => 'fallou o escaneado (código $1)',
'virus-unknownscanner' => 'antivirus descoñecido:',

# Login and logout pages
'logouttext'                 => "'''Agora está fóra do sistema.'''

Pode continuar usando {{SITENAME}} de xeito anónimo, ou pode [[Special:UserLogin|acceder de novo]] co mesmo nome de usuario ou con outro.
Teña en conta que mentres non se limpa a memoria caché do seu navegador algunhas páxinas poden continuar a ser amosadas como se aínda estivese dentro do sistema.",
'welcomecreation'            => '== Reciba a nosa benvida, $1! ==
A súa conta foi creada correctamente.
Non esqueza personalizar as súas [[Special:Preferences|preferencias de {{SITENAME}}]].',
'yourname'                   => 'Nome de usuario:',
'yourpassword'               => 'Contrasinal:',
'yourpasswordagain'          => 'Insira o seu contrasinal outra vez:',
'remembermypassword'         => 'Lembrar o meu contrasinal neste ordenador (ata $1 {{PLURAL:$1|día|días}})',
'yourdomainname'             => 'O seu dominio',
'externaldberror'            => 'Ou ben se produciu un erro da base de datos na autenticación externa ou ben non se lle permite actualizar a súa conta externa.',
'login'                      => 'Acceder ao sistema',
'nav-login-createaccount'    => 'Rexistro',
'loginprompt'                => "Debe habilitar as ''cookies'' para acceder a {{SITENAME}}.",
'userlogin'                  => 'Rexistro',
'userloginnocreate'          => 'Rexistro',
'logout'                     => 'Saír do sistema',
'userlogout'                 => 'Saír ao anonimato',
'notloggedin'                => 'Non accedeu ao sistema',
'nologin'                    => "Non está rexistrado? '''$1'''.",
'nologinlink'                => 'Cree unha conta',
'createaccount'              => 'Crear unha conta nova',
'gotaccount'                 => "Xa ten unha conta? '''$1'''.",
'gotaccountlink'             => 'Acceda ao sistema',
'createaccountmail'          => 'Por correo electrónico',
'badretype'                  => 'Os contrasinais que inseriu non coinciden entre si.',
'userexists'                 => 'O nome de usuario que pretende usar xa está en uso.
Escolla un nome diferente.',
'loginerror'                 => 'Erro ao acceder ao sistema',
'createaccounterror'         => 'Non se puido crear a conta: $1',
'nocookiesnew'               => 'A conta de usuario foi creada, pero non accedeu ao sistema.
{{SITENAME}} para rexistrar os usuarios.
Vostede ten as cookies deshabilitadas.
Por favor, habilíteas e logo acceda ao sistema co seu novo nome de usuario e contrasinal.',
'nocookieslogin'             => '{{SITENAME}} usa cookies para rexistrar os usuarios.
Vostede ten as cookies deshabilitadas.
Por favor, habilíteas e inténteo de novo.',
'noname'                     => 'Non especificou un nome de usuario válido.',
'loginsuccesstitle'          => 'Acceso exitoso',
'loginsuccess'               => "'''Accedeu ao sistema {{SITENAME}} como \"\$1\".'''",
'nosuchuser'                 => 'Non existe ningún usuario chamado "$1".
Os nomes de usuario diferencian entre maiúsculas e minúsculas.
Verifique o nome que inseriu ou [[Special:UserLogin/signup|cree unha nova conta]].',
'nosuchusershort'            => 'Non existe ningún usuario chamado "<nowiki>$1</nowiki>".
Verifique o nome que inseriu.',
'nouserspecified'            => 'Debe especificar un nome de usuario.',
'login-userblocked'          => 'Este usuario está bloqueado. Acceso non autorizado.',
'wrongpassword'              => 'O contrasinal escrito é incorrecto.
Por favor, insira outro.',
'wrongpasswordempty'         => 'O campo do contrasinal estaba en branco.
Por favor, inténteo de novo.',
'passwordtooshort'           => 'Os contrasinais deben conter, como mínimo, {{PLURAL:$1|1 carácter|$1 caracteres}}.',
'password-name-match'        => 'O seu contrasinal debe ser diferente do seu nome de usuario.',
'mailmypassword'             => 'Enviádeme un contrasinal novo por correo',
'passwordremindertitle'      => 'Novo contrasinal temporal para {{SITENAME}}',
'passwordremindertext'       => 'Alguén (probablemente vostede, desde o enderezo IP $1) solicitou un novo
contrasinal para acceder a {{SITENAME}} ($4). Un contrasinal temporal para o usuario
"$2" foi creado e fixado como "$3". Se esa foi a súa
intención, necesitará acceder ao sistema e escoller un novo contrasinal agora.
O seu contrasinal temporal caducará {{PLURAL:$5|nun día|en $5 días}}.

Se foi alguén diferente o que fixo esta solicitude ou se xa se lembra do seu contrasinal
e non o quere modificar, pode ignorar esta mensaxe e
continuar a utilizar o seu contrasinal vello.',
'noemail'                    => 'O usuario "$1" non posúe ningún enderezo de correo electrónico rexistrado.',
'noemailcreate'              => 'Ten que proporcionar un enderezo de correo electrónico válido',
'passwordsent'               => 'Envióuselle un contrasinal novo ao enderezo de correo electrónico rexistrado de "$1".
Por favor, acceda ao sistema de novo tras recibilo.',
'blocked-mailpassword'       => 'O seu enderezo IP está bloqueado e ten restrinxida a edición de artigos. Tampouco se lle permite usar a función de recuperación do contrasinal para evitar abusos do sistema.',
'eauthentsent'               => 'Envióuselle un correo electrónico de confirmación ao enderezo mencionado.
Antes de que se lle envíe calquera outro correo a esta conta terá que seguir as instrucións que aparecen nesa mensaxe para confirmar que a conta é realmente súa.',
'throttled-mailpassword'     => 'Enviouse un aviso co contrasinal {{PLURAL:$1|na última hora|nas últimas $1 horas}}.
Para evitar o abuso do sistema só se envía unha mensaxe cada {{PLURAL:$1|hora|$1 horas}}.',
'mailerror'                  => 'Produciuse un erro ao enviar o correo electrónico: $1',
'acct_creation_throttle_hit' => 'Alguén que visitou este wiki co seu enderezo IP creou, no último día, {{PLURAL:$1|unha conta|$1 contas}}, que é o máximo permitido neste período de tempo.
Como resultado, os visitantes que usen este enderezo IP non poden crear máis contas nestes intres.',
'emailauthenticated'         => 'O seu enderezo de correo electrónico foi autenticado o $2 ás $3.',
'emailnotauthenticated'      => 'O seu enderezo de correo electrónico aínda <strong>non foi autenticado</strong>. Non se enviou ningunha mensaxe por algunha das seguintes razóns.',
'noemailprefs'               => 'Especifique un enderezo de correo electrónico se quere que funcione esta opción.',
'emailconfirmlink'           => 'Confirmar o enderezo de correo electrónico',
'invalidemailaddress'        => 'Non se pode aceptar o enderezo de correo electrónico porque semella ter un formato incorrecto.
Insira un enderezo cun formato válido ou baleire ese campo.',
'accountcreated'             => 'Conta creada',
'accountcreatedtext'         => 'A conta de usuario para $1 foi creada.',
'createaccount-title'        => 'Creación dunha conta para {{SITENAME}}',
'createaccount-text'         => 'Alguén creou unha conta chamada "$2" para o seu enderezo de correo electrónico en {{SITENAME}} ($4), e con contrasinal "$3".
Debe acceder ao sistema e mudar o contrasinal agora.

Pode facer caso omiso desta mensaxe se se creou esta conta por erro.',
'usernamehasherror'          => 'O nome de usuario non pode conter cancelos ("#")',
'login-throttled'            => 'Fixo demasiados intentos de inserir o contrasinal.
Por favor, agarde antes de probar outra vez.',
'loginlanguagelabel'         => 'Lingua: $1',
'suspicious-userlogout'      => 'Rexeitouse a súa petición de saír do sistema porque semella que a enviou un navegador roto ou a caché dun proxy.',

# Password reset dialog
'resetpass'                 => 'Cambiar o contrasinal',
'resetpass_announce'        => 'Debe rexistrarse co código temporal que recibiu por correo electrónico. Para finalizar o rexistro debe indicar un novo contrasinal aquí:',
'resetpass_text'            => '<!-- Engadir texto aquí -->',
'resetpass_header'          => 'Cambiar o contrasinal da conta',
'oldpassword'               => 'Contrasinal antigo:',
'newpassword'               => 'Contrasinal novo:',
'retypenew'                 => 'Insira outra vez o novo contrasinal:',
'resetpass_submit'          => 'Poñer o contrasinal e entrar',
'resetpass_success'         => 'O cambio do contrasinal realizouse con éxito! Agora pode entrar...',
'resetpass_forbidden'       => 'Os contrasinais non poden ser mudados',
'resetpass-no-info'         => 'Debe acceder ao sistema para acceder directamente a esta páxina.',
'resetpass-submit-loggedin' => 'Cambiar o contrasinal',
'resetpass-submit-cancel'   => 'Cancelar',
'resetpass-wrong-oldpass'   => 'Contrasinal temporal ou actual inválido. 
Pode ser que xa cambiase o seu contrasinal ou que solicitase un novo contrasinal temporal.',
'resetpass-temp-password'   => 'Contrasinal temporal:',

# Edit page toolbar
'bold_sample'     => 'Texto en negra',
'bold_tip'        => 'Texto en negra',
'italic_sample'   => 'Texto en cursiva',
'italic_tip'      => 'Texto en cursiva',
'link_sample'     => 'Título de ligazón',
'link_tip'        => 'Ligazón interna',
'extlink_sample'  => 'http://www.example.com título de ligazón',
'extlink_tip'     => 'Ligazón externa (lembre o prefixo http://)',
'headline_sample' => 'Texto de cabeceira',
'headline_tip'    => 'Cabeceira de nivel 2',
'math_sample'     => 'Insira unha fórmula aquí',
'math_tip'        => 'Fórmula matemática (LaTeX)',
'nowiki_sample'   => 'Insira aquí un texto sen formato',
'nowiki_tip'      => 'Ignorar o formato wiki',
'image_sample'    => 'Exemplo.jpg',
'image_tip'       => 'Ficheiro embelecido',
'media_sample'    => 'Exemplo.ogg',
'media_tip'       => 'Ligazón a un ficheiro',
'sig_tip'         => 'A súa sinatura con data e hora',
'hr_tip'          => 'Liña horizontal (úsea con moderación)',

# Edit pages
'summary'                          => 'Resumo:',
'subject'                          => 'Asunto/título:',
'minoredit'                        => 'Esta é unha edición pequena',
'watchthis'                        => 'Vixiar esta páxina',
'savearticle'                      => 'Gardar a páxina',
'preview'                          => 'Vista previa',
'showpreview'                      => 'Mostrar a vista previa',
'showlivepreview'                  => 'Vista previa',
'showdiff'                         => 'Mostrar os cambios',
'anoneditwarning'                  => "'''Aviso:''' non accedeu ao sistema.
O seu enderezo IP quedará rexistrado no historial de revisións desta páxina.",
'anonpreviewwarning'               => "''Non accedeu ao sistema. Se garda a páxina, o seu enderezo IP quedará rexistrado no historial de edicións.''",
'missingsummary'                   => "'''Aviso:''' esqueceu incluír o texto do campo resumo.
Se preme en \"Gardar a páxina\" a súa edición gardarase sen ningunha descrición da edición.",
'missingcommenttext'               => 'Por favor, escriba un comentario a continuación.',
'missingcommentheader'             => "'''Aviso:''' non escribiu ningún texto no asunto/cabeceira deste comentario.
Se preme en \"Gardar a páxina\", a súa edición gardarase sen el.",
'summary-preview'                  => 'Vista previa do resumo:',
'subject-preview'                  => 'Vista previa do asunto/título:',
'blockedtitle'                     => 'O usuario está bloqueado',
'blockedtext'                      => '\'\'\'O seu nome de usuario ou enderezo IP foi bloqueado.\'\'\'

O bloqueo foi realizado por $1.
A razón que deu foi \'\'$2\'\'.

* Inicio do bloqueo: $8
* Caducidade do bloqueo: $6
* Pretendeuse bloquear: $7

Pode contactar con $1 ou con calquera outro [[{{MediaWiki:Grouppage-sysop}}|administrador]] para discutir este bloqueo.
Non pode empregar a característica "Enviar un correo electrónico a este usuario" a non ser que dispoña dun enderezo electrónico válido rexistrado nas súas [[Special:Preferences|preferencias de usuario]] e que o seu uso non fose bloqueado.
O seu enderezo IP actual é $3 e o ID do bloqueo é #$5.
Por favor, inclúa eses datos nas consultas que faga.',
'autoblockedtext'                  => 'O seu enderezo IP foi bloqueado automaticamente porque foi empregado por outro usuario que foi bloqueado por $1.
A razón que deu foi a seguinte:

:\'\'$2\'\'

* Inicio do bloqueo: $8
* Caducidade do bloqueo: $6
* Pretendeuse bloquear: $7 

Pode contactar con $1 ou con calquera outro [[{{MediaWiki:Grouppage-sysop}}|administrador]] para discutir este bloqueo.

Teña en conta que non pode empregar "enviarlle un correo electrónico a este usuario" a non ser que dispoña dun enderezo electrónico válido rexistrado nas súas [[Special:Preferences|preferencias de usuario]] e e que o seu uso non fose bloqueado.

O seu enderezo IP actual é $3 e o ID do bloqueo é #$5.
Por favor, inclúa eses datos nas consultas que faga.',
'blockednoreason'                  => 'non se deu ningunha razón',
'blockedoriginalsource'            => "O código fonte de '''$1''' móstrase a continuación:",
'blockededitsource'                => "O texto das '''súas edicións''' en '''$1''' móstrase a continuación:",
'whitelistedittitle'               => 'Cómpre acceder ao sistema para poder editar',
'whitelistedittext'                => 'Ten que $1 para poder editar páxinas.',
'confirmedittext'                  => 'Debe confirmar o correo electrónico antes de comezar a editar. Por favor, configure e dea validez ao correo mediante as súas [[Special:Preferences|preferencias de usuario]].',
'nosuchsectiontitle'               => 'Non se pode atopar a sección',
'nosuchsectiontext'                => 'Intentou editar unha sección que non existe.
Poida que a movesen ou borrasen mentres ollaba a páxina.',
'loginreqtitle'                    => 'Cómpre acceder ao sistema',
'loginreqlink'                     => 'acceder ao sistema',
'loginreqpagetext'                 => 'Debe $1 para ver outras páxinas.',
'accmailtitle'                     => 'O contrasinal foi enviado.',
'accmailtext'                      => 'Un contrasinal xerado ao chou para "[[User talk:$1|$1]]" foi enviado a "$2".

O contrasinal para esta conta nova pode ser modificado na páxina especial \'\'[[Special:ChangePassword|Cambiar o contrasinal]]\'\' tras acceder ao sistema.',
'newarticle'                       => '(Novo)',
'newarticletext'                   => "Seguiu unha ligazón a unha páxina que aínda non existe.
Para crear a páxina, comece a escribir na caixa de embaixo (vexa a [[{{MediaWiki:Helppage}}|páxina de axuda]] para máis información).
Se chegou aquí por erro, simplemente prema no botón '''atrás''' do seu navegador.",
'anontalkpagetext'                 => "----''Esta é a páxina de conversa dun usuario anónimo que aínda non creou unha conta ou que non a usa. Polo tanto, empregamos o enderezo IP para a súa identificación. Este enderezo IP pódeno compartir varios usuarios distintos. Se pensa que foron dirixidos contra a súa persoa comentarios inadecuados, por favor, [[Special:UserLogin/signup|cree unha conta]] ou [[Special:UserLogin|acceda ao sistema]] para evitar futuras confusións con outros usuarios anónimos.''",
'noarticletext'                    => 'Actualmente non hai ningún texto nesta páxina.
Pode [[Special:Search/{{PAGENAME}}|procurar polo título desta páxina]] noutras páxinas,
<span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} ollar os rexistros relacionados]
ou [{{fullurl:{{FULLPAGENAME}}|action=edit}} editar a páxina]</span>.',
'noarticletext-nopermission'       => 'Actualmente non hai ningún texto nesta páxina.
Pode [[Special:Search/{{PAGENAME}}|procurar polo título desta páxina]] noutras páxinas
ou <span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} ollar os rexistros relacionados]</span>.',
'userpage-userdoesnotexist'        => 'A conta do usuario "$1" non está rexistrada. Comprobe se desexa crear/editar esta páxina.',
'userpage-userdoesnotexist-view'   => 'A conta de usuario "$1" non está rexistrada.',
'blocked-notice-logextract'        => 'Este usuario está bloqueado.
Velaquí está a última entrada do rexistro de bloqueos, por se quere consultala:',
'clearyourcache'                   => "'''Nota: despois de gravar cómpre limpar a memoria caché do seu navegador para ver os cambios.'''
'''Mozilla / Firefox / Safari:''' prema ''Maiúsculas'' á vez que en ''Recargar'', ou prema en ''Ctrl-F5'' ou ''Ctrl-R'' (''Command-R'' nos Macintosh);
'''Konqueror:''' faga clic en ''Recargar'' ou prema en ''F5'';
'''Opera:''' limpe a súa memoria caché en ''Ferramentas → Preferencias'';
'''Internet Explorer:''' prema ''Ctrl'' ao tempo que fai clic en ''Refrescar'', ou prema ''Ctrl-F5''.",
'usercssyoucanpreview'             => "'''Nota:''' use o botón \"{{int:showpreview}}\" para verificar o novo CSS antes de gardalo.",
'userjsyoucanpreview'              => "'''Nota:''' use o botón \"{{int:showpreview}}\" para verificar o novo JS antes de gardalo.",
'usercsspreview'                   => "'''Lembre que só está vendo a vista previa do seu CSS de usuario.'''
'''Este aínda non foi gardado!'''",
'userjspreview'                    => "'''Lembre que só está probando/previsualizando o seu JavaScript de usuario.'''
'''Este aínda non foi gardado!'''",
'userinvalidcssjstitle'            => "'''Aviso:''' non hai ningún tema chamado \"\$1\".
Lembre que as páxinas .css e .js personalizadas utilizan un título en minúsculas, como por exemplo {{ns:user}}:Foo/monobook.css no canto de {{ns:user}}:Foo/Monobook.css.",
'updated'                          => '(Actualizado)',
'note'                             => "'''Nota:'''",
'previewnote'                      => "'''Lembre que esta é só unha vista previa e que os seus cambios aínda non foron gardados!'''",
'previewconflict'                  => 'Esta vista previa amosa o texto na área superior tal e como aparecerá se escolle gardar.',
'session_fail_preview'             => "'''O sistema non pode procesar a súa edición porque se perderon os datos de inicio da sesión.
Por favor, inténteo de novo.
Se segue sen funcionar, probe a [[Special:UserLogout|saír do sistema]] e volver entrar.'''",
'session_fail_preview_html'        => "'''O sistema non pode procesar a súa edición porque se perderon os datos de inicio da sesión.'''

''Dado que {{SITENAME}} ten activado o HTML simple, agóchase a vista previa como precaución contra ataques mediante JavaScript.''

'''Se este é un intento de facer unha edición lexítima, por favor, inténteo de novo.
Se segue sen funcionar, probe a [[Special:UserLogout|saír do sistema]] e volver entrar.'''",
'token_suffix_mismatch'            => "'''Rexeitouse a súa edición porque o seu cliente confundiu os signos de puntuación na edición.'''
Rexeitouse a edición para evitar que se corrompa o texto do artigo.
Isto pode acontecer porque estea a empregar un servizo de ''proxy'' anónimo defectuoso baseado na web.",
'editing'                          => 'Editando "$1"',
'editingsection'                   => 'Editando unha sección de "$1"',
'editingcomment'                   => 'Editando unha nova sección de "$1"',
'editconflict'                     => 'Conflito de edición: "$1"',
'explainconflict'                  => "Alguén cambiou esta páxina desde que comezou a editala.
A área de texto superior contén o texto da páxina tal e como existe na actualidade.
Os seus cambios móstranse na área inferior.
Pode mesturar os seus cambios co texto existente.
'''Só''' se gardará o texto na área superior cando prema \"Gardar a páxina\".",
'yourtext'                         => 'O seu texto',
'storedversion'                    => 'Versión gardada',
'nonunicodebrowser'                => "'''ATENCIÓN: o seu navegador non soporta Unicode.'''
Existe unha solución que lle permite editar páxinas con seguridade: os caracteres non incluídos no ASCII aparecerán na caixa de edición como códigos hexadecimais.",
'editingold'                       => "'''ATENCIÓN: está editando unha revisión non actualizada desta páxina.
Se a garda, perderanse os cambios realizados tras esta revisión.'''",
'yourdiff'                         => 'Diferenzas',
'copyrightwarning'                 => "Por favor, teña en conta que todas as contribucións a {{SITENAME}} considéranse publicadas baixo a $2 (vexa $1 para máis detalles). Se non quere que o que escriba se edite sen piedade e se redistribúa sen límites, entón non o envíe aquí.<br />
Ao mesmo tempo, prométanos que o que escribiu é da súa autoría ou que está copiado dun recurso do dominio público ou que permite unha liberdade semellante.
'''NON ENVÍE MATERIAL CON DEREITOS DE AUTOR SEN PERMISO!'''",
'copyrightwarning2'                => "Por favor, decátese de que todas as súas contribucións a {{SITENAME}} poden ser editadas, alteradas ou eliminadas por outras persoas. Se non quere que os seus escritos sexan editados sen piedade, non os publique aquí.<br />
Do mesmo xeito, comprométese a que o que vostede escriba sexa da súa autoría ou copiado dunha fonte de dominio público ou recurso público semellante (vexa $1 para detalles).
'''NON ENVÍE SEN PERMISO TRABALLOS CON DEREITOS DE COPIA!'''",
'longpagewarning'                  => "'''ATENCIÓN: esta páxina ten $1 kilobytes;
algúns navegadores poden ter problemas editando páxinas de 32kb ou máis.
Por favor, considere partir a páxina en seccións máis pequenas.'''",
'longpageerror'                    => "'''Erro: o texto que pretende gardar ocupa $1 kilobytes, e existe un límite dun máximo de $2 kilobytes.'''
Polo tanto, non se pode gardar.",
'readonlywarning'                  => "'''ATENCIÓN: a base de datos foi fechada para facer mantemento, polo que non vai poder gardar as súas edicións polo de agora.
Se cadra, pode cortar e pegar o texto nun ficheiro de texto e gardalo para despois.'''

O administrador que a fechou deu esta explicación: $1",
'protectedpagewarning'             => "'''Aviso: esta páxina foi protexida de xeito que só os usuarios con privilexios de administrador a poidan editar.'''
Velaquí está a última entrada no rexistro, por se quere consultala:",
'semiprotectedpagewarning'         => "'''Nota:''' esta páxina foi protexida de xeito que só os usuarios rexistrados a poidan editar.
Velaquí está a última entrada no rexistro, por se quere consultala:",
'cascadeprotectedwarning'          => "'''Aviso:''' esta páxina foi protexida de xeito que só a poden editar os usuarios con privilexios de administrador debido a que está incluída {{PLURAL:\$1|na seguinte páxina protexida|nas seguintes páxinas protexidas}} coa opción \"protección en serie\" activada:",
'titleprotectedwarning'            => "'''Aviso: esta páxina foi protexida de xeito que [[Special:ListGroupRights|só algúns usuarios]] a poidan crear.'''
Velaquí está a última entrada no rexistro, por se quere consultala:",
'templatesused'                    => '{{PLURAL:$1|Modelo usado|Modelos usados}} nesta páxina:',
'templatesusedpreview'             => '{{PLURAL:$1|Modelo usado|Modelos usados}} nesta vista previa:',
'templatesusedsection'             => '{{PLURAL:$1|Modelo usado|Modelos usados}} nesta sección:',
'template-protected'               => '(protexido)',
'template-semiprotected'           => '(semiprotexido)',
'hiddencategories'                 => 'Esta páxina forma parte {{PLURAL:$1|dunha categoría oculta|de $1 categorías ocultas}}:',
'edittools'                        => '<!-- O texto que apareza aquí mostrarase por debaixo dos formularios de edición e envío. -->',
'nocreatetitle'                    => 'Limitada a creación de páxinas',
'nocreatetext'                     => '{{SITENAME}} ten restrinxida a posibilidade de crear páxinas novas.
Pode volver e editar unha páxina que xa existe ou, se non, [[Special:UserLogin|rexistrarse ou crear unha conta]].',
'nocreate-loggedin'                => 'Non dispón dos permisos necesarios para crear páxinas novas.',
'sectioneditnotsupported-title'    => 'A edición de seccións non está soportada',
'sectioneditnotsupported-text'     => 'A edición de seccións non está soportada nesta páxina.',
'permissionserrors'                => 'Erros de permisos',
'permissionserrorstext'            => 'Non dispón de permiso para facelo por {{PLURAL:$1|esta razón|estas razóns}}:',
'permissionserrorstext-withaction' => 'Non ten os permisos necesarios para $2, {{PLURAL:$1|pola seguinte razón|polas seguintes razóns}}:',
'recreate-moveddeleted-warn'       => "'''Atención: vai volver crear unha páxina que xa foi eliminada anteriormente.'''

Debería considerar se é apropiado continuar a editar esta páxina.
Velaquí están o rexistro de borrados e mais o de traslados desta páxina, por se quere consultalos:",
'moveddeleted-notice'              => 'Esta páxina foi borrada.
A continuación pódese ver o rexistro de borrados e traslados desta páxina, por se quere consultalos.',
'log-fulllog'                      => 'Ver o rexistro completo',
'edit-hook-aborted'                => "A edición foi abortada polo ''hook''.
Este non deu ningunha explicación.",
'edit-gone-missing'                => 'Non se pode actualizar a páxina.
Semella que foi borrada.',
'edit-conflict'                    => 'Conflito de edición.',
'edit-no-change'                   => 'A súa edición foi ignorada dado que non fixo ningún cambio no texto.',
'edit-already-exists'              => 'Non se pode crear a nova páxina.
Esta xa existe.',

# Parser/template warnings
'expensive-parserfunction-warning'        => 'Aviso: esta páxina contén moitos analizadores de funcións de chamadas moi caros.

Debe ter menos {{PLURAL:$2|dunha chamada|de $2 chamadas}}, e agora hai $1.',
'expensive-parserfunction-category'       => 'Páxinas con moitos analizadores de funcións de chamadas moi caros',
'post-expand-template-inclusion-warning'  => 'Aviso: o tamaño do modelo incluído é moi grande.
Algúns modelos non serán incluídos.',
'post-expand-template-inclusion-category' => 'Páxinas onde o tamaño dos modelos incluídos é excedido',
'post-expand-template-argument-warning'   => 'Aviso: esta páxina contén, polo menos, un argumento dun modelo que ten un tamaño e expansión moi grande.
Estes argumentos serán omitidos.',
'post-expand-template-argument-category'  => 'Páxinas que conteñen argumentos de modelo omitidos',
'parser-template-loop-warning'            => 'Detectouse un modelo en bucle: [[$1]]',
'parser-template-recursion-depth-warning' => 'Excedeuse o límite da profundidade do recurso do modelo ($1)',
'language-converter-depth-warning'        => 'Excedeuse o límite de profundidade do convertedor de lingua ($1)',

# "Undo" feature
'undo-success' => 'A edición pode ser desfeita.
Por favor, comprobe a comparación que aparece a continuación para confirmar que isto é o que desexa facer, despois, garde os cambios para desfacer a edición.',
'undo-failure' => 'Non se pode desfacer a edición debido a un conflito con algunha das edicións intermedias.',
'undo-norev'   => 'A edición non se pode desfacer porque non existe ou foi eliminada.',
'undo-summary' => 'Desfíxose a edición $1 de [[Special:Contributions/$2|$2]] ([[User talk:$2|conversa]])',

# Account creation failure
'cantcreateaccounttitle' => 'Non pode crear unha conta de usuario',
'cantcreateaccount-text' => "A creación de contas desde este enderezo IP ('''$1''') foi bloqueada por [[User:$3|$3]].

A razón dada por $3 foi ''$2''",

# History pages
'viewpagelogs'           => 'Ver os rexistros desta páxina',
'nohistory'              => 'Esta páxina non posúe ningún historial de edicións.',
'currentrev'             => 'Revisión actual',
'currentrev-asof'        => 'Revisión actual feita o $2 ás $3',
'revisionasof'           => 'Revisión como estaba o $2 ás $3',
'revision-info'          => 'Revisión feita o $4 ás $5 por $2',
'previousrevision'       => '← Revisión máis antiga',
'nextrevision'           => 'Revisión máis nova →',
'currentrevisionlink'    => 'Revisión actual',
'cur'                    => 'actual',
'next'                   => 'seguinte',
'last'                   => 'última',
'page_first'             => 'primeira',
'page_last'              => 'derradeira',
'histlegend'             => "Selección de diferenzas: marque as versións que queira comparar e prema no botón ao final.<br />
Lenda: '''({{int:cur}})''' = diferenza coa versión actual, '''({{int:last}})''' = diferenza coa versión precedente, '''{{int:minoreditletter}}''' = edición pequena.",
'history-fieldset-title' => 'Navegar polo historial',
'history-show-deleted'   => 'Borrados soamente',
'histfirst'              => 'Primeiras',
'histlast'               => 'Últimas',
'historysize'            => '({{PLURAL:$1|1 byte|$1 bytes}})',
'historyempty'           => '(baleiro)',

# Revision feed
'history-feed-title'          => 'Historial de revisións',
'history-feed-description'    => 'Historial de revisións desta páxina no wiki',
'history-feed-item-nocomment' => '$1 en $2',
'history-feed-empty'          => 'A páxina solicitada non existe.
Puido borrarse ou moverse a outro nome.
Probe a [[Special:Search|buscar no wiki]] para atopar as páxinas relacionadas.',

# Revision deletion
'rev-deleted-comment'         => '(comentario eliminado)',
'rev-deleted-user'            => '(nome de usuario eliminado)',
'rev-deleted-event'           => '(rexistro de evento eliminado)',
'rev-deleted-user-contribs'   => '[nome de usuario ou enderezo IP eliminado; edición agochada das contribucións]',
'rev-deleted-text-permission' => "Esta revisión da páxina foi '''borrada'''.
Pode ampliar os detalles no [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} rexistro de borrados].",
'rev-deleted-text-unhide'     => "Esta revisión da páxina foi '''borrada'''.
Pode ampliar os detalles no [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} rexistro de borrados].
Como administrador aínda podería [$1 ver esta revisión] se quixese.",
'rev-suppressed-text-unhide'  => "Esta revisión da páxina foi '''suprimida'''.
Pode ampliar os detalles no [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} rexistro de supresións].
Como administrador aínda podería [$1 ver esta revisión] se quixese.",
'rev-deleted-text-view'       => "Esta revisión da páxina foi '''borrada'''.
Como administrador pode vela; pode ampliar os detalles no [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} rexistro de borrados].",
'rev-suppressed-text-view'    => "Esta revisión da páxina foi '''suprimida'''.
Como administrador pode vela; pode ampliar os detalles no [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} rexistro de supresións].",
'rev-deleted-no-diff'         => "Non pode ver esta diferenza porque unha das revisións foi '''borrada'''.
Pode ampliar os detalles no [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} rexistro de borrados].",
'rev-suppressed-no-diff'      => "Non pode ver esta diferenza porque unha das revisións foi '''borrada'''.",
'rev-deleted-unhide-diff'     => "Unha das revisións desta diferenza foi '''borrada'''.
Pode ampliar os detalles no [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} rexistro de borrados].
Como administrador aínda podería [$1 ver esta diferenza] se quixese.",
'rev-suppressed-unhide-diff'  => "Unha das revisións desta diferenza foi '''suprimida'''.
Pode ampliar os detalles no [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} rexistro de supresións].
Como administrador aínda podería [$1 ver esta diferenza] se quixese.",
'rev-deleted-diff-view'       => "Unha das revisións desta diferenza foi '''borrada'''.
Como administrador pode vela; pode ampliar os detalles no [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} rexistro de borrados].",
'rev-suppressed-diff-view'    => "Unha das revisións desta diferenza foi '''suprimida'''.
Como administrador pode vela; pode ampliar os detalles no [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} rexistro de supresións].",
'rev-delundel'                => 'mostrar/agochar',
'rev-showdeleted'             => 'mostrar',
'revisiondelete'              => 'Borrar/restaurar revisións',
'revdelete-nooldid-title'     => 'Revisión inválida',
'revdelete-nooldid-text'      => 'Non indicou a revisión ou revisións sobre as que realizar esta
función, a revisión especificada non existe ou está intentando agochar a revisión actual.',
'revdelete-nologtype-title'   => 'Non se especificou ningún tipo de rexistro',
'revdelete-nologtype-text'    => 'Non especificou un tipo de rexistro co que levar a cabo esta acción.',
'revdelete-nologid-title'     => 'Entrada de rexistro inválida',
'revdelete-nologid-text'      => 'Ou non especificou o evento rexistrado no que levar a cabo esta función ou a entrada que deu non existe.',
'revdelete-no-file'           => 'O ficheiro especificado non existe.',
'revdelete-show-file-confirm' => 'Está seguro de querer ver unha revisión borrada do ficheiro "<nowiki>$1</nowiki>" do día $2 ás $3?',
'revdelete-show-file-submit'  => 'Si',
'revdelete-selected'          => "'''{{PLURAL:\$2|Revisión seleccionada|Revisións seleccionadas}} de \"[[:\$1]]\":'''",
'logdelete-selected'          => "'''{{PLURAL:$1|Rexistro de evento seleccionado|Rexistro de eventos seleccionados}}:'''",
'revdelete-text'              => "'''As revisións borradas seguirán aparecendo no historial da páxina e nos rexistros, pero partes do seu contido serán inaccesibles de cara ao público.'''
Os demais administradores de {{SITENAME}} poderán acceder ao contido agochado e poderán restaurar a páxina de novo a través desta mesma interface, a non ser que se estableza algunha restrición adicional.",
'revdelete-confirm'           => 'Por favor, confirme que quere levar a cabo esta acción, que comprende as consecuencias e que o fai de acordo [[{{MediaWiki:Policy-url}}|coas políticas]].',
'revdelete-suppress-text'     => "A eliminación '''só''' debería ser usada nos seguintes casos:
* Información persoal inapropiada
*: ''domicilios e números de teléfono, números da seguridade social, etc.''",
'revdelete-legend'            => 'Aplicar restricións de visibilidade',
'revdelete-hide-text'         => 'Agochar o texto da revisión',
'revdelete-hide-image'        => 'Agochar o contido do ficheiro',
'revdelete-hide-name'         => 'Agochar a acción e o destino',
'revdelete-hide-comment'      => 'Agochar o resumo de edición',
'revdelete-hide-user'         => 'Agochar o nome de usuario ou o enderezo IP do editor',
'revdelete-hide-restricted'   => 'Eliminar os datos da vista dos administradores así coma da doutros',
'revdelete-radio-same'        => '(non cambiar)',
'revdelete-radio-set'         => 'Si',
'revdelete-radio-unset'       => 'Non',
'revdelete-suppress'          => 'Eliminar os datos da vista dos administradores así coma da doutros',
'revdelete-unsuppress'        => 'Retirar as restricións sobre as revisións restauradas',
'revdelete-log'               => 'Motivo para o borrado:',
'revdelete-submit'            => 'Aplicar {{PLURAL:$1|á revisión seleccionada|ás revisións seleccionadas}}',
'revdelete-logentry'          => 'mudou a visibilidade dunha revisión de "[[$1]]"',
'logdelete-logentry'          => 'mudou a visibilidade do evento de [[$1]]',
'revdelete-success'           => "'''Actualizouse sen problemas a visibilidade da revisión.'''",
'revdelete-failure'           => "'''Non se puido actualizar a visibilidade da revisión:'''
$1",
'logdelete-success'           => "'''Configurouse sen problemas a visibilidade do rexistro.'''",
'logdelete-failure'           => "'''A visibilidade do rexistro non pode ser fixada:'''
$1",
'revdel-restore'              => 'cambiar a visibilidade',
'revdel-restore-deleted'      => 'revisións borradas',
'revdel-restore-visible'      => 'revisións visibles',
'pagehist'                    => 'Historial da páxina',
'deletedhist'                 => 'Historial de borrado',
'revdelete-content'           => 'o contido',
'revdelete-summary'           => 'o resumo de edición',
'revdelete-uname'             => 'o nome de usuario',
'revdelete-restricted'        => 'aplicou as restricións aos administradores',
'revdelete-unrestricted'      => 'eliminou as restricións aos administradores',
'revdelete-hid'               => 'agochou $1',
'revdelete-unhid'             => 'descubriu $1',
'revdelete-log-message'       => '$1 {{PLURAL:$2|dunha revisión|de $2 revisións}}',
'logdelete-log-message'       => '$1 {{PLURAL:$2|dun evento|de $2 eventos}}',
'revdelete-hide-current'      => 'Produciuse un erro ao agochar o elemento con data de $1 ás $2: esta é a revisión actual.
Non pode ser agochado.',
'revdelete-show-no-access'    => 'Produciuse un erro ao mostrar o elemento con data de $1 ás $2: este elemento marcouse como "restrinxido".
Non ten acceso a el.',
'revdelete-modify-no-access'  => 'Produciuse un erro ao modificar o elemento con data de $1 ás $2: este elemento marcouse como "restrinxido".
Non ten acceso a el.',
'revdelete-modify-missing'    => 'Produciuse un erro ao modificar o elemento con ID $1: falta na base de datos!',
'revdelete-no-change'         => "'''Aviso:''' o elemento con data de $1 ás $2 xa ten solicitado as configuracións de visibilidade.",
'revdelete-concurrent-change' => 'Produciuse un erro ao modificar o elemento con data de $1 ás $2: o seu estado parece ter sido cambiado por alguén mentres intentaba modificalo.
Por favor, comprobe o rexistros.',
'revdelete-only-restricted'   => 'Erro ao agochar o elemento con data de $1 ás $2: non pode eliminar elementos da vista dos administradores sen tamén seleccionar algunha das outras opcións de visibilidade.',
'revdelete-reason-dropdown'   => '* Motivos frecuentes para borrar
** Violación dos dereitos de autor
** Información persoal inapropiada',
'revdelete-otherreason'       => 'Outro motivo:',
'revdelete-reasonotherlist'   => 'Outro motivo',
'revdelete-edit-reasonlist'   => 'Editar os motivos de borrado',
'revdelete-offender'          => 'Autor da revisión:',

# Suppression log
'suppressionlog'     => 'Rexistro de supresións',
'suppressionlogtext' => 'Embaixo amósase unha lista coas eliminacións e cos bloqueos recentes, que inclúen contido oculto dos administradores.
Vexa a [[Special:IPBlockList|lista de enderezos IP bloqueados]] para comprobar as prohibicións e os bloqueos vixentes.',

# Revision move
'moverevlogentry'              => 'moveu {{PLURAL:$3|unha revisión|$3 revisións}} de $1 a $2',
'revisionmove'                 => 'Mover as revisións de "$1"',
'revmove-explain'              => 'As seguintes revisións moveranse de "$1" á páxina de destino especificada. Se a páxina de destino non existe, esta será creada. En caso de existir, estas revisións fusionaranse co historial de revisións desa páxina.',
'revmove-legend'               => 'Establecer a páxina de destino e o resumo',
'revmove-submit'               => 'Mover as revisións á páxina seleccionada',
'revisionmoveselectedversions' => 'Mover as revisións seleccionadas',
'revmove-reasonfield'          => 'Motivo:',
'revmove-titlefield'           => 'Páxina de destino:',
'revmove-badparam-title'       => 'Parámetros incorrectos',
'revmove-badparam'             => '<span class="error">A súa solicitude contén parámetros insuficientes ou ilegais. Volva atrás e inténteo de novo.</span>',
'revmove-norevisions-title'    => 'A revisión especificada é incorrecta',
'revmove-norevisions'          => '<span class="error">Non especificou unha ou máis revisións sobre as que levar a cabo esta operación; ou poida tamén que a revisión especificada non exista.</span>',
'revmove-nullmove-title'       => 'Título incorrecto',
'revmove-nullmove'             => '<span class="error">As páxinas de orixe e destino son idénticas. Volva atrás e introduza un nome de páxina diferente de "$1".</span>',
'revmove-success-existing'     => '{{PLURAL:$1|Moveuse unha revisión de "[[$2]]"|Movéronse $1 revisións de "[[$2]]"}} á páxina "[[$3]]".',
'revmove-success-created'      => '{{PLURAL:$1|Moveuse unha revisión de "[[$2]]"|Movéronse $1 revisións de "[[$2]]"}} á nova páxina "[[$3]]", creada hai uns intres.',

# History merging
'mergehistory'                     => 'Fusionar historiais das páxinas',
'mergehistory-header'              => 'Esta páxina permítelle fusionar revisións dos historiais da páxina de orixe nunha nova páxina.
Asegúrese de que esta modificación da páxina mantén a continuidade histórica.',
'mergehistory-box'                 => 'Fusionar as revisións de dúas páxinas:',
'mergehistory-from'                => 'Páxina de orixe:',
'mergehistory-into'                => 'Páxina de destino:',
'mergehistory-list'                => 'Historial de edicións fusionábeis',
'mergehistory-merge'               => 'As revisións seguintes de [[:$1]] pódense fusionar con [[:$2]]. Use a columna de botóns de selección para fusionar só as revisións creadasen e antes da hora indicada. Teña en conta que se usa as ligazóns de navegación a columna limparase.',
'mergehistory-go'                  => 'Amosar edicións fusionábeis',
'mergehistory-submit'              => 'Fusionar revisións',
'mergehistory-empty'               => 'Non hai revisións que se poidan fusionar.',
'mergehistory-success'             => '{{PLURAL:$3|Unha revisión|$3 revisións}} de [[:$1]] {{PLURAL:$3|fusionouse|fusionáronse}} sen problemas en [[:$2]].',
'mergehistory-fail'                => 'Non se puido fusionar o historial; comprobe outra vez os parámetros de páxina e hora.',
'mergehistory-no-source'           => 'Non existe a páxina de orixe $1.',
'mergehistory-no-destination'      => 'Non existe a páxina de destino $1.',
'mergehistory-invalid-source'      => 'A páxina de orixe ten que ter un título válido.',
'mergehistory-invalid-destination' => 'A páxina de destino ten que ter un título válido.',
'mergehistory-autocomment'         => '[[:$1]] fusionouse en [[:$2]]',
'mergehistory-comment'             => '[[:$1]] fusionouse en [[:$2]]: $3',
'mergehistory-same-destination'    => 'A orixe das páxinas e o seu destino non poden ser os mesmos',
'mergehistory-reason'              => 'Motivo:',

# Merge log
'mergelog'           => 'Rexistro de fusións',
'pagemerge-logentry' => 'fusionouse [[$1]] con [[$2]] (revisións até $3)',
'revertmerge'        => 'Desfacer a fusión',
'mergelogpagetext'   => 'Embaixo hai unha lista coas fusións máis recentes do historial dunha páxina co doutra.',

# Diffs
'history-title'            => 'Historial de revisións de "$1"',
'difference'               => '(Diferenzas entre revisións)',
'lineno'                   => 'Liña $1:',
'compareselectedversions'  => 'Comparar as versións seleccionadas',
'showhideselectedversions' => 'Mostrar/Agochar as versións seleccionadas',
'editundo'                 => 'desfacer',
'diff-multi'               => '(Non se {{PLURAL:$1|mostra unha revisión|mostran $1 revisións}} do historial.)',

# Search results
'searchresults'                    => 'Resultados da procura',
'searchresults-title'              => 'Resultados da procura de "$1"',
'searchresulttext'                 => 'Para máis información sobre como realizar procuras en {{SITENAME}}, vexa [[{{MediaWiki:Helppage}}|{{int:help}}]].',
'searchsubtitle'                   => 'A súa busca de "\'\'\'[[:$1]]\'\'\'" ([[Special:Prefixindex/$1|todas as páxinas que comezan por "$1"]]{{int:pipe-separator}}[[Special:WhatLinksHere/$1|todas as páxinas que ligan con "$1"]])',
'searchsubtitleinvalid'            => "A súa busca de \"'''\$1'''\"",
'toomanymatches'                   => 'Demasiadas coincidencias foron devoltas, por favor tente unha consulta diferente',
'titlematches'                     => 'O título do artigo coincide',
'notitlematches'                   => 'Non coincide ningún título de páxina',
'textmatches'                      => 'O texto da páxina coincide',
'notextmatches'                    => 'Non se atopou o texto en ningunha páxina',
'prevn'                            => '{{PLURAL:$1|$1}} previas',
'nextn'                            => '{{PLURAL:$1|$1}} seguintes',
'prevn-title'                      => '$1 {{PLURAL:$1|resultado previo|resultados previos}}',
'nextn-title'                      => '$1 {{PLURAL:$1|resultado seguinte|resultados seguintes}}',
'shown-title'                      => 'Mostrar $1 {{PLURAL:$1|resultado|resultados}} por páxina',
'viewprevnext'                     => 'Ver as ($1 {{int:pipe-separator}} $2) ($3)',
'searchmenu-legend'                => 'Opcións de busca',
'searchmenu-exists'                => "* Páxina \"'''[[\$1]]'''\"",
'searchmenu-new'                   => "'''Crear a páxina \"[[:\$1]]\" neste wiki!'''",
'searchhelp-url'                   => 'Help:Contidos',
'searchmenu-prefix'                => '[[Special:PrefixIndex/$1|Navegue polas páxinas que comezan coas mesmas iniciais]]',
'searchprofile-articles'           => 'Páxinas de contido',
'searchprofile-project'            => 'Páxinas do proxecto e de axuda',
'searchprofile-images'             => 'Multimedia',
'searchprofile-everything'         => 'Todo',
'searchprofile-advanced'           => 'Avanzado',
'searchprofile-articles-tooltip'   => 'Procurar en "$1"',
'searchprofile-project-tooltip'    => 'Procurar en "$1"',
'searchprofile-images-tooltip'     => 'Procurar ficheiros',
'searchprofile-everything-tooltip' => 'Procurar en todo o contido (incluíndo páxinas de conversa)',
'searchprofile-advanced-tooltip'   => 'Procurar nos espazos de nomes elixidos',
'search-result-size'               => '$1 ({{PLURAL:$2|1 palabra|$2 palabras}})',
'search-result-category-size'      => '{{PLURAL:$1|1 membro|$1 membros}} ({{PLURAL:$2|1 subcategoría|$2 subcategorías}}, {{PLURAL:$3|1 ficheiro|$3 ficheiros}})',
'search-result-score'              => 'Relevancia: $1%',
'search-redirect'                  => '(redirixido desde "$1")',
'search-section'                   => '(sección "$1")',
'search-suggest'                   => 'Quizais quixo dicir: $1',
'search-interwiki-caption'         => 'Proxectos irmáns',
'search-interwiki-default'         => 'Resultados en $1:',
'search-interwiki-more'            => '(máis)',
'search-mwsuggest-enabled'         => 'con suxestións',
'search-mwsuggest-disabled'        => 'sen suxestións',
'search-relatedarticle'            => 'Relacionado',
'mwsuggest-disable'                => 'Deshabilitar as suxestións AJAX',
'searcheverything-enable'          => 'Procurar en todos os espazos de nomes',
'searchrelated'                    => 'relacionado',
'searchall'                        => 'todo',
'showingresults'                   => "Amósanse {{PLURAL:$1|'''1''' resultado|'''$1''' resultados}} comezando polo número '''$2'''.",
'showingresultsnum'                => "Embaixo {{PLURAL:$3|amósase '''1''' resultado|amósanse '''$3''' resultados}}, comezando polo número '''$2'''.",
'showingresultsheader'             => "{{PLURAL:\$5|Resultado '''\$1''' de '''\$3'''|Resultados do '''\$1''' ao '''\$2''', dun total de '''\$3'''}} para \"'''\$4'''\"",
'nonefound'                        => "'''Nota:''' só algúns espazos de nomes son procurados por omisión.
Probe a fixar a súa petición con ''all:'' para procurar en todo o contido (incluíndo páxinas de conversa, modelos, etc.) ou use como prefixo o espazo de nomes desexado.",
'search-nonefound'                 => 'Non se atopou ningún resultado que coincidise coa procura.',
'powersearch'                      => 'Procurar',
'powersearch-legend'               => 'Busca avanzada',
'powersearch-ns'                   => 'Procurar nos espazos de nomes:',
'powersearch-redir'                => 'Listar as redireccións',
'powersearch-field'                => 'Procurar por',
'powersearch-togglelabel'          => 'Seleccionar:',
'powersearch-toggleall'            => 'Todos',
'powersearch-togglenone'           => 'Ningún',
'search-external'                  => 'Procura externa',
'searchdisabled'                   => 'As procuras en {{SITENAME}} están deshabilitadas por cuestións de rendemento.
Mentres tanto pode procurar usando o Google.
Note que os seus índices do contido de {{SITENAME}} poden estar desactualizados.',

# Quickbar
'qbsettings'               => 'Opcións da barra rápida',
'qbsettings-none'          => 'Ningunha',
'qbsettings-fixedleft'     => 'Fixa á esquerda',
'qbsettings-fixedright'    => 'Fixa á dereita',
'qbsettings-floatingleft'  => 'Flotante á esquerda',
'qbsettings-floatingright' => 'Flotante á dereita',

# Preferences page
'preferences'                   => 'Preferencias',
'mypreferences'                 => 'As miñas preferencias',
'prefs-edits'                   => 'Número de edicións:',
'prefsnologin'                  => 'Non accedeu ao sistema',
'prefsnologintext'              => 'Debe <span class="plainlinks">[{{fullurl:{{#Special:UserLogin}}|returnto=$1}} acceder ao sistema]</span> para modificar as preferencias de usuario.',
'changepassword'                => 'Cambiar o meu contrasinal',
'prefs-skin'                    => 'Aparencia',
'skin-preview'                  => 'Vista previa',
'prefs-math'                    => 'Fórmulas matemáticas',
'datedefault'                   => 'Ningunha preferencia',
'prefs-datetime'                => 'Data e hora',
'prefs-personal'                => 'Información do usuario',
'prefs-rc'                      => 'Cambios recentes',
'prefs-watchlist'               => 'Lista de vixilancia',
'prefs-watchlist-days'          => 'Días para amosar na lista de vixilancia:',
'prefs-watchlist-days-max'      => '(máximo 7 días)',
'prefs-watchlist-edits'         => 'Número de edicións para mostrar na lista de vixilancia completa:',
'prefs-watchlist-edits-max'     => '(número máximo: 1000)',
'prefs-watchlist-token'         => 'Pase para a lista de vixilancia:',
'prefs-misc'                    => 'Preferencias varias',
'prefs-resetpass'               => 'Cambiar o contrasinal',
'prefs-email'                   => 'Opcións de correo electrónico',
'prefs-rendering'               => 'Aparencia',
'saveprefs'                     => 'Gardar as preferencias',
'resetprefs'                    => 'Eliminar os cambios non gardados',
'restoreprefs'                  => 'Restaurar todas as preferencias por defecto',
'prefs-editing'                 => 'Edición',
'prefs-edit-boxsize'            => 'Tamaño da caixa de edición.',
'rows'                          => 'Filas:',
'columns'                       => 'Columnas:',
'searchresultshead'             => 'Procurar',
'resultsperpage'                => 'Número de resultados por páxina:',
'contextlines'                  => 'Número de liñas por resultado:',
'contextchars'                  => 'Número de caracteres de contexto por liña:',
'stub-threshold'                => 'Límite superior para o formato de <a href="#" class="stub">ligazóns de bosquexo</a> (bytes):',
'recentchangesdays'             => 'Número de días a mostrar nos cambios recentes:',
'recentchangesdays-max'         => '(máximo {{PLURAL:$1|un día|$1 días}})',
'recentchangescount'            => 'Número de edicións a mostrar por defecto:',
'prefs-help-recentchangescount' => 'Isto inclúe os cambios recentes, os historiais e mais os rexistros.',
'prefs-help-watchlist-token'    => 'Ao encher este campo cunha clave secreta xerarase unha fonte de novas RSS para a súa lista de vixilancia.
Calquera que saiba esta clave poderá ler a súa lista de vixilancia, así que escolla un valor seguro.
Velaquí un valor xerado ao chou que pode usar: $1',
'savedprefs'                    => 'Gardáronse as súas preferencias.',
'timezonelegend'                => 'Zona horaria:',
'localtime'                     => 'Hora local:',
'timezoneuseserverdefault'      => 'Usar a hora do servidor por defecto',
'timezoneuseoffset'             => 'Outra (especifique o desprazamento)',
'timezoneoffset'                => 'Desprazamento¹:',
'servertime'                    => 'Hora do servidor:',
'guesstimezone'                 => 'Encher desde o navegador',
'timezoneregion-africa'         => 'África',
'timezoneregion-america'        => 'América',
'timezoneregion-antarctica'     => 'Antártida',
'timezoneregion-arctic'         => 'Ártico',
'timezoneregion-asia'           => 'Asia',
'timezoneregion-atlantic'       => 'Océano Atlántico',
'timezoneregion-australia'      => 'Australia',
'timezoneregion-europe'         => 'Europa',
'timezoneregion-indian'         => 'Océano Índico',
'timezoneregion-pacific'        => 'Océano Pacífico',
'allowemail'                    => 'Admitir mensaxes de correo electrónico doutros usuarios',
'prefs-searchoptions'           => 'Opcións de procura',
'prefs-namespaces'              => 'Espazos de nomes',
'defaultns'                     => 'Se non, procurar nestes espazos de nomes:',
'default'                       => 'predeterminado',
'prefs-files'                   => 'Ficheiros',
'prefs-custom-css'              => 'CSS personalizado',
'prefs-custom-js'               => 'JS personalizado',
'prefs-common-css-js'           => 'CSS/JS compartido por todas as aparencias:',
'prefs-reset-intro'             => 'Pode usar esta páxina para restablecer as súas preferencias ás que veñen dadas por defecto.
Este cambio non se poderá desfacer.',
'prefs-emailconfirm-label'      => 'Confirmación do correo:',
'prefs-textboxsize'             => 'Tamaño da caixa de edición',
'youremail'                     => 'Correo electrónico:',
'username'                      => 'Nome de usuario:',
'uid'                           => 'ID do usuario:',
'prefs-memberingroups'          => 'Membro {{PLURAL:$1|do grupo|dos grupos}}:',
'prefs-registration'            => 'Data e hora de rexistro:',
'yourrealname'                  => 'Nome real:',
'yourlanguage'                  => 'Lingua da interface:',
'yourvariant'                   => 'Variante de lingua:',
'yournick'                      => 'Sinatura:',
'prefs-help-signature'          => 'Os comentarios feitos nas páxinas de conversa deben asinarse con catro tiles ("<nowiki>~~~~</nowiki>"), que se converterán na súa sinatura con data e hora.',
'badsig'                        => 'Sinatura non válida; comprobe o código HTML utilizado.',
'badsiglength'                  => 'A súa sinatura é demasiado longa.
Ha de ter menos {{PLURAL:$1|dun carácter|de $1 caracteres}}.',
'yourgender'                    => 'Sexo:',
'gender-unknown'                => 'Non especificado',
'gender-male'                   => 'Masculino',
'gender-female'                 => 'Feminino',
'prefs-help-gender'             => 'Opcional: usado para xerar correctamente o sexo por parte do software. Esta información será pública.',
'email'                         => 'Correo electrónico',
'prefs-help-realname'           => 'O seu nome real é opcional, pero se escolle dalo utilizarase para atribuírlle o seu traballo.',
'prefs-help-email'              => 'O enderezo de correo electrónico é opcional, pero permite que se lle envíe un contrasinal novo se se esquece del.
Tamén pode deixar que outras persoas se poñan en contacto con vostede desde a súa páxina de usuario ou de conversa sen necesidade de revelar a súa identidade.',
'prefs-help-email-required'     => 'Requírese o enderezo de correo electrónico.',
'prefs-info'                    => 'Información básica',
'prefs-i18n'                    => 'Internacionalización',
'prefs-signature'               => 'Sinatura',
'prefs-dateformat'              => 'Formato da data',
'prefs-timeoffset'              => 'Desprazamento horario',
'prefs-advancedediting'         => 'Opcións avanzadas',
'prefs-advancedrc'              => 'Opcións avanzadas',
'prefs-advancedrendering'       => 'Opcións avanzadas',
'prefs-advancedsearchoptions'   => 'Opcións avanzadas',
'prefs-advancedwatchlist'       => 'Opcións avanzadas',
'prefs-display'                 => 'Opcións de visualización',
'prefs-diffs'                   => 'Diferenzas',

# User rights
'userrights'                   => 'Xestión dos dereitos de usuario',
'userrights-lookup-user'       => 'Administrar os grupos do usuario',
'userrights-user-editname'     => 'Escriba un nome de usuario:',
'editusergroup'                => 'Editar os grupos do usuario',
'editinguser'                  => "Mudando os dereitos do usuario '''[[User:$1|$1]]''' ([[User talk:$1|{{int:talkpagelinktext}}]]{{int:pipe-separator}}[[Special:Contributions/$1|{{int:contribslink}}]])",
'userrights-editusergroup'     => 'Editar os grupos do usuario',
'saveusergroups'               => 'Gardar os grupos do usuario',
'userrights-groupsmember'      => 'Membro de:',
'userrights-groupsmember-auto' => 'Membro implícito de:',
'userrights-groups-help'       => 'Pode cambiar os grupos aos que o usuario pertence:
* Se a caixa ten un sinal (✓) significa que o usuario pertence a ese grupo.
* Se, pola contra, non o ten, significa que non pertence.
* Un asterisco (*) indica que non pode eliminar o grupo unha vez que o engadiu, e viceversa.',
'userrights-reason'            => 'Motivo:',
'userrights-no-interwiki'      => 'Non dispón de permiso para editar dereitos de usuarios noutros wikis.',
'userrights-nodatabase'        => 'A base de datos $1 non existe ou non é local.',
'userrights-nologin'           => 'Debe [[Special:UserLogin|acceder ao sistema]] cunta conta de administrador para asignar dereitos de usuario.',
'userrights-notallowed'        => 'A súa conta non dispón dos permisos necesarios para asignar dereitos de usuario.',
'userrights-changeable-col'    => 'Os grupos que pode cambiar',
'userrights-unchangeable-col'  => 'Os grupos que non pode cambiar',

# Groups
'group'               => 'Grupo:',
'group-user'          => 'Usuarios',
'group-autoconfirmed' => 'Usuarios autoconfirmados',
'group-bot'           => 'Bots',
'group-sysop'         => 'Administradores',
'group-bureaucrat'    => 'Burócratas',
'group-suppress'      => 'Supervisores',
'group-all'           => '(todos)',

'group-user-member'          => 'usuario',
'group-autoconfirmed-member' => 'usuario autoconfirmado',
'group-bot-member'           => 'bot',
'group-sysop-member'         => 'administrador',
'group-bureaucrat-member'    => 'burócrata',
'group-suppress-member'      => 'supervisor',

'grouppage-user'          => '{{ns:project}}:Usuarios',
'grouppage-autoconfirmed' => '{{ns:project}}:Usuarios autoconfirmados',
'grouppage-bot'           => '{{ns:project}}:Bots',
'grouppage-sysop'         => '{{ns:project}}:Administradores',
'grouppage-bureaucrat'    => '{{ns:project}}:Burócratas',
'grouppage-suppress'      => '{{ns:project}}:Supervisor',

# Rights
'right-read'                  => 'Ler páxinas',
'right-edit'                  => 'Editar páxinas',
'right-createpage'            => 'Crear páxinas (que non son de conversa)',
'right-createtalk'            => 'Crear páxinas de conversa',
'right-createaccount'         => 'Crear novas contas de usuario',
'right-minoredit'             => 'Marcar as edicións como pequenas',
'right-move'                  => 'Mover páxinas',
'right-move-subpages'         => 'Mover páxinas coas súas subpáxinas',
'right-move-rootuserpages'    => 'Mover páxinas de usuario raíz',
'right-movefile'              => 'Mover ficheiros',
'right-suppressredirect'      => 'Non crear unha redirección dende o nome vello ao mover unha páxina',
'right-upload'                => 'Cargar ficheiros',
'right-reupload'              => 'Sobrescribir ficheiros existentes',
'right-reupload-own'          => 'Sobrescribir un ficheiro existente cargado polo mesmo usuario',
'right-reupload-shared'       => 'Sobrescribir localmente ficheiros do repositorio multimedia',
'right-upload_by_url'         => 'Cargar un ficheiro dende un enderezo URL',
'right-purge'                 => 'Purgar a caché dunha páxina do wiki sen a páxina de confirmación',
'right-autoconfirmed'         => 'Editar páxinas semiprotexidas',
'right-bot'                   => 'Ser tratado coma un proceso automatizado',
'right-nominornewtalk'        => 'As edicións pequenas nas páxinas de conversa non lanzan o aviso de mensaxes novas',
'right-apihighlimits'         => 'Usar os límites superiores nas peticións API',
'right-writeapi'              => 'Usar o API para modificar o wiki',
'right-delete'                => 'Borrar páxinas',
'right-bigdelete'             => 'Borrar páxinas con historiais grandes',
'right-deleterevision'        => 'Borrar e restaurar versións específicas de páxinas',
'right-deletedhistory'        => 'Ver as entradas borradas do historial, sen o seu texto asociado',
'right-deletedtext'           => 'Ver texto borrado e cambios entre revisións eliminadas',
'right-browsearchive'         => 'Procurar páxinas borradas',
'right-undelete'              => 'Restaurar unha páxina',
'right-suppressrevision'      => 'Revisar e restaurar as revisións agochadas dos administradores',
'right-suppressionlog'        => 'Ver rexistros privados',
'right-block'                 => 'Bloquear outros usuarios fronte á edición',
'right-blockemail'            => 'Bloquear un usuario fronte ao envío dun correo electrónico',
'right-hideuser'              => 'Bloquear un usuario, agochándollo ao público',
'right-ipblock-exempt'        => 'Evitar bloqueos de IPs, autobloqueos e bloqueos de rango',
'right-proxyunbannable'       => 'Evitar os bloqueos autamáticos a proxies',
'right-unblockself'           => 'Desbloqueárense a si mesmos',
'right-protect'               => 'Trocar os niveis de protección e editar páxinas protexidas',
'right-editprotected'         => 'Editar páxinas protexidas (que non teñan protección en serie)',
'right-editinterface'         => 'Editar a interface de usuario',
'right-editusercssjs'         => 'Editar os ficheiros CSS e JS doutros usuarios',
'right-editusercss'           => 'Editar os ficheiros CSS doutros usuarios',
'right-edituserjs'            => 'Editar os ficheiros JS doutros usuarios',
'right-rollback'              => 'Reversión rápida da edición dun usuario dunha páxina particular',
'right-markbotedits'          => 'Marcar as edicións desfeitas como edicións dun bot',
'right-noratelimit'           => 'Non lle afectan os límites de frecuencia',
'right-import'                => 'Importar páxinas doutros wikis',
'right-importupload'          => 'Importar páxinas desde un ficheiro cargado',
'right-patrol'                => 'Marcar edicións como patrulladas',
'right-autopatrol'            => 'Ter as edicións marcadas automaticamente como patrulladas',
'right-patrolmarks'           => 'Ver os cambios que están marcados coma patrullados',
'right-unwatchedpages'        => 'Ver unha lista de páxinas que non están vixiadas',
'right-trackback'             => 'Enviar un trackback',
'right-mergehistory'          => 'Fusionar o historial das páxinas',
'right-userrights'            => 'Editar todos os dereitos de usuario',
'right-userrights-interwiki'  => 'Editar os dereitos de usuario dos usuarios doutros wikis',
'right-siteadmin'             => 'Fechar e abrir a base de datos',
'right-reset-passwords'       => 'Restablecer os contrasinais doutros usuarios',
'right-override-export-depth' => 'Exportar páxinas incluíndo as páxinas ligadas ata unha profundidade de 5',
'right-sendemail'             => 'Enviar correos electrónicos a outros usuarios',
'right-revisionmove'          => 'Mover revisións',

# User rights log
'rightslog'      => 'Rexistro de dereitos de usuario',
'rightslogtext'  => 'Este é un rexistro de permisos dos usuarios.',
'rightslogentry' => 'cambiou o grupo ao que pertence "$1" de $2 a $3',
'rightsnone'     => '(ningún)',

# Associated actions - in the sentence "You do not have permission to X"
'action-read'                 => 'ler esta páxina',
'action-edit'                 => 'editar esta páxina',
'action-createpage'           => 'crear páxinas',
'action-createtalk'           => 'crear páxinas de conversa',
'action-createaccount'        => 'crear esta conta de usuario',
'action-minoredit'            => 'marcar esta edición como pequena',
'action-move'                 => 'mover esta páxina',
'action-move-subpages'        => 'mover esta páxina e as súas subpáxinas',
'action-move-rootuserpages'   => 'mover páxinas de usuario raíz',
'action-movefile'             => 'mover este ficheiro',
'action-upload'               => 'cargar este ficheiro',
'action-reupload'             => 'sobrescribir este ficheiro existente',
'action-reupload-shared'      => 'sobrescribir este ficheiro nun repositorio compartido',
'action-upload_by_url'        => 'cargar este ficheiro desde un enderezo URL',
'action-writeapi'             => 'usar a escritura API',
'action-delete'               => 'borrar esta páxina',
'action-deleterevision'       => 'borrar esta revisión',
'action-deletedhistory'       => 'ver o historial borrado desta páxina',
'action-browsearchive'        => 'procurar páxinas borradas',
'action-undelete'             => 'restaurar esta páxina',
'action-suppressrevision'     => 'revisar e restaurar esta revisión agochada',
'action-suppressionlog'       => 'ver este rexistro privado',
'action-block'                => 'bloquear o usuario fronte á edición',
'action-protect'              => 'cambiar o nivel de protección desta páxina',
'action-import'               => 'importar esta páxina doutro wiki',
'action-importupload'         => 'importar esta páxina da carga dun ficheiro',
'action-patrol'               => 'marcar a edición doutro como patrullada',
'action-autopatrol'           => 'marcar a súa edición como patrullada',
'action-unwatchedpages'       => 'ver a lista das páxinas non vixiadas',
'action-trackback'            => 'enviar un trackback',
'action-mergehistory'         => 'fusionar o historial desta páxina',
'action-userrights'           => 'editar todos os permisos de usuario',
'action-userrights-interwiki' => 'editar os permisos de usuario dos usuarios doutros wikis',
'action-siteadmin'            => 'bloquear ou desbloquear a base de datos',
'action-revisionmove'         => 'mover revisións',

# Recent changes
'nchanges'                          => '$1 {{PLURAL:$1|cambio|cambios}}',
'recentchanges'                     => 'Cambios recentes',
'recentchanges-legend'              => 'Opcións dos cambios',
'recentchangestext'                 => 'Sigue, nesta páxina, as modificacións máis recentes no wiki.',
'recentchanges-feed-description'    => 'Siga os cambios máis recentes deste wiki nesta fonte de novas.',
'recentchanges-label-legend'        => 'Lenda: $1.',
'recentchanges-legend-newpage'      => '$1 - nova páxina',
'recentchanges-label-newpage'       => 'Esta edición creou unha nova páxina',
'recentchanges-legend-minor'        => '$1 - edición pequena',
'recentchanges-label-minor'         => 'Esta é unha edición pequena',
'recentchanges-legend-bot'          => '$1 - edición feita por un bot',
'recentchanges-label-bot'           => 'Esta edición foi realizada por un bot',
'recentchanges-legend-unpatrolled'  => '$1 - edición non patrullada',
'recentchanges-label-unpatrolled'   => 'Esta edición aínda non foi comprobada',
'rcnote'                            => "Embaixo {{PLURAL:$1|amósase '''1''' cambio|amósanse os últimos '''$1''' cambios}} {{PLURAL:$2|no último día|nos últimos '''$2''' días}} ata as $5 do $4.",
'rcnotefrom'                        => "A continuación amósanse os cambios desde as '''$4''' do '''$3''' (móstranse ata '''$1''').",
'rclistfrom'                        => 'Mostrar os cambios novos desde as $1',
'rcshowhideminor'                   => '$1 as edicións pequenas',
'rcshowhidebots'                    => '$1 os bots',
'rcshowhideliu'                     => '$1 os usuarios rexistrados',
'rcshowhideanons'                   => '$1 os usuarios anónimos',
'rcshowhidepatr'                    => '$1 edicións revisadas',
'rcshowhidemine'                    => '$1 as edicións propias',
'rclinks'                           => 'Mostrar os últimos $1 cambios nos últimos $2 días.<br />$3',
'diff'                              => 'dif',
'hist'                              => 'hist',
'hide'                              => 'Agochar',
'show'                              => 'Amosar',
'minoreditletter'                   => 'm',
'newpageletter'                     => 'N',
'boteditletter'                     => 'b',
'number_of_watching_users_pageview' => '[$1 {{PLURAL:$1|usuario|usuarios}} vixiando]',
'rc_categories'                     => 'Límite para categorías (separado con "|")',
'rc_categories_any'                 => 'Calquera',
'newsectionsummary'                 => 'Nova sección: /* $1 */',
'rc-enhanced-expand'                => 'Amosar os detalles (require JavaScript)',
'rc-enhanced-hide'                  => 'Agochar os detalles',

# Recent changes linked
'recentchangeslinked'          => 'Cambios relacionados',
'recentchangeslinked-feed'     => 'Cambios relacionados',
'recentchangeslinked-toolbox'  => 'Cambios relacionados',
'recentchangeslinked-title'    => 'Cambios relacionados con "$1"',
'recentchangeslinked-noresult' => 'Non se produciron cambios nas páxinas vinculadas a esta durante o período de tempo seleccionado.',
'recentchangeslinked-summary'  => "Esta é unha lista dos cambios que se realizaron recentemente nas páxinas vinculadas a esta (ou dos membros da categoría especificada).
As páxinas da súa [[Special:Watchlist|lista de vixilancia]] aparecen en '''negra'''.",
'recentchangeslinked-page'     => 'Nome da páxina:',
'recentchangeslinked-to'       => 'Amosar os cambios relacionados das páxinas que ligan coa dada',

# Upload
'upload'                      => 'Cargar un ficheiro',
'uploadbtn'                   => 'Cargar o ficheiro',
'reuploaddesc'                => 'Cancelar a carga e volver ao formulario de carga',
'upload-tryagain'             => 'Enviar a descrición do ficheiro modificada',
'uploadnologin'               => 'Non accedeu ao sistema',
'uploadnologintext'           => 'Debe [[Special:UserLogin|acceder ao sistema]] para poder cargar ficheiros.',
'upload_directory_missing'    => 'Falta o directorio de carga ($1) e non pode ser creado polo servidor da páxina web.',
'upload_directory_read_only'  => 'Non se pode escribir no directorio de subida ($1) do servidor web.',
'uploaderror'                 => 'Erro ao cargar',
'upload-recreate-warning'     => "'''Atención: borrouse ou trasladouse un ficheiro con ese nome.'''

Velaquí están o rexistro de borrados e mais o de traslados desta páxina, por se quere consultalos:",
'uploadtext'                  => "Use o formulario de embaixo para cargar ficheiros.
Para ver ou procurar imaxes subidas con anterioridade vaia á [[Special:FileList|lista de imaxes]]; os envíos tamén se rexistran no [[Special:Log/upload|rexistro de cargas]] e as eliminacións no [[Special:Log/delete|rexistro de borrados]].

Para incluír un ficheiro nunha páxina, use unha ligazón do seguinte xeito:
* '''<tt><nowiki>[[</nowiki>{{ns:file}}<nowiki>:File.jpg]]</nowiki></tt>''' para usar a versión completa do ficheiro
* '''<tt><nowiki>[[</nowiki>{{ns:file}}<nowiki>:File.png|200px|thumb|left|texto alternativo]]</nowiki></tt>''' para usar unha resolución de 200 píxeles de ancho nunha caixa na marxe esquerda cunha descrición (\"texto alternativo\")
* '''<tt><nowiki>[[</nowiki>{{ns:media}}<nowiki>:File.ogg]]</nowiki></tt>''' para ligar directamente co ficheiro sen que este saia na páxina",
'upload-permitted'            => 'Tipos de ficheiro permitidos: $1.',
'upload-preferred'            => 'Tipos de arquivos preferidos: $1.',
'upload-prohibited'           => 'Tipos de arquivos prohibidos: $1.',
'uploadlog'                   => 'rexistro de cargas',
'uploadlogpage'               => 'Rexistro de cargas',
'uploadlogpagetext'           => 'Embaixo hai unha lista cos ficheiros subidos máis recentemente.
Vexa a [[Special:NewFiles|galería de imaxes novas]] para unha visión máis xeral.',
'filename'                    => 'Nome do ficheiro',
'filedesc'                    => 'Resumo',
'fileuploadsummary'           => 'Descrición:',
'filereuploadsummary'         => 'Cambios no ficheiro:',
'filestatus'                  => 'Estado dos dereitos de autor:',
'filesource'                  => 'Fonte:',
'uploadedfiles'               => 'Ficheiros cargados en {{SITENAME}}',
'ignorewarning'               => 'Ignorar a advertencia e gardar o ficheiro de calquera xeito',
'ignorewarnings'              => 'Ignorar os avisos',
'minlength1'                  => 'Os nomes dos ficheiros deben ter cando menos unha letra.',
'illegalfilename'             => 'O nome de ficheiro "$1" contén caracteres que non están permitidos nos títulos das páxinas.
Por favor, cambie o nome do ficheiro e intente cargalo de novo.',
'badfilename'                 => 'O nome desta imaxe cambiouse a "$1".',
'filetype-mime-mismatch'      => 'A extensión do ficheiro non coincide co tipo MIME.',
'filetype-badmime'            => 'Non se permite enviar ficheiros de tipo MIME "$1".',
'filetype-bad-ie-mime'        => 'Non se pode cargar este ficheiro porque o Internet Explorer detectaríao como "$1", o cal é un tipo de ficheiro non permitido e potencialmente perigoso.',
'filetype-unwanted-type'      => "'''\".\$1\"''' é un tipo de ficheiro non desexado.
{{PLURAL:\$3|O tipo de ficheiro preferido é|Os tipos de ficheiro preferidos son}} \$2.",
'filetype-banned-type'        => "'''\".\$1\"''' non é un tipo de ficheiro permitido.
{{PLURAL:\$3|O tipo de ficheiro permitido é|Os tipos de ficheiros permitidos son}} \$2.",
'filetype-missing'            => 'O ficheiro non conta cunha extensión (como ".jpg").',
'empty-file'                  => 'O ficheiro que enviou estaba baleiro.',
'file-too-large'              => 'O ficheiro que enviou era grande de máis.',
'filename-tooshort'           => 'O nome do ficheiro é curto de máis.',
'filetype-banned'             => 'Este tipo de ficheiro está prohibido.',
'verification-error'          => 'O ficheiro non pasou a comprobación de ficheiros.',
'hookaborted'                 => 'O asociador da extensión cancelou a modificación que intentou realizar.',
'illegal-filename'            => 'O nome do ficheiro non está permitido.',
'overwrite'                   => 'Non está permitido sobrescribir un ficheiro existente.',
'unknown-error'               => 'Houbo un erro descoñecido.',
'tmp-create-error'            => 'Non se puido crear o ficheiro temporal.',
'tmp-write-error'             => 'Houbo un erro ao gravar o ficheiro temporal.',
'large-file'                  => 'Recoméndase que o tamaño dos ficheiros non supere os $1; este ficheiro ocupa $2.',
'largefileserver'             => 'Este ficheiro é de maior tamaño có permitido pola configuración do servidor.',
'emptyfile'                   => 'O ficheiro que cargou semella estar baleiro.
Isto pode deberse a un erro ortográfico no seu nome.
Por favor, verifique se realmente quere cargar este ficheiro.',
'fileexists'                  => "Xa existe un ficheiro con ese nome. Por favor, comprobe '''<tt>[[:$1]]</tt>''' se non está seguro de querer cambialo.
[[$1|thumb]]",
'filepageexists'              => "A páxina de descrición deste ficheiro xa foi creada en '''<tt>[[:$1]]</tt>''', pero polo de agora non existe ningún ficheiro con este nome.
O resumo que escribiu non aparecerá na páxina de descrición.
Para facer que o resumo apareza alí, necesitará editar a páxina manualmente.
[[$1|miniatura]]",
'fileexists-extension'        => "Xa existe un ficheiro cun nome semellante: [[$2|thumb]]
* Nome do ficheiro que intenta cargar: '''<tt>[[:$1]]</tt>'''
* Nome de ficheiro existente: '''<tt>[[:$2]]</tt>'''
Por favor, escolla un nome diferente.",
'fileexists-thumbnail-yes'    => "Semella que o ficheiro é unha imaxe de tamaño reducido ''(miniatura)''.
[[$1|thumb]]
Por favor, comprobe o ficheiro '''<tt>[[:$1]]</tt>'''.
Se o ficheiro seleccionado é a mesma imaxe en tamaño orixinal non é preciso enviar unha miniatura adicional.",
'file-thumbnail-no'           => "O nome do ficheiro comeza por '''<tt>$1</tt>'''.
Parece tratarse dunha imaxe de tamaño reducido ''(miniatura)''.
Se dispón dunha versión desta imaxe de maior resolución, se non, múdelle o nome ao ficheiro.",
'fileexists-forbidden'        => 'Xa existe un ficheiro co mesmo nome e este non pode ser sobrescrito.
Se aínda quere cargar o seu ficheiro, por favor, retroceda e use un novo nome. [[File:$1|thumb|center|$1]]',
'fileexists-shared-forbidden' => 'Xa existe un ficheiro con este nome no repositorio de ficheiros compartidos.
Se aínda quere cargar o seu ficheiro, volva atrás e use outro nome.
[[File:$1|thumb|center|$1]]',
'file-exists-duplicate'       => 'Este ficheiro é un duplicado {{PLURAL:$1|do seguinte|dos seguintes}}:',
'file-deleted-duplicate'      => 'Un ficheiro idéntico a este ("[[$1]]") foi borrado previamente. Debería comprobar o historial de borrados do ficheiro antes de proceder a cargalo de novo.',
'successfulupload'            => 'A carga realizouse correctamente',
'uploadwarning'               => 'Advertencia ao cargar o ficheiro',
'uploadwarning-text'          => 'Por favor, modifique a descrición do ficheiro e inténteo de novo.',
'savefile'                    => 'Gardar o ficheiro',
'uploadedimage'               => 'cargou "[[$1]]"',
'overwroteimage'              => 'enviou unha nova versión de "[[$1]]"',
'uploaddisabled'              => 'Sentímolo, a subida de ficheiros está desactivada.',
'copyuploaddisabled'          => 'A carga mediante URL está desactivada.',
'uploadfromurl-queued'        => 'A súa carga púxese á cola.',
'uploaddisabledtext'          => 'A carga de ficheiros está desactivada.',
'php-uploaddisabledtext'      => 'As cargas de ficheiros PHP están desactivadas. Por favor, comprobe a característica file_uploads.',
'uploadscripted'              => 'Este ficheiro contén HTML ou código (script code) que pode producir erros ao ser interpretado polo navegador.',
'uploadvirus'                 => 'O ficheiro contén un virus! Detalles: $1',
'upload-source'               => 'Ficheiro de orixe',
'sourcefilename'              => 'Nome do ficheiro a cargar:',
'sourceurl'                   => 'URL de orixe:',
'destfilename'                => 'Nome do ficheiro de destino:',
'upload-maxfilesize'          => 'Tamaño máximo para o ficheiro: $1',
'upload-description'          => 'Descrición do ficheiro',
'upload-options'              => 'Opcións de carga',
'watchthisupload'             => 'Vixiar este ficheiro',
'filewasdeleted'              => 'Un ficheiro con ese nome foi cargado con anterioridade e a continuación borrado.
Debe comprobar o $1 antes de proceder a cargalo outra vez.',
'upload-wasdeleted'           => "'''Aviso: está cargando un ficheiro que foi previamente borrado.'''

Debería considerar se é apropiado continuar a carga deste ficheiro.
A continuación móstrase o rexistro de borrados deste ficheiro, por se quere consultalo:",
'filename-bad-prefix'         => "O nome do ficheiro que está cargando comeza con '''\"\$1\"''', que é un típico nome non descritivo asignado automaticamente polas cámaras dixitais.
Por favor, escolla un nome máis descritivo para o seu ficheiro.",
'filename-prefix-blacklist'   => ' #<!-- deixe esta liña exactamente como está --> <pre>
# A sintaxe é a seguinte:
#   * Todo o que estea desde o carácter "#" até o final da liña é un comentario
#   * Cada liña que non está en branco é un prefixo para os nomes típicos dos ficheiros asignados automaticamente polas cámaras dixitais
CIMG # Casio
DSC_ # Nikon
DSCF # Fuji
DSCN # Nikon
DUW # algúns teléfonos móbiles
IMG # xenérico
JD # Jenoptik
MGP # Pentax
PICT # varias
 #</pre> <!-- deixe esta liña exactamente como está -->',
'upload-successful-msg'       => 'O ficheiro cargado está dispoñible aquí: $1',
'upload-failure-subj'         => 'Problema ao cargar',
'upload-failure-msg'          => 'Houbo un problema durante a carga:

$1',

'upload-proto-error'        => 'Protocolo erróneo',
'upload-proto-error-text'   => 'A carga remota require URLs que comecen por <code>http://</code> ou <code>ftp://</code>.',
'upload-file-error'         => 'Erro interno',
'upload-file-error-text'    => 'Produciuse un erro interno ao intentar crear un ficheiro temporal no servidor.
Por favor, contacte cun [[Special:ListUsers/sysop|administrador]] do sistema.',
'upload-misc-error'         => 'Erro de carga descoñecido',
'upload-misc-error-text'    => 'Ocorreu un erro descoñecido durante a carga.
Por favor, comprobe que o enderezo URL é válido e está dispoñíbel e, despois, inténteo de novo.
Se o problema persiste contacte cun [[Special:ListUsers/sysop|administrador]] do sistema.',
'upload-too-many-redirects' => 'O enderezo URL contiña moitas redireccións',
'upload-unknown-size'       => 'Tamaño descoñecido',
'upload-http-error'         => 'Produciuse un erro HTTP: $1',

# img_auth script messages
'img-auth-accessdenied' => 'Acceso rexeitado',
'img-auth-nopathinfo'   => 'Falta a PATH_INFO.
O seu servidor non está configurado para pasar esta información.
Pode ser que estea baseado en CGI e non puidese soportar img_auth.
Olle http://www.mediawiki.org/wiki/Manual:Image_Authorization para obter máis información.',
'img-auth-notindir'     => 'A ruta solicitada non está no directorio de carga configurado.',
'img-auth-badtitle'     => 'Non é posible construír un título válido a partir de "$1".',
'img-auth-nologinnWL'   => 'Non accedeu ao sistema e "$1" non está na lista de branca.',
'img-auth-nofile'       => 'O ficheiro "$1" non existe.',
'img-auth-isdir'        => 'Está intentando acceder ao directorio "$1".
Só se permite o acceso ao ficheiro.',
'img-auth-streaming'    => 'Secuenciando "$1".',
'img-auth-public'       => 'A función de img_auth.php é para ficheiros de saída desde un wiki privado.
Este wiki está configurado como público.
Para unha seguridade óptima, img_auth.php está desactivado.',
'img-auth-noread'       => 'O usuario non ten acceso á lectura de "$1".',

# HTTP errors
'http-invalid-url'      => 'URL non válido: $1',
'http-invalid-scheme'   => 'Os enderezos URL co esquema "$1" non están soportados',
'http-request-error'    => 'Erro descoñecido ao enviar a solicitude.',
'http-read-error'       => 'Erro de lectura HTTP.',
'http-timed-out'        => 'O pedido HTTP expirou.',
'http-curl-error'       => 'Ocorreu un erro ao acceder ao URL: $1',
'http-host-unreachable' => 'Non se puido acceder ao URL.',
'http-bad-status'       => 'Houbo un problema durante a solicitude HTTP: $1 $2',

# Some likely curl errors. More could be added from <http://curl.haxx.se/libcurl/c/libcurl-errors.html>
'upload-curl-error6'       => 'Non se logrou acceder a ese URL',
'upload-curl-error6-text'  => 'Non se logrou acceder ao URL que indicou. Comprobe que ese URL é correcto e que o sitio está activo.',
'upload-curl-error28'      => 'Rematou o tempo de espera',
'upload-curl-error28-text' => 'O sitio tardou demasiado en responder.
Por favor, comprobe que está activo, agarde un anaco e inténteo de novo.
Tamén pode reintentalo cando haxa menos actividade.',

'license'            => 'Licenza:',
'license-header'     => 'Licenza',
'nolicense'          => 'Ningunha (os ficheiros sen licenza serán eliminados)',
'license-nopreview'  => '(A vista previa non está dispoñíbel)',
'upload_source_url'  => '  (un URL válido e accesible publicamente)',
'upload_source_file' => '  (un ficheiro no seu ordenador)',

# Special:ListFiles
'listfiles-summary'     => 'Esta páxina especial amosa todos os ficheiros cargados.
Por omisión, os ficheiros enviados máis recentemente aparecen no alto da lista.
Premendo nunha cabeceira da columna cambia a ordenación.',
'listfiles_search_for'  => 'Buscar polo nome do ficheiro multimedia:',
'imgfile'               => 'ficheiro',
'listfiles'             => 'Lista de imaxes',
'listfiles_date'        => 'Data',
'listfiles_name'        => 'Nome',
'listfiles_user'        => 'Usuario',
'listfiles_size'        => 'Tamaño',
'listfiles_description' => 'Descrición',
'listfiles_count'       => 'Versións',

# File description page
'file-anchor-link'          => 'Ficheiro',
'filehist'                  => 'Historial do ficheiro',
'filehist-help'             => 'Faga clic nunha data/hora para ver o ficheiro tal e como estaba nese momento.',
'filehist-deleteall'        => 'borrar todo',
'filehist-deleteone'        => 'borrar',
'filehist-revert'           => 'reverter',
'filehist-current'          => 'actual',
'filehist-datetime'         => 'Data/Hora',
'filehist-thumb'            => 'Miniatura',
'filehist-thumbtext'        => 'Miniatura da versión ás $3 do $2',
'filehist-nothumb'          => 'Sen miniatura',
'filehist-user'             => 'Usuario',
'filehist-dimensions'       => 'Dimensións',
'filehist-filesize'         => 'Tamaño do ficheiro',
'filehist-comment'          => 'Comentario',
'filehist-missing'          => 'Falta o ficheiro',
'imagelinks'                => 'Ligazóns do ficheiro',
'linkstoimage'              => '{{PLURAL:$1|A seguinte páxina liga|As seguintes $1 páxinas ligan}} con esta imaxe:',
'linkstoimage-more'         => 'Máis {{PLURAL:$1|dunha páxina liga|de $1 páxinas ligan}} con este ficheiro.
A seguinte lista só amosa {{PLURAL:$1|a primeira páxina que liga|as primeiras $1 páxina que ligan}} con el.
Hai dispoñible [[Special:WhatLinksHere/$2|unha lista completa]].',
'nolinkstoimage'            => 'Ningunha páxina liga con este ficheiro.',
'morelinkstoimage'          => 'Ver [[Special:WhatLinksHere/$1|máis ligazóns]] cara a este ficheiro.',
'redirectstofile'           => '{{PLURAL:$1|O seguinte ficheiro redirixe|Os seguintes $1 ficheiros redirixen}} cara a este:',
'duplicatesoffile'          => '{{PLURAL:$1|O seguinte ficheiro é un duplicado|Os seguintes $1 ficheiros son duplicados}} destoutro ([[Special:FileDuplicateSearch/$2|máis detalles]]):',
'sharedupload'              => 'Este ficheiro é de $1 e pode ser usado por outros proxectos.',
'sharedupload-desc-there'   => 'Este ficheiro é de $1 e pode ser usado por outros proxectos.
Por favor, vexa a [$2 páxina de descrición do ficheiro] para obter máis información.',
'sharedupload-desc-here'    => 'Este ficheiro é de $1 e pode ser usado por outros proxectos.
A descrición da [$2 páxina de descrición do ficheiro] móstrase a continuación.',
'filepage-nofile'           => 'Non existe ningún ficheiro con este nome.',
'filepage-nofile-link'      => 'Non existe ningún ficheiro con este nome, pero pode [$1 cargalo].',
'uploadnewversion-linktext' => 'Cargar unha nova versión deste ficheiro',
'shared-repo-from'          => 'de $1',
'shared-repo'               => 'repositorio compartido',

# File reversion
'filerevert'                => 'Desfacer $1',
'filerevert-legend'         => 'Reverter o ficheiro',
'filerevert-intro'          => 'Está revertendo "\'\'\'[[Media:$1|$1]]\'\'\'", vai volver á versión [$4 de $2, ás $3].',
'filerevert-comment'        => 'Motivo:',
'filerevert-defaultcomment' => 'Volveuse á versión do $1 ás $2',
'filerevert-submit'         => 'Reverter',
'filerevert-success'        => 'Reverteuse "\'\'\'[[Media:$1|$1]]\'\'\'" á versión [$4 de $2, ás $3].',
'filerevert-badversion'     => 'Non existe unha versión local anterior deste ficheiro coa data e hora indicadas.',

# File deletion
'filedelete'                  => 'Eliminar "$1"',
'filedelete-legend'           => 'Eliminar un ficheiro',
'filedelete-intro'            => "Está a piques de eliminar o ficheiro \"'''[[Media:\$1|\$1]]'''\" xunto con todo o seu historial.",
'filedelete-intro-old'        => 'Vai eliminar a versión de "\'\'\'[[Media:$1|$1]]\'\'\'" do [$4 $2, ás $3].',
'filedelete-comment'          => 'Comentario:',
'filedelete-submit'           => 'Eliminar',
'filedelete-success'          => "Borrouse o ficheiro \"'''\$1'''\".",
'filedelete-success-old'      => 'Eliminouse a versión de "\'\'\'[[Media:$1|$1]]\'\'\'" do $2 ás $3.',
'filedelete-nofile'           => "\"'''\$1'''\" non existe.",
'filedelete-nofile-old'       => "Non existe unha versión arquivada de \"'''\$1'''\" cos atributos especificados.",
'filedelete-otherreason'      => 'Outro motivo:',
'filedelete-reason-otherlist' => 'Outra razón',
'filedelete-reason-dropdown'  => '*Motivos frecuentes para borrar
** Violación dos dereitos de autor
** Ficheiro duplicado',
'filedelete-edit-reasonlist'  => 'Editar os motivos de borrado',
'filedelete-maintenance'      => 'Os borrados e restauracións de ficheiros están desactivados temporalmente durante o mantemento.',

# MIME search
'mimesearch'         => 'Busca MIME',
'mimesearch-summary' => 'Esta páxina permite filtrar os ficheiros segundo o seu tipo MIME.
Entrada: tipodecontido/subtipo, p.ex. <tt>image/jpeg</tt>.',
'mimetype'           => 'Tipo MIME:',
'download'           => 'descargar',

# Unwatched pages
'unwatchedpages' => 'Páxinas non vixiadas',

# List redirects
'listredirects' => 'Lista de redireccións',

# Unused templates
'unusedtemplates'     => 'Modelos sen uso',
'unusedtemplatestext' => 'Esta páxina contén unha lista de todas as páxinas no espazo de nomes {{ns:template}} que non están incluídas en ningunha outra páxina.
Lembre verificar outras ligazóns cara aos modelos antes de borralos.',
'unusedtemplateswlh'  => 'outras ligazóns',

# Random page
'randompage'         => 'Páxina aleatoria',
'randompage-nopages' => 'Non hai páxinas {{PLURAL:$2|no seguinte espazo de nomes|nos seguintes espazos de nomes}}: $1.',

# Random redirect
'randomredirect'         => 'Redirección aleatoria',
'randomredirect-nopages' => 'Non hai redireccións no espazo de nomes "$1".',

# Statistics
'statistics'                   => 'Estatísticas',
'statistics-header-pages'      => 'Estatísticas das páxinas',
'statistics-header-edits'      => 'Estatísticas das edicións',
'statistics-header-views'      => 'Estatísticas das vistas',
'statistics-header-users'      => 'Estatísticas dos usuarios',
'statistics-header-hooks'      => 'Outras estatísticas',
'statistics-articles'          => 'Páxinas de contido',
'statistics-pages'             => 'Páxinas',
'statistics-pages-desc'        => 'Todas as páxinas do wiki; isto inclúe as páxinas de conversa, redireccións, etc.',
'statistics-files'             => 'Ficheiros cargados',
'statistics-edits'             => 'Edicións nas páxinas des que se creou {{SITENAME}}',
'statistics-edits-average'     => 'Media de edicións por páxina',
'statistics-views-total'       => 'Vistas totais',
'statistics-views-peredit'     => 'Vistas por edición',
'statistics-users'             => '[[Special:ListUsers|Usuarios]] rexistrados',
'statistics-users-active'      => 'Usuarios activos',
'statistics-users-active-desc' => 'Usuarios que teñen levado a cabo unha acción {{PLURAL:$1|no último día|nos últimos $1 días}}',
'statistics-mostpopular'       => 'Páxinas máis vistas',

'disambiguations'      => 'Páxinas de homónimos',
'disambiguationspage'  => 'Template:Homónimos',
'disambiguations-text' => "As seguintes páxinas ligan cunha '''páxina de homónimos'''.
No canto de ligar cos homónimos deben apuntar cara á páxina apropiada.<br />
Unha páxina trátase como páxina de homónimos cando nela se usa un modelo que está ligado desde [[MediaWiki:Disambiguationspage]].",

'doubleredirects'            => 'Redireccións dobres',
'doubleredirectstext'        => 'Esta lista contén as páxinas que redirixen cara a outras páxinas de redirección.
Cada ringleira contén ligazóns cara á primeira e segunda redireccións, así como a primeira liña de texto da segunda páxina, que é frecuentemente o artigo "real", á que a primeira redirección debera apuntar.
As entradas <s>riscadas</s> xa foron resoltas.',
'double-redirect-fixed-move' => 'A páxina "[[$1]]" foi movida, agora é unha redirección cara a "[[$2]]"',
'double-redirect-fixer'      => 'Amañador de redireccións',

'brokenredirects'        => 'Redireccións rotas',
'brokenredirectstext'    => 'As seguintes redireccións ligan cara a páxinas que non existen:',
'brokenredirects-edit'   => 'editar',
'brokenredirects-delete' => 'borrar',

'withoutinterwiki'         => 'Páxinas sen ligazóns interwiki',
'withoutinterwiki-summary' => 'As seguintes páxinas non ligan con ningunha versión noutra lingua.',
'withoutinterwiki-legend'  => 'Prefixo',
'withoutinterwiki-submit'  => 'Amosar',

'fewestrevisions' => 'Artigos con menos revisións',

# Miscellaneous special pages
'nbytes'                  => '$1 {{PLURAL:$1|byte|bytes}}',
'ncategories'             => '$1 {{PLURAL:$1|categoría|categorías}}',
'nlinks'                  => '$1 {{PLURAL:$1|ligazón|ligazóns}}',
'nmembers'                => '$1 {{PLURAL:$1|páxina|páxinas}}',
'nrevisions'              => '$1 {{PLURAL:$1|revisión|revisións}}',
'nviews'                  => 'vista {{PLURAL:$1|unha vez|$1 veces}}',
'specialpage-empty'       => 'Non hai resultados para o que solicitou.',
'lonelypages'             => 'Páxinas orfas',
'lonelypagestext'         => 'As seguintes páxinas non teñen ningunha ligazón que apunte cara a elas desde outra páxina de {{SITENAME}}.',
'uncategorizedpages'      => 'Páxinas sen categorías',
'uncategorizedcategories' => 'Categorías sen categorías',
'uncategorizedimages'     => 'Ficheiros sen categorizar',
'uncategorizedtemplates'  => 'Modelos sen categorizar',
'unusedcategories'        => 'Categorías sen uso',
'unusedimages'            => 'Imaxes sen uso',
'popularpages'            => 'Páxinas populares',
'wantedcategories'        => 'Categorías requiridas',
'wantedpages'             => 'Páxinas requiridas',
'wantedpages-badtitle'    => 'Título inválido fixado nos resultados: $1',
'wantedfiles'             => 'Ficheiros requiridos',
'wantedtemplates'         => 'Modelos requiridos',
'mostlinked'              => 'Páxinas máis ligadas',
'mostlinkedcategories'    => 'Categorías máis ligadas',
'mostlinkedtemplates'     => 'Modelos máis enlazados',
'mostcategories'          => 'Artigos con máis categorías',
'mostimages'              => 'Ficheiros máis usados',
'mostrevisions'           => 'Artigos con máis revisións',
'prefixindex'             => 'Todas as páxinas coas iniciais',
'shortpages'              => 'Páxinas curtas',
'longpages'               => 'Páxinas longas',
'deadendpages'            => 'Páxinas sen ligazóns cara a outras',
'deadendpagestext'        => 'Estas páxinas non ligan con ningunha outra páxina de {{SITENAME}}.',
'protectedpages'          => 'Páxinas protexidas',
'protectedpages-indef'    => 'Só as proteccións indefinidas',
'protectedpages-cascade'  => 'Só as proteccións en serie',
'protectedpagestext'      => 'As seguintes páxinas están protexidas fronte á edición ou traslado',
'protectedpagesempty'     => 'Non hai páxinas protexidas neste momento',
'protectedtitles'         => 'Títulos protexidos',
'protectedtitlestext'     => 'Os seguintes títulos están protexidos da creación',
'protectedtitlesempty'    => 'Actualmente non están protexidos títulos con eses parámetros.',
'listusers'               => 'Lista de usuarios',
'listusers-editsonly'     => 'Amosar só os usuarios con edicións',
'listusers-creationsort'  => 'Ordenar por data de creación',
'usereditcount'           => '$1 {{PLURAL:$1|edición|edicións}}',
'usercreated'             => 'Creado o $1 ás $2',
'newpages'                => 'Páxinas novas',
'newpages-username'       => 'Nome de usuario:',
'ancientpages'            => 'Artigos máis antigos',
'move'                    => 'Mover',
'movethispage'            => 'Mover esta páxina',
'unusedimagestext'        => 'Os seguintes ficheiros existen pero aínda non se incluíron en ningunha páxina.
Por favor, teña en conta que outras páxinas web poden ligar cara a un ficheiro mediante un enderezo URL directo e por iso poden aparecer listados aquí, mesmo estando en uso.',
'unusedcategoriestext'    => 'Existen as seguintes categorías, aínda que ningún artigo ou categoría as emprega.',
'notargettitle'           => 'Sen obxectivo',
'notargettext'            => 'Non especificou a páxina ou o usuario no cal levar a cabo esta función.',
'nopagetitle'             => 'Non existe esa páxina',
'nopagetext'              => 'A páxina que especificou non existe.',
'pager-newer-n'           => '{{PLURAL:$1|unha posterior|$1 posteriores}}',
'pager-older-n'           => '{{PLURAL:$1|unha anterior|$1 anteriores}}',
'suppress'                => 'Supervisor',

# Book sources
'booksources'               => 'Fontes bibliográficas',
'booksources-search-legend' => 'Procurar fontes bibliográficas',
'booksources-go'            => 'Ir',
'booksources-text'          => 'A continuación aparece unha lista de ligazóns cara a outros sitios web que venden libros novos e usados, neles tamén pode obter máis información sobre as obras que está a buscar:',
'booksources-invalid-isbn'  => 'O ISBN inserido parece non ser válido; comprobe que non haxa erros ao copialo da fonte orixinal.',

# Special:Log
'specialloguserlabel'  => 'Usuario:',
'speciallogtitlelabel' => 'Título:',
'log'                  => 'Rexistros',
'all-logs-page'        => 'Todos os rexistros públicos',
'alllogstext'          => 'Vista combinada de todos os rexistros dipoñibles en {{SITENAME}}.
Pode precisar máis a vista seleccionando o tipo de rexistro, o nome do usuario ou o título da páxina afectada.',
'logempty'             => 'Non se atopou ningún elemento relacionado no rexistro.',
'log-title-wildcard'   => 'Procurar os títulos que comecen con este texto',

# Special:AllPages
'allpages'          => 'Todas as páxinas',
'alphaindexline'    => '$1 a $2',
'nextpage'          => 'Páxina seguinte ($1)',
'prevpage'          => 'Páxina anterior ($1)',
'allpagesfrom'      => 'Mostrar as páxinas que comecen por:',
'allpagesto'        => 'Mostrar as páxinas que rematen en:',
'allarticles'       => 'Todos os artigos',
'allinnamespace'    => 'Todas as páxinas (espazo de nomes $1)',
'allnotinnamespace' => 'Todas as páxinas (que non están no espazo de nomes $1)',
'allpagesprev'      => 'Anterior',
'allpagesnext'      => 'Seguinte',
'allpagessubmit'    => 'Amosar',
'allpagesprefix'    => 'Mostrar as páxinas que comezan co prefixo:',
'allpagesbadtitle'  => 'O título dado á páxina non era válido ou contiña un prefixo inter-linguas ou inter-wikis. Pode que conteña un ou máis caracteres que non se poden empregar nos títulos.',
'allpages-bad-ns'   => '{{SITENAME}} carece do espazo de nomes "$1".',

# Special:Categories
'categories'                    => 'Categorías',
'categoriespagetext'            => '{{PLURAL:$1|A seguinte categoría contén|As seguintes categorías conteñen}} páxinas ou contidos multimedia.
Aquí non se amosan as [[Special:UnusedCategories|categorías sen uso]].
Olle tamén as [[Special:WantedCategories|categorías requiridas]].',
'categoriesfrom'                => 'Mostrar as categorías que comecen por:',
'special-categories-sort-count' => 'ordenar por número',
'special-categories-sort-abc'   => 'ordenar alfabeticamente',

# Special:DeletedContributions
'deletedcontributions'             => 'Contribucións borradas do usuario',
'deletedcontributions-title'       => 'Contribucións borradas do usuario',
'sp-deletedcontributions-contribs' => 'contribucións',

# Special:LinkSearch
'linksearch'       => 'Ligazóns externas',
'linksearch-pat'   => 'Patrón de procura:',
'linksearch-ns'    => 'Espazo de nomes:',
'linksearch-ok'    => 'Procurar',
'linksearch-text'  => 'Pódense usar caracteres comodín como "*.wikipedia.org".<br />
Protocolos soportados: <tt>$1</tt>',
'linksearch-line'  => '$1 está ligado desde $2',
'linksearch-error' => 'Os caracteres comodín só poden aparecer ao principio do nome do servidor.',

# Special:ListUsers
'listusersfrom'      => 'Mostrar os usuarios que comecen por:',
'listusers-submit'   => 'Mostrar',
'listusers-noresult' => 'Non se atopou ningún usuario.',
'listusers-blocked'  => '(bloqueado)',

# Special:ActiveUsers
'activeusers'            => 'Lista de usuarios activos',
'activeusers-intro'      => 'Esta é unha lista cos usuarios que tiveron algún tipo de actividade {{PLURAL:$1|no último día|nos últimos $1 días}}.',
'activeusers-count'      => '$1 {{PLURAL:$1|edición|edicións}} {{PLURAL:$3|no último día|nos últimos $3 días}}',
'activeusers-from'       => 'Mostrar os usuarios que comecen por:',
'activeusers-hidebots'   => 'Agochar os bots',
'activeusers-hidesysops' => 'Agochar os administradores',
'activeusers-noresult'   => 'Non se atopou ningún usuario.',

# Special:Log/newusers
'newuserlogpage'              => 'Rexistro de creación de usuarios',
'newuserlogpagetext'          => 'Este é un rexistro de creación de contas de usuario.',
'newuserlog-byemail'          => 'contrasinal enviado por correo electrónico',
'newuserlog-create-entry'     => 'Novo usuario',
'newuserlog-create2-entry'    => 'creou unha nova conta para "$1"',
'newuserlog-autocreate-entry' => 'Conta de usuario creada automaticamente',

# Special:ListGroupRights
'listgrouprights'                      => 'Dereitos dun usuario segundo o seu grupo',
'listgrouprights-summary'              => 'A seguinte lista mostra os grupos de usuario definidos neste wiki, cos seus dereitos de acceso asociados.
Se quere máis información acerca dos dereitos individuais, pode atopala [[{{MediaWiki:Listgrouprights-helppage}}|aquí]].',
'listgrouprights-key'                  => '* <span class="listgrouprights-granted">Dereito concedido</span>
* <span class="listgrouprights-revoked">Dereito revogado</span>',
'listgrouprights-group'                => 'Grupo',
'listgrouprights-rights'               => 'Dereitos',
'listgrouprights-helppage'             => 'Help:Dereitos do grupo',
'listgrouprights-members'              => '(lista de membros)',
'listgrouprights-addgroup'             => 'Pode engadir {{PLURAL:$2|o grupo|os grupos}}: $1',
'listgrouprights-removegroup'          => 'Pode eliminar {{PLURAL:$2|o grupo|os grupos}}: $1',
'listgrouprights-addgroup-all'         => 'Pode engadir todos os grupos',
'listgrouprights-removegroup-all'      => 'Pode eliminar todos os grupos',
'listgrouprights-addgroup-self'        => 'Pode engadir {{PLURAL:$2|un grupo|grupos}} pola súa propia conta: $1',
'listgrouprights-removegroup-self'     => 'Pode eliminar {{PLURAL:$2|un grupo|grupos}} pola súa propia conta: $1',
'listgrouprights-addgroup-self-all'    => 'Pode engadir todos os grupos pola súa propia conta',
'listgrouprights-removegroup-self-all' => 'Pode eliminar todos os grupos pola súa propia conta',

# E-mail user
'mailnologin'          => 'Non existe enderezo para o envío',
'mailnologintext'      => 'Debe [[Special:UserLogin|acceder ao sistema]] e ter rexistrado un enderezo de correo electrónico válido nas súas [[Special:Preferences|preferencias]] para enviar correos electrónicos a outros usuarios.',
'emailuser'            => 'Enviar un correo electrónico a este usuario',
'emailpage'            => 'Enviar un correo electrónico a un usuario',
'emailpagetext'        => 'Pode usar o formulario de embaixo para enviar unha mensaxe de correo electrónico a este usuario.
O correo electrónico que inseriu [[Special:Preferences|nas súas preferencias]] aparecerá no campo "De:" do correo, polo que o receptor da mensaxe poderalle responder.',
'usermailererror'      => 'O obxecto enviado deu unha mensaxe de erro:',
'defemailsubject'      => 'Correo electrónico de {{SITENAME}}',
'usermaildisabled'     => 'O correo electrónico do usuario está desactivado',
'usermaildisabledtext' => 'Non pode enviar correos electrónicos a outros usuarios deste wiki',
'noemailtitle'         => 'Sen enderezo de correo electrónico',
'noemailtext'          => 'Este usuario non especificou un enderezo de correo electrónico válido.',
'nowikiemailtitle'     => 'Sen correo electrónico habilitado',
'nowikiemailtext'      => 'Este usuario elixiu non recibir correos electrónicos doutros usuarios.',
'email-legend'         => 'Enviar un correo electrónico a outro usuario de {{SITENAME}}',
'emailfrom'            => 'De:',
'emailto'              => 'Para:',
'emailsubject'         => 'Asunto:',
'emailmessage'         => 'Mensaxe:',
'emailsend'            => 'Enviar',
'emailccme'            => 'Enviar unha copia da mensaxe para min.',
'emailccsubject'       => 'Copia da súa mensaxe para $1: $2',
'emailsent'            => 'Mensaxe enviada',
'emailsenttext'        => 'A súa mensaxe de correo electrónico foi enviada.',
'emailuserfooter'      => 'Este correo electrónico foi enviado por $1 a $2 mediante a función "Enviar un correo electrónico a este usuario" de {{SITENAME}}.',

# User Messenger
'usermessage-summary' => 'Mensaxe deixada polo sistema.',
'usermessage-editor'  => 'Editor das mensaxes do sistema',

# Watchlist
'watchlist'            => 'A miña lista de vixilancia',
'mywatchlist'          => 'A miña lista de vixilancia',
'watchlistfor'         => "(de '''$1''')",
'nowatchlist'          => 'Non ten elementos na súa lista de vixilancia.',
'watchlistanontext'    => 'Faga o favor de $1 no sistema para ver ou editar os elementos da súa lista de vixilancia.',
'watchnologin'         => 'Non accedeu ao sistema',
'watchnologintext'     => 'Debe [[Special:UserLogin|acceder ao sistema]] para modificar a súa lista de vixilancia.',
'addedwatch'           => 'Engadido á lista de vixilancia',
'addedwatchtext'       => "A páxina \"[[:\$1]]\" foi engadida á súa [[Special:Watchlist|lista de vixilancia]].
Os cambios futuros nesta páxina e na súa páxina de conversa asociada serán listados alí, e a páxina aparecerá en '''negra''' na [[Special:RecentChanges|lista de cambios recentes]] para facer máis sinxela a súa sinalización.",
'removedwatch'         => 'Eliminado da lista de vixilancia',
'removedwatchtext'     => 'A páxina "[[:$1]]" foi eliminada [[Special:Watchlist|da súa lista de vixilancia]].',
'watch'                => 'Vixiar',
'watchthispage'        => 'Vixiar esta páxina',
'unwatch'              => 'Deixar de vixiar',
'unwatchthispage'      => 'Deixar de vixiar',
'notanarticle'         => 'Non é unha páxina de contido',
'notvisiblerev'        => 'A revisión foi borrada',
'watchnochange'        => 'Ningún dos elementos baixo vixilancia foi editado no período de tempo amosado.',
'watchlist-details'    => 'Hai {{PLURAL:$1|unha páxina|$1 páxinas}} na súa lista de vixilancia, sen contar as de conversa.',
'wlheader-enotif'      => '* Está dispoñible a notificación por correo electrónico.',
'wlheader-showupdated' => "* As páxinas que cambiaron desde a súa última visita amósanse en '''negra'''",
'watchmethod-recent'   => 'comprobando as edicións recentes na procura de páxinas vixiadas',
'watchmethod-list'     => 'comprobando as páxinas vixiadas na procura de edicións recentes',
'watchlistcontains'    => 'A súa lista de vixilancia ten $1 {{PLURAL:$1|páxina|páxinas}}.',
'iteminvalidname'      => 'Hai un problema co elemento "$1", nome non válido...',
'wlnote'               => "Embaixo {{PLURAL:$1|está a última modificación|están as últimas '''$1''' modificacións}} {{PLURAL:$2|na última hora|nas últimas '''$2''' horas}}.",
'wlshowlast'           => 'Amosar as últimas $1 horas, os últimos $2 días ou $3',
'watchlist-options'    => 'Opcións de vixilancia',

# Displayed when you click the "watch" button and it is in the process of watching
'watching'   => 'Vixiando...',
'unwatching' => 'Deixando de vixiar...',

'enotif_mailer'                => 'Correo de aviso de {{SITENAME}}',
'enotif_reset'                 => 'Marcar todas as páxinas como visitadas',
'enotif_newpagetext'           => 'Esta é unha páxina nova.',
'enotif_impersonal_salutation' => 'usuario de {{SITENAME}}',
'changed'                      => 'modificada',
'created'                      => 'creada',
'enotif_subject'               => 'A páxina de {{SITENAME}} chamada "$PAGETITLE" foi $CHANGEDORCREATED por $PAGEEDITOR',
'enotif_lastvisited'           => 'Vexa $1 para comprobar todos os cambios desde a súa última visita.',
'enotif_lastdiff'              => 'Vexa $1 para visualizar esta modificación.',
'enotif_anon_editor'           => 'usuario anónimo $1',
'enotif_body'                  => 'Estimado $WATCHINGUSERNAME:


A páxina de {{SITENAME}} "$PAGETITLE" foi $CHANGEDORCREATED o $PAGEEDITDATE por $PAGEEDITOR, olle $PAGETITLE_URL para comprobar a versión actual.

$NEWPAGE

Resumo de edición: $PAGESUMMARY $PAGEMINOREDIT

Pode contactar co editor:
por correo electrónico: $PAGEEDITOR_EMAIL
no wiki: $PAGEEDITOR_WIKI

Non se producirán novas notificacións cando haxa novos cambios ata que vostede visite a páxina.
Pode borrar os indicadores de aviso de notificación para o conxunto das páxinas marcadas na súa lista de vixilancia.

             O sistema de aviso de {{SITENAME}}

--
Para cambiar a súa lista de vixilancia, visite
{{fullurl:{{#special:Watchlist}}/edit}}

Para borrar a páxina da súa lista de vixilancia, visite
$UNWATCHURL

Axuda:
{{fullurl:{{MediaWiki:Helppage}}}}',

# Delete
'deletepage'             => 'Borrar a páxina',
'confirm'                => 'Confirmar',
'excontent'              => 'o contido era: "$1"',
'excontentauthor'        => 'o contido era: "$1" (e o único editor foi "[[Special:Contributions/$2|$2]]")',
'exbeforeblank'          => 'o contido antes do baleirado era: "$1"',
'exblank'                => 'a páxina estaba baleira',
'delete-confirm'         => 'Borrar "$1"',
'delete-legend'          => 'Borrar',
'historywarning'         => "'''Atención:''' a páxina que está a piques de borrar ten un historial con aproximadamente $1 {{PLURAL:$1|revisión|revisións}}:",
'confirmdeletetext'      => 'Está a piques de borrar de xeito permanente unha páxina ou imaxe con todo o seu historial na base de datos.
Por favor, confirme que é realmente a súa intención, que comprende as consecuencias e que está obrando de acordo coas regras [[{{MediaWiki:Policy-url}}|da política e normas]].',
'actioncomplete'         => 'A acción foi completada',
'actionfailed'           => 'Fallou a acción',
'deletedtext'            => 'Borrouse a páxina "<nowiki>$1</nowiki>".
No $2 pode ver unha lista cos borrados máis recentes.',
'deletedarticle'         => 'borrou "[[$1]]"',
'suppressedarticle'      => 'suprimiu "[[$1]]"',
'dellogpage'             => 'Rexistro de borrados',
'dellogpagetext'         => 'A continuación atópase a lista cos borrados máis recentes.',
'deletionlog'            => 'rexistro de borrados',
'reverted'               => 'Volveuse a unha versión anterior',
'deletecomment'          => 'Razón para o borrado:',
'deleteotherreason'      => 'Outro motivo:',
'deletereasonotherlist'  => 'Outro motivo',
'deletereason-dropdown'  => '*Motivos frecuentes para borrar
** Solicitado polo autor
** Violación dos dereitos de autor
** Vandalismo',
'delete-edit-reasonlist' => 'Editar os motivos de borrado',
'delete-toobig'          => 'Esta páxina conta cun historial longo, de máis {{PLURAL:$1|dunha revisión|de $1 revisións}}.
Limitouse a eliminación destas páxinas para previr problemas de funcionamento accidentais en {{SITENAME}}.',
'delete-warning-toobig'  => 'Esta páxina conta cun historial de edicións longo, de máis {{PLURAL:$1|dunha revisión|de $1 revisións}}.
Ao eliminala pódense provocar problemas de funcionamento nas operacións da base de datos de {{SITENAME}};
proceda con coidado.',

# Rollback
'rollback'          => 'Reverter as edicións',
'rollback_short'    => 'Reverter',
'rollbacklink'      => 'reverter',
'rollbackfailed'    => 'Houbo un fallo ao reverter as edicións',
'cantrollback'      => 'Non se pode desfacer a edición; o último colaborador é o único autor desta páxina.',
'alreadyrolled'     => 'Non se pode desfacer a edición en "[[:$1]]" feita por [[User:$2|$2]] ([[User talk:$2|conversa]]{{int:pipe-separator}}[[Special:Contributions/$2|{{int:contribslink}}]]); alguén máis editou ou desfixo os cambios desta páxina.

A última edición fíxoa [[User:$3|$3]] ([[User talk:$3|conversa]]{{int:pipe-separator}}[[Special:Contributions/$2|{{int:contribslink}}]]).',
'editcomment'       => "O resumo de edición era: \"''\$1''\".",
'revertpage'        => 'Desfixéronse as edicións de [[Special:Contributions/$2|$2]] ([[User talk:$2|conversa]]); cambiado á última versión feita por [[User:$1|$1]]',
'revertpage-nouser' => 'Desfixéronse as edicións de (nome eliminado); cambiado á última versión feita por [[User:$1|$1]]',
'rollback-success'  => 'Desfixéronse as edicións de $1;
volveuse á última edición, feita por $2.',

# Edit tokens
'sessionfailure-title' => 'Erro de sesión',
'sessionfailure'       => 'Parece que hai un problema co rexistro da súa sesión;
esta acción cancelouse como precaución fronte ao secuestro de sesións.
Prema no botón "atrás", volva cargar a páxina da que proviña e inténteo de novo.',

# Protect
'protectlogpage'              => 'Rexistro de proteccións',
'protectlogtext'              => 'Embaixo móstrase unha lista dos bloqueos e desbloqueos de páxinas.
Vexa a [[Special:ProtectedPages|lista de páxinas protexidas]] se quere obter a lista coas proteccións de páxinas vixentes.',
'protectedarticle'            => 'protexeu "[[$1]]"',
'modifiedarticleprotection'   => 'modificou o nivel de protección de "[[$1]]"',
'unprotectedarticle'          => 'desprotexeu "[[$1]]"',
'movedarticleprotection'      => 'cambiou as características da protección de "[[$2]]" a "[[$1]]"',
'protect-title'               => 'Cambiar o nivel de protección de "$1"',
'prot_1movedto2'              => 'moveu "[[$1]]" a "[[$2]]"',
'protect-legend'              => 'Confirmar a protección',
'protectcomment'              => 'Motivo:',
'protectexpiry'               => 'Caducidade:',
'protect_expiry_invalid'      => 'O tempo de duración da protección non e válido.',
'protect_expiry_old'          => 'O momento de remate da protección corresponde ao pasado.',
'protect-unchain-permissions' => 'Desbloquear as opcións de protección adicionais',
'protect-text'                => "Aquí é onde pode ver e cambiar os niveis de protección da páxina chamada \"'''<nowiki>\$1</nowiki>'''\".",
'protect-locked-blocked'      => "Non pode modificar os niveis de protección mentres exista un bloqueo. Velaquí a configuración actual da páxina  '''$1''':",
'protect-locked-dblock'       => "Os niveis de protección non se poden modificar debido a un bloqueo da base de datos activa.
Velaquí a configuración actual da páxina '''$1''':",
'protect-locked-access'       => "A súa conta non dispón de permisos para mudar os niveis de protección.
Velaquí a configuración actual da páxina '''$1''':",
'protect-cascadeon'           => 'Esta páxina está protexida neste momento porque está incluída {{PLURAL:$1|na seguinte páxina, que foi protexida|nas seguintes páxinas, que foron protexidas}} coa opción protección en serie activada.
Pode mudar o nivel de protección da páxina pero iso non afectará á protección en serie.',
'protect-default'             => 'Permitir a todos os usuarios',
'protect-fallback'            => 'Require permisos de "$1"',
'protect-level-autoconfirmed' => 'Bloquear os usuarios novos e anónimos',
'protect-level-sysop'         => 'Só os administradores',
'protect-summary-cascade'     => 'protección en serie',
'protect-expiring'            => 'remata o $2 ás $3 (UTC)',
'protect-expiry-indefinite'   => 'indefinido',
'protect-cascade'             => 'Protexer as páxinas incluídas nesta (protección en serie)',
'protect-cantedit'            => 'Non pode modificar os niveis de protección desta páxina porque non ten os permisos necesarios para editala.',
'protect-othertime'           => 'Outro período:',
'protect-othertime-op'        => 'outro período',
'protect-existing-expiry'     => 'Período de caducidade actual: $2 ás $3',
'protect-otherreason'         => 'Outro motivo:',
'protect-otherreason-op'      => 'Outro motivo',
'protect-dropdown'            => '*Motivos frecuentes para a protección
** Vandalismo excesivo
** Publicidade excesiva
** Guerra de edicións
** Páxina moi visitada',
'protect-edit-reasonlist'     => 'Editar os motivos de protección',
'protect-expiry-options'      => '1 hora:1 hour,1 día:1 day,1 semana:1 week,2 semanas:2 weeks,1 mes:1 month,3 meses:3 months,6 meses:6 months,1 ano:1 year,para sempre:infinite',
'restriction-type'            => 'Permiso:',
'restriction-level'           => 'Nivel de protección:',
'minimum-size'                => 'Tamaño mínimo',
'maximum-size'                => 'Tamaño máximo:',
'pagesize'                    => '(bytes)',

# Restrictions (nouns)
'restriction-edit'   => 'Editar',
'restriction-move'   => 'Mover',
'restriction-create' => 'Crear',
'restriction-upload' => 'Cargar',

# Restriction levels
'restriction-level-sysop'         => 'protección completa',
'restriction-level-autoconfirmed' => 'semiprotexida',
'restriction-level-all'           => 'todos',

# Undelete
'undelete'                     => 'Ver as páxinas borradas',
'undeletepage'                 => 'Ver e restaurar páxinas borradas',
'undeletepagetitle'            => "'''A continuación amósanse as revisións eliminadas de''' \"'''[[:\$1|\$1]]'''\".",
'viewdeletedpage'              => 'Ver as páxinas borradas',
'undeletepagetext'             => '{{PLURAL:$1|A seguinte páxina foi borrada|As seguintes páxinas foron borradas}}, pero aínda {{PLURAL:$1|está|están}} no arquivo e {{PLURAL:$1|pode|poden}} ser {{PLURAL:$1|restaurada|restauradas}}.
O arquivo será limpado periodicamente.',
'undelete-fieldset-title'      => 'Restaurar as revisións',
'undeleteextrahelp'            => "Para restaurar o historial dunha páxina ao completo, deixe todas as caixas sen marcar e prema en '''''Restaurar'''''.
Para realizar unha recuperación parcial, marque só aquelas caixas que correspondan ás revisións que se queiran recuperar e prema en '''''Restaurar'''''.
Ao premer en '''''Limpar''''', bórranse o campo do comentario e todas as caixas.",
'undeleterevisions'            => '$1 {{PLURAL:$1|revisión arquivada|revisións arquivadas}}',
'undeletehistory'              => 'Se restaura a páxina, todas as revisións van ser restauradas no historial.
Se se creou unha páxina nova co mesmo nome desde o seu borrado, as revisións restauradas van aparecer no historial anterior.',
'undeleterevdel'               => 'Non se levará a cabo a reversión do borrado se ocasiona que a última revisión da páxina ou ficheiro se elimine parcialmente.
Nestes casos, debe retirar a selección ou quitar a ocultación das revisións borradas máis recentes.',
'undeletehistorynoadmin'       => 'Esta páxina foi borrada.
O motivo do borrado consta no resumo de embaixo, xunto cos detalles dos usuarios que editaron esta páxina antes da súa eliminación.
O texto destas revisións eliminadas só está á disposición dos administradores.',
'undelete-revision'            => 'Revisión eliminada de "$1" (o $4 ás $5) feita por $3:',
'undeleterevision-missing'     => 'Revisión non válida ou inexistente. Pode que a ligazón conteña un erro ou que a revisión se restaurase ou eliminase do arquivo.',
'undelete-nodiff'              => 'Non se atopou ningunha revisión anterior.',
'undeletebtn'                  => 'Restaurar',
'undeletelink'                 => 'ver/restaurar',
'undeleteviewlink'             => 'ver',
'undeletereset'                => 'Limpar',
'undeleteinvert'               => 'Inverter a selección',
'undeletecomment'              => 'Motivo:',
'undeletedarticle'             => 'restaurou "[[$1]]"',
'undeletedrevisions'           => '$1 {{PLURAL:$1|revisión restaurada|revisións restauradas}}',
'undeletedrevisions-files'     => '$1 {{PLURAL:$1|revisión|revisións}} e $2 {{PLURAL:$2|ficheiro restaurado|ficheiros restaurados}}',
'undeletedfiles'               => '$1 {{PLURAL:$1|ficheiro restaurado|ficheiros restaurados}}',
'cannotundelete'               => 'Non se restaurou a páxina porque alguén xa o fixo antes.',
'undeletedpage'                => "'''A páxina \"\$1\" foi restaurada'''

Comprobe o [[Special:Log/delete|rexistro de borrados]] para ver as entradas recentes no rexistro de páxinas eliminadas e restauradas.",
'undelete-header'              => 'Vexa [[Special:Log/delete|no rexistro de borrados]] as páxinas eliminadas recentemente.',
'undelete-search-box'          => 'Buscar páxinas borradas',
'undelete-search-prefix'       => 'Mostrar as páxinas que comecen por:',
'undelete-search-submit'       => 'Procurar',
'undelete-no-results'          => 'Non se atoparon páxinas coincidentes no arquivo de eliminacións.',
'undelete-filename-mismatch'   => 'Non se pode desfacer a eliminación da revisión do ficheiro datada en $1: non corresponde o nome do ficheiro',
'undelete-bad-store-key'       => 'Non se pode desfacer o borrado da revisión do ficheiro datada en $1: o ficheiro faltaba antes de proceder a borralo.',
'undelete-cleanup-error'       => 'Erro ao eliminar o ficheiro do arquivo sen usar "$1".',
'undelete-missing-filearchive' => 'Non foi posíbel restaurar o ID do arquivo do ficheiro $1 porque non figura na base de datos. Pode que xa se desfixese a eliminación con anterioridade.',
'undelete-error-short'         => 'Erro ao desfacer a eliminación do ficheiro: $1',
'undelete-error-long'          => 'Atopáronse erros ao desfacer a eliminación do ficheiro:

$1',
'undelete-show-file-confirm'   => 'Está seguro de querer ver unha revisión borrada do ficheiro "<nowiki>$1</nowiki>" do día $2 ás $3?',
'undelete-show-file-submit'    => 'Si',

# Namespace form on various pages
'namespace'      => 'Espazo de nomes:',
'invert'         => 'Inverter a selección',
'blanknamespace' => '(Principal)',

# Contributions
'contributions'       => 'Contribucións do usuario',
'contributions-title' => 'Contribucións de $1',
'mycontris'           => 'As miñas contribucións',
'contribsub2'         => 'De $1 ($2)',
'nocontribs'          => 'Non se deron atopado cambios con eses criterios.',
'uctop'               => '(última revisión)',
'month'               => 'Desde o mes de (e anteriores):',
'year'                => 'Desde o ano (e anteriores):',

'sp-contributions-newbies'             => 'Mostrar só as contribucións das contas de usuario novas',
'sp-contributions-newbies-sub'         => 'Contribucións dos usuarios novos',
'sp-contributions-newbies-title'       => 'Contribucións dos usuarios novos',
'sp-contributions-blocklog'            => 'rexistro de bloqueos',
'sp-contributions-deleted'             => 'contribucións borradas do usuario',
'sp-contributions-logs'                => 'rexistros',
'sp-contributions-talk'                => 'conversa',
'sp-contributions-userrights'          => 'xestión dos dereitos de usuario',
'sp-contributions-blocked-notice'      => 'Este usuario está bloqueado. Velaquí está a última entrada do rexistro de bloqueos, por se quere consultala:',
'sp-contributions-blocked-notice-anon' => 'Este enderezo IP está bloqueado.
Velaquí está a última entrada do rexistro de bloqueos, por se quere consultala:',
'sp-contributions-search'              => 'Busca de contribucións',
'sp-contributions-username'            => 'Enderezo IP ou nome de usuario:',
'sp-contributions-submit'              => 'Procurar',

# What links here
'whatlinkshere'            => 'Páxinas que ligan con esta',
'whatlinkshere-title'      => 'Páxinas que ligan con "$1"',
'whatlinkshere-page'       => 'Páxina:',
'linkshere'                => "As seguintes páxinas ligan con \"'''[[:\$1]]'''\":",
'nolinkshere'              => "Ningunha páxina liga con \"'''[[:\$1]]'''\".",
'nolinkshere-ns'           => "Ningunha páxina liga con \"'''[[:\$1]]'''\" no espazo de nomes elixido.",
'isredirect'               => 'páxina redirixida',
'istemplate'               => 'inclusión',
'isimage'                  => 'ligazón á imaxe',
'whatlinkshere-prev'       => '{{PLURAL:$1|anterior|$1 anteriores}}',
'whatlinkshere-next'       => '{{PLURAL:$1|seguinte|$1 seguintes}}',
'whatlinkshere-links'      => '← ligazóns',
'whatlinkshere-hideredirs' => '$1 as redireccións',
'whatlinkshere-hidetrans'  => '$1 as inclusións',
'whatlinkshere-hidelinks'  => '$1 as ligazóns',
'whatlinkshere-hideimages' => '$1 as ligazóns á imaxe',
'whatlinkshere-filters'    => 'Filtros',

# Block/unblock
'blockip'                         => 'Bloquear este usuario',
'blockip-title'                   => 'Bloquear un usuario',
'blockip-legend'                  => 'Bloquear un usuario',
'blockiptext'                     => 'Use o seguinte formulario para bloquear o acceso de escritura desde un enderezo IP ou para bloquear un usuario específico.
Isto debería facerse só para previr vandalismo, e de acordo coa [[{{MediaWiki:Policy-url}}|política e normas]] vixentes.
Explique a razón específica do bloqueo (por exemplo, citando as páxinas concretas que sufriron vandalismo).',
'ipaddress'                       => 'Enderezo IP:',
'ipadressorusername'              => 'Enderezo IP ou nome de usuario:',
'ipbexpiry'                       => 'Duración:',
'ipbreason'                       => 'Motivo:',
'ipbreasonotherlist'              => 'Outro motivo',
'ipbreason-dropdown'              => '*Motivos frecuentes para bloquear
** Inserir información falsa
** Eliminar o contido de páxinas
** Ligazóns lixo a sitios externos
** Inserir textos sen sentido ou inintelixíbeis
** Comportamento intimidatorio/acoso
** Abuso de múltiples contas de usuario
** Nome de usuario inaceptábel',
'ipbanononly'                     => 'Bloquear os usuarios anónimos unicamente',
'ipbcreateaccount'                => 'Previr a creación de contas',
'ipbemailban'                     => 'Impedir que o usuario envíe correos electrónicos',
'ipbenableautoblock'              => 'Bloquear automaticamente o último enderezo IP utilizado por este usuario, e calquera outro enderezo desde o que intente editar',
'ipbsubmit'                       => 'Bloquear este usuario',
'ipbother'                        => 'Outro período de tempo:',
'ipboptions'                      => '2 horas:2 hours,1 día:1 day,3 días:3 days,1 semana:1 week,2 semanas:2 weeks,1 mes:1 month,3 meses:3 months,6 meses:6 months,1 ano:1 year,para sempre:infinite',
'ipbotheroption'                  => 'outra',
'ipbotherreason'                  => 'Outro motivo:',
'ipbhidename'                     => 'Agochar o nome de usuario nas edicións e listas',
'ipbwatchuser'                    => 'Vixiar a páxina de usuario e a de conversa deste usuario',
'ipballowusertalk'                => 'Permitir que este usuario poida editar a súa páxina de conversa mentres estea bloqueado',
'ipb-change-block'                => 'Volver bloquear o usuario con estas configuracións',
'badipaddress'                    => 'O enderezo IP non é válido',
'blockipsuccesssub'               => 'Bloqueo exitoso',
'blockipsuccesstext'              => 'O enderezo IP [[Special:Contributions/$1|$1]] foi bloqueado.<br />
Olle a [[Special:IPBlockList|lista de enderezos IP e usuarios bloqueados]] para revisalo.',
'ipb-edit-dropdown'               => 'Editar os motivos de bloqueo',
'ipb-unblock-addr'                => 'Desbloquear a "$1"',
'ipb-unblock'                     => 'Desbloquear un usuario ou enderezo IP',
'ipb-blocklist-addr'              => 'Bloqueos vixentes de "$1"',
'ipb-blocklist'                   => 'Ver os bloqueos vixentes',
'ipb-blocklist-contribs'          => 'Contribucións de "$1"',
'unblockip'                       => 'Desbloquear o usuario',
'unblockiptext'                   => 'Use o seguinte formulario para dar de novo acceso de escritura a un enderezo IP ou usuario que estea bloqueado.',
'ipusubmit'                       => 'Retirar este bloqueo',
'unblocked'                       => '"[[User:$1|$1]]" foi desbloqueado',
'unblocked-id'                    => 'O bloqueo $1 foi eliminado',
'ipblocklist'                     => 'Enderezos IP e usuarios bloqueados',
'ipblocklist-legend'              => 'Buscar un usuario bloqueado',
'ipblocklist-username'            => 'Nome de usuario ou enderezo IP:',
'ipblocklist-sh-userblocks'       => '$1 as contas bloqueadas',
'ipblocklist-sh-tempblocks'       => '$1 os bloqueos temporais',
'ipblocklist-sh-addressblocks'    => '$1 os bloqueos únicos a enderezos IP',
'ipblocklist-submit'              => 'Procurar',
'ipblocklist-localblock'          => 'Bloqueo local',
'ipblocklist-otherblocks'         => '{{PLURAL:$1|Outro bloqueo|Outros bloqueos}}',
'blocklistline'                   => '$1, $2 bloqueou a "$3" ($4)',
'infiniteblock'                   => 'para sempre',
'expiringblock'                   => 'remata o $1 ás $2',
'anononlyblock'                   => 'só anón.',
'noautoblockblock'                => 'autobloqueo desactivado',
'createaccountblock'              => 'bloqueada a creación de contas',
'emailblock'                      => 'correo electrónico bloqueado',
'blocklist-nousertalk'            => 'non pode editar a súa conversa',
'ipblocklist-empty'               => 'A lista de bloqueos está baleira.',
'ipblocklist-no-results'          => 'Nin o enderezo IP nin o nome de usuario solicitados están bloqueados.',
'blocklink'                       => 'bloquear',
'unblocklink'                     => 'desbloquear',
'change-blocklink'                => 'cambiar o bloqueo',
'contribslink'                    => 'contribucións',
'autoblocker'                     => 'Foi autobloqueado porque "[[User:$1|$1]]" usou recentemente o seu  mesmo enderezo IP.
O motivo do bloqueo de $1 é: "$2"',
'blocklogpage'                    => 'Rexistro de bloqueos',
'blocklog-showlog'                => 'Este usuario xa foi bloqueado con anterioridade. Velaquí está o rexistro de bloqueos por se quere consultalo:',
'blocklog-showsuppresslog'        => 'Este usuario xa foi bloqueado e agochado con anterioridade. Velaquí está o rexistro de supresións por se quere consultalo:',
'blocklogentry'                   => 'bloqueou a "[[$1]]" cun tempo de duración de $2 $3',
'reblock-logentry'                => 'cambiou as configuracións do bloqueo de "[[$1]]" cunha caducidade de $2 $3',
'blocklogtext'                    => 'Este é o rexistro das accións de bloqueo e desbloqueo de usuarios.
Non se listan os enderezos IP bloqueados automaticamente.
Olle a [[Special:IPBlockList|lista de enderezos IP e usuarios bloqueados]] se quere comprobar a lista cos bloqueos vixentes.',
'unblocklogentry'                 => 'desbloqueou a "$1"',
'block-log-flags-anononly'        => 'só usuarios anónimos',
'block-log-flags-nocreate'        => 'desactivada a creación de contas de usuario',
'block-log-flags-noautoblock'     => 'bloqueo automático deshabilitado',
'block-log-flags-noemail'         => 'correo electrónico bloqueado',
'block-log-flags-nousertalk'      => 'desactivada a edición da súa conversa',
'block-log-flags-angry-autoblock' => 'realzou o autobloqueo permitido',
'block-log-flags-hiddenname'      => 'nome de usuario agochado',
'range_block_disabled'            => 'A funcionalidade de administrador de crear rangos de bloqueos está deshabilitada.',
'ipb_expiry_invalid'              => 'Tempo de duración non válido.',
'ipb_expiry_temp'                 => 'Os bloqueos a nomes de usuario agochados deberían ser permanentes.',
'ipb_hide_invalid'                => 'Incapaz de suprimir esta conta; pode que teña moitas edicións.',
'ipb_already_blocked'             => '"$1" xa está bloqueado',
'ipb-needreblock'                 => '== Xa está bloqueado ==
"$1" xa está bloqueado. Quere cambiar as configuracións?',
'ipb-otherblocks-header'          => '{{PLURAL:$1|Outro bloqueo|Outros bloqueos}}',
'ipb_cant_unblock'                => 'Erro: Non se atopa o Block ID $1. Posiblemente xa foi desbloqueado.',
'ipb_blocked_as_range'            => 'Erro: O enderezo IP $1 non está bloqueado directamente e non se pode desbloquear. Porén, está bloqueado por estar no rango $2, que si se pode desbloquear.',
'ip_range_invalid'                => 'Rango IP non válido.',
'ip_range_toolarge'               => 'Non están permitidos os rangos de bloqueo maiores que /$1.',
'blockme'                         => 'Bloquearme',
'proxyblocker'                    => 'Bloqueador de proxy',
'proxyblocker-disabled'           => 'Esta función está desactivada.',
'proxyblockreason'                => 'O seu enderezo IP foi bloqueado porque é un proxy aberto.
Por favor, contacte co seu fornecedor de acceso á Internet ou co seu soporte técnico e informe deste grave problema de seguridade.',
'proxyblocksuccess'               => 'Feito.',
'sorbsreason'                     => "O seu enderezo IP está rexistrado como un ''proxy'' aberto na lista DNSBL usada por {{SITENAME}}.",
'sorbs_create_account_reason'     => "O seu enderezo IP está rexistrado como un ''proxy'' aberto na lista DNSBL usada por {{SITENAME}}.
Polo tanto, non pode crear unha conta",
'cant-block-while-blocked'        => 'Non pode bloquear outros usuarios mentres vostede estea bloqueado.',
'cant-see-hidden-user'            => 'O usuario que intenta bloquear xa foi bloqueado e agochado. Dado que non ten o dereito necesario para agochar usuarios, non pode ver ou editar o bloqueo do usuario.',
'ipbblocked'                      => 'Non pode bloquear ou desbloquear outros usuarios porque vostede está bloqueado',
'ipbnounblockself'                => 'Non ten os permisos necesarios para desbloquearse a si mesmo',

# Developer tools
'lockdb'              => 'Fechar base de datos',
'unlockdb'            => 'Desbloquear a base de datos',
'lockdbtext'          => 'Ao fechar a base de datos quitaralles aos usuarios a posibilidade de editar páxinas, cambiar as súas preferencias, editar as súas listas de vixilancia e outras cousas que requiren cambios na base de datos.
Por favor, confirme que isto é o que realmente quere facer e que retirará o bloqueo da base de datos cando remate co mantemento.',
'unlockdbtext'        => 'O desbloqueo da base de datos vai permitir que os usuarios poidan editar páxinas, cambiar as súas preferencias, editar as súas listas de vixilancia e outras accións que requiran cambios na base de datos.
Por favor confirme que isto é o que quere facer.',
'lockconfirm'         => 'Si, realmente quero fechar a base de datos.',
'unlockconfirm'       => 'Si, realmente quero desbloquear a base de datos',
'lockbtn'             => 'Fechar base de datos',
'unlockbtn'           => 'Desbloquear a base de datos',
'locknoconfirm'       => 'Vostede non marcou o sinal de confirmación.',
'lockdbsuccesssub'    => 'A base de datos foi fechada con éxito',
'unlockdbsuccesssub'  => 'Quitouse a protección da base de datos',
'lockdbsuccesstext'   => 'Fechouse a base de datos.<br />
Lembre [[Special:UnlockDB|eliminar o bloqueo]] unha vez completado o seu mantemento.',
'unlockdbsuccesstext' => 'A base de datos foi desbloqueada.',
'lockfilenotwritable' => 'Non se pode escribir no ficheiro de bloqueo da base de datos. Para bloquear ou desbloquear a base de datos, o servidor web ten que poder escribir neste ficheiro.',
'databasenotlocked'   => 'A base de datos non está bloqueada.',

# Move page
'move-page'                    => 'Mover "$1"',
'move-page-legend'             => 'Mover páxina',
'movepagetext'                 => "Ao usar o formulario de embaixo vai cambiar o nome da páxina, movendo todo o seu historial ao novo nome.
O título vello vaise converter nunha páxina de redirección ao novo título.
Pode actualizar automaticamente as redireccións que van dar ao título orixinal.
Se escolle non facelo, asegúrese de verificar que non hai redireccións [[Special:DoubleRedirects|dobres]] ou [[Special:BrokenRedirects|crebadas]].
Vostede é responsábel de asegurarse de que as ligazóns continúan a apuntar cara a onde se supón que deberían.

Teña en conta que a páxina '''non''' será movida se xa existe unha páxina co novo título, a menos que estea baleira ou sexa unha redirección e que non teña historial de edicións.
Isto significa que pode volver renomear unha páxina ao seu nome antigo se comete un erro, e que non pode sobrescribir unha páxina que xa existe.

'''ATENCIÓN!'''
Este cambio nunha páxina popular pode ser drástico e inesperado;
por favor, asegúrese de que entende as consecuencias disto antes de proseguir.",
'movepagetalktext'             => "A páxina de conversa asociada, se existe, será automaticamente movida con esta '''agás que''':
*Estea a mover a páxina empregando espazos de nomes,
*Xa exista unha páxina de conversa con ese nome, ou
*Desactive a opción de abaixo.

Nestes casos, terá que mover ou mesturar a páxina manualmente se o desexa.",
'movearticle'                  => 'Mover esta páxina:',
'moveuserpage-warning'         => "'''Aviso:''' está a piques de mover unha páxina de usuario. Por favor, teña en conta que só se trasladará a páxina e que o usuario '''non''' será renomeado.",
'movenologin'                  => 'Non accedeu ao sistema',
'movenologintext'              => 'Debe ser un usuario rexistrado e [[Special:UserLogin|acceder ao sistema]] para mover unha páxina.',
'movenotallowed'               => 'Non ten os permisos necesarios para mover páxinas.',
'movenotallowedfile'           => 'Non ten os permisos necesarios para mover ficheiros.',
'cant-move-user-page'          => 'Non ten os permisos necesarios para mover páxinas de usuario (agás subpáxinas).',
'cant-move-to-user-page'       => 'Non ten os permisos necesarios para mover unha páxina a unha páxina de usuario (agás a unha subpáxina).',
'newtitle'                     => 'Ao novo título:',
'move-watch'                   => 'Vixiar esta páxina',
'movepagebtn'                  => 'Mover a páxina',
'pagemovedsub'                 => 'O movemento foi un éxito',
'movepage-moved'               => '\'\'\'A páxina "$1" foi movida a "$2"\'\'\'',
'movepage-moved-redirect'      => 'Creouse unha redirección da primeira cara á segunda.',
'movepage-moved-noredirect'    => 'A creación da redirección da primeira cara á segunda foi cancelada.',
'articleexists'                => 'Xa existe unha páxina con ese nome, ou o nome que escolleu non é válido.
Por favor, escolla outro nome.',
'cantmove-titleprotected'      => 'Non pode mover a páxina a este destino, xa que o novo título foi protexido fronte á creación',
'talkexists'                   => "'''Só foi movida con éxito a páxina, pero a páxina de conserva non puido ser movida porque xa existe unha co novo título. Por favor, mestúreas de xeito manual.'''",
'movedto'                      => 'movido a',
'movetalk'                     => 'Mover a páxina de conversa, se cómpre',
'move-subpages'                => 'Mover as subpáxinas (ata $1)',
'move-talk-subpages'           => 'Mover as subpáxinas da páxina de conversa (ata $1)',
'movepage-page-exists'         => 'A páxina "$1" xa existe e non pode ser sobrescrita automaticamente.',
'movepage-page-moved'          => 'A páxina "$1" foi movida a "$2".',
'movepage-page-unmoved'        => 'A páxina "$1" non pode ser movida a "$2".',
'movepage-max-pages'           => 'Foi movido o número máximo {{PLURAL:$1|dunha páxina|de $1 páxinas}} e non poderán ser movidas automaticamente máis.',
'1movedto2'                    => 'moveu "[[$1]]" a "[[$2]]"',
'1movedto2_redir'              => 'moveu "[[$1]]" a "[[$2]]" sobre unha redirección',
'move-redirect-suppressed'     => 'redirección suprimida',
'movelogpage'                  => 'Rexistro de traslados',
'movelogpagetext'              => 'A continuación móstrase a lista con todas as páxinas trasladadas.',
'movesubpage'                  => '{{PLURAL:$1|Subpáxina|Subpáxinas}}',
'movesubpagetext'              => 'Esta páxina ten $1 {{PLURAL:$1|subpáxina|subpáxinas}}.',
'movenosubpage'                => 'Esta páxina non ten subpáxinas.',
'movereason'                   => 'Motivo:',
'revertmove'                   => 'reverter',
'delete_and_move'              => 'Borrar e mover',
'delete_and_move_text'         => '==Precísase borrar==
A páxina de destino, chamada "[[:$1]]", xa existe.
Quérea eliminar para facer sitio para mover?',
'delete_and_move_confirm'      => 'Si, borrar a páxina',
'delete_and_move_reason'       => 'Eliminado para facer sitio para mover',
'selfmove'                     => 'O título de orixe e o de destino é o mesmo; non se pode mover unha páxina sobre si mesma.',
'immobile-source-namespace'    => 'Non se poden mover as páxinas que están no espazo de nomes "$1"',
'immobile-target-namespace'    => 'Non se poden mover as páxinas ao espazo de nomes "$1"',
'immobile-target-namespace-iw' => 'A ligazón interwiki non é válida para o movemento da páxina.',
'immobile-source-page'         => 'Esta páxina non se pode mover.',
'immobile-target-page'         => 'Non se pode mover a ese título.',
'imagenocrossnamespace'        => 'Non se pode mover o ficheiro a un espazo de nomes que non o admite',
'imagetypemismatch'            => 'A nova extensión do fiheiro non coincide co seu tipo',
'imageinvalidfilename'         => 'O nome da imaxe é inválido',
'fix-double-redirects'         => 'Actualizar calquera redirección que apunte cara ao título orixinal',
'move-leave-redirect'          => 'Deixar unha redirección detrás',
'protectedpagemovewarning'     => "'''Aviso:''' esta páxina foi protexida de xeito que só os usuarios con privilexios de administrador a poidan mover.
Velaquí está a última entrada no rexistro, por se quere consultala:",
'semiprotectedpagemovewarning' => "'''Nota:''' esta páxina foi protexida de xeito que só os usuarios rexistrados a poidan mover.
Velaquí está a última entrada no rexistro, por se quere consultala:",
'move-over-sharedrepo'         => '== O ficheiro xa existe ==
"[[:$1]]" xa existe nun repositorio compartido. Ao mover un ficheiro a este título sobrescribirase o ficheiro compartido.',
'file-exists-sharedrepo'       => 'O nome que elixiu para o ficheiro xa está en uso nun repositorio compartido.
Por favor, escolla outro nome.',

# Export
'export'            => 'Exportar páxinas',
'exporttext'        => 'Pode exportar o texto e o historial de edición dunha páxina calquera ou un conxunto de páxinas agrupadas nalgún ficheiro XML. Este pódese importar noutro wiki que utilice o programa MediaWiki mediante a [[Special:Import|páxina de importación]].

Para exportar páxinas, insira os títulos na caixa de texto que está máis abaixo, poñendo un título por liña, e se quere seleccione a versión actual e todas as versións vellas, coas liñas do historial da páxina, ou só a versión actual con información sobre a última edición.

No último caso, pode usar tamén unha ligazón, por exemplo [[{{#Special:Export}}/{{MediaWiki:Mainpage}}]], para a páxina "[[{{MediaWiki:Mainpage}}]]".',
'exportcuronly'     => 'Incluír só a revisión actual, non o historial completo',
'exportnohistory'   => "----
'''Aviso:''' foi desactivada a exportación do historial completo das páxinas mediante este formulario debido a razóns relacionadas co rendemento do servidor.",
'export-submit'     => 'Exportar',
'export-addcattext' => 'Engadir as páxinas da categoría:',
'export-addcat'     => 'Engadir',
'export-addnstext'  => 'Engadir as páxinas do espazo de nomes:',
'export-addns'      => 'Engadir',
'export-download'   => 'Ofrecer gardar como un ficheiro',
'export-templates'  => 'Incluír os modelos',
'export-pagelinks'  => 'Engadir as páxinas ligadas a unha profundidade de:',

# Namespace 8 related
'allmessages'                   => 'Todas as mensaxes do sistema',
'allmessagesname'               => 'Nome',
'allmessagesdefault'            => 'Texto predeterminado',
'allmessagescurrent'            => 'Texto actual',
'allmessagestext'               => 'Esta é unha lista de todas as mensaxes dispoñibles no espazo de nomes MediaWiki.
Por favor, visite a [http://www.mediawiki.org/wiki/Localisation localización MediaWiki] e [http://translatewiki.net translatewiki.net] se quere contribuír á localización xenérica de MediaWiki.',
'allmessagesnotsupportedDB'     => "'''{{ns:special}}:Allmessages''' non está dispoñíbel porque '''\$wgUseDatabaseMessages''' está desactivado.",
'allmessages-filter-legend'     => 'Filtrar',
'allmessages-filter'            => 'Filtrar por estado de personalización:',
'allmessages-filter-unmodified' => 'Inalteradas',
'allmessages-filter-all'        => 'Todas',
'allmessages-filter-modified'   => 'Modificadas',
'allmessages-prefix'            => 'Filtrar por prefixo:',
'allmessages-language'          => 'Lingua:',
'allmessages-filter-submit'     => 'Mostrar',

# Thumbnails
'thumbnail-more'           => 'Ampliar',
'filemissing'              => 'O ficheiro non se dá atopado',
'thumbnail_error'          => 'Erro ao crear a miniatura: $1',
'djvu_page_error'          => 'Páxina DjVu fóra de rango',
'djvu_no_xml'              => 'Foi imposíbel obter o XML para o ficheiro DjVu',
'thumbnail_invalid_params' => 'Parámetros de miniatura non válidos',
'thumbnail_dest_directory' => 'Foi imposíbel crear un directorio de destino',
'thumbnail_image-type'     => 'Tipo de imaxe non soportado',
'thumbnail_gd-library'     => 'Configuración da libraría GD incompleta: falta a función $1',
'thumbnail_image-missing'  => 'Parece que falta o ficheiro: $1',

# Special:Import
'import'                     => 'Importar páxinas',
'importinterwiki'            => 'Importación transwiki',
'import-interwiki-text'      => 'Seleccione o wiki e o título da páxina que queira importar.
As datas das revisións e os nomes dos editores manteranse.
Todas as accións relacionadas coa importación entre wikis poden verse no [[Special:Log/import|rexistro de importacións]].',
'import-interwiki-source'    => 'Wiki/Páxina de orixe:',
'import-interwiki-history'   => 'Copiar todas as versións que hai no historial desta páxina',
'import-interwiki-templates' => 'Incluír todos os modelos',
'import-interwiki-submit'    => 'Importar',
'import-interwiki-namespace' => 'Espazo de nomes de destino:',
'import-upload-filename'     => 'Nome do ficheiro:',
'import-comment'             => 'Comentario:',
'importtext'                 => 'Por favor, exporte o ficheiro do wiki de orixe usando a [[Special:Export|ferramenta de exportación]].
Gárdeo no seu disco duro e cárgueo aquí.',
'importstart'                => 'Importando páxinas...',
'import-revision-count'      => '$1 {{PLURAL:$1|revisión|revisións}}',
'importnopages'              => 'Non hai páxinas para importar.',
'imported-log-entries'       => '{{PLURAL:$1|Importouse unha entrada|Importáronse $1 entradas}} do rexisto.',
'importfailed'               => 'A importación fallou: $1',
'importunknownsource'        => 'Fonte de importación descoñecida',
'importcantopen'             => 'Non se pode abrir o ficheiro importado',
'importbadinterwiki'         => 'Ligazón interwiki incorrecta',
'importnotext'               => 'Texto baleiro ou inexistente',
'importsuccess'              => 'A importación rematou!',
'importhistoryconflict'      => 'Existe un conflito no historial de revisións (por ter importado esta páxina antes)',
'importnosources'            => 'Non se defininiu ningunha fonte de importación transwiki e os envíos directos dos historiais están desactivados.',
'importnofile'               => 'Non se enviou ningún ficheiro de importación.',
'importuploaderrorsize'      => 'Fallou o envío do ficheiro de importación. O ficheiro é máis grande que o tamaño de envío permitido.',
'importuploaderrorpartial'   => 'Fallou o envío do ficheiro de importación. O ficheiro só se enviou parcialmente.',
'importuploaderrortemp'      => 'Fallou o envío do ficheiro de importación. Falta un cartafol temporal.',
'import-parse-failure'       => 'Fallo de análise da importación de XML',
'import-noarticle'           => 'Ningunha páxina para importar!',
'import-nonewrevisions'      => 'Todas as revisións son previamente importadas.',
'xml-error-string'           => '$1 na liña $2, col $3 (byte $4): $5',
'import-upload'              => 'Cargar datos XML',
'import-token-mismatch'      => 'Perdéronse os datos da sesión. Por favor, inténteo de novo.',
'import-invalid-interwiki'   => 'Non se pode importar desde o wiki escificado.',

# Import log
'importlogpage'                    => 'Rexistro de importacións',
'importlogpagetext'                => 'Rexistro de importación de páxinas xunto co seu historial de edicións procedentes doutros wikis.',
'import-logentry-upload'           => 'importou "[[$1]]" mediante a carga dun ficheiro',
'import-logentry-upload-detail'    => '$1 {{PLURAL:$1|revisión|revisións}}',
'import-logentry-interwiki'        => 'importou "$1"',
'import-logentry-interwiki-detail' => '$1 {{PLURAL:$1|revisión|revisións}} de $2',

# Tooltip help for the actions
'tooltip-pt-userpage'             => 'A súa páxina de usuario',
'tooltip-pt-anonuserpage'         => 'A páxina de usuario da IP desde a que está a editar',
'tooltip-pt-mytalk'               => 'A súa páxina de conversa',
'tooltip-pt-anontalk'             => 'Conversa acerca de edicións feitas desde este enderezo IP',
'tooltip-pt-preferences'          => 'As miñas preferencias',
'tooltip-pt-watchlist'            => 'Lista de páxinas cuxas modificacións estou a seguir',
'tooltip-pt-mycontris'            => 'Lista das súas contribucións',
'tooltip-pt-login'                => 'Recoméndaselle que acceda ao sistema, porén, non é obrigatorio.',
'tooltip-pt-anonlogin'            => 'Recoméndaselle rexistrarse, se ben non é obrigatorio.',
'tooltip-pt-logout'               => 'Saír do sistema',
'tooltip-ca-talk'                 => 'Conversa acerca do contido desta páxina',
'tooltip-ca-edit'                 => 'Pode modificar esta páxina; antes de gardala, por favor, utilice o botón de vista previa',
'tooltip-ca-addsection'           => 'Comezar unha nova sección',
'tooltip-ca-viewsource'           => 'Esta páxina está protexida. Pode ver o código fonte.',
'tooltip-ca-history'              => 'Versións anteriores desta páxina',
'tooltip-ca-protect'              => 'Protexer esta páxina',
'tooltip-ca-unprotect'            => 'Desprotexer esta páxina',
'tooltip-ca-delete'               => 'Eliminar esta páxina',
'tooltip-ca-undelete'             => 'Restaurar as edicións feitas nesta páxina antes de que fose eliminada',
'tooltip-ca-move'                 => 'Mover esta páxina',
'tooltip-ca-watch'                => 'Engadir esta páxina á lista de vixilancia',
'tooltip-ca-unwatch'              => 'Eliminar esta páxina da súa lista de vixilancia',
'tooltip-search'                  => 'Procurar en {{SITENAME}}',
'tooltip-search-go'               => 'Ir a unha páxina con este texto exacto, se existe',
'tooltip-search-fulltext'         => 'Procurar este texto nas páxinas',
'tooltip-p-logo'                  => 'Portada',
'tooltip-n-mainpage'              => 'Visitar a Portada',
'tooltip-n-mainpage-description'  => 'Visitar a Portada',
'tooltip-n-portal'                => 'Acerca do proxecto, o que vostede pode facer, onde atopar cousas',
'tooltip-n-currentevents'         => 'Atopar documentación acerca de acontecementos de actualidade',
'tooltip-n-recentchanges'         => 'A lista de modificacións recentes no wiki.',
'tooltip-n-randompage'            => 'Cargar unha páxina ao chou',
'tooltip-n-help'                  => 'O lugar para informarse.',
'tooltip-t-whatlinkshere'         => 'Lista de todas as páxinas do wiki que ligan cara a aquí',
'tooltip-t-recentchangeslinked'   => 'Cambios recentes nas páxinas ligadas desde esta',
'tooltip-feed-rss'                => 'Fonte de novas RSS desta páxina',
'tooltip-feed-atom'               => 'Fonte de novas Atom desta páxina',
'tooltip-t-contributions'         => 'Ver a lista de contribucións deste usuario',
'tooltip-t-emailuser'             => 'Enviarlle unha mensaxe a este usuario por correo electrónico',
'tooltip-t-upload'                => 'Cargar os ficheiros',
'tooltip-t-specialpages'          => 'Lista de todas as páxinas especiais',
'tooltip-t-print'                 => 'Versión imprimíbel desta páxina',
'tooltip-t-permalink'             => 'Ligazón permanente a esta versión da páxina',
'tooltip-ca-nstab-main'           => 'Ver o contido da páxina',
'tooltip-ca-nstab-user'           => 'Ver a páxina do usuario',
'tooltip-ca-nstab-media'          => 'Ver a páxina con contido multimedia',
'tooltip-ca-nstab-special'        => 'Esta é unha páxina especial, polo que non a pode editar',
'tooltip-ca-nstab-project'        => 'Ver a páxina do proxecto',
'tooltip-ca-nstab-image'          => 'Ver a páxina do ficheiro',
'tooltip-ca-nstab-mediawiki'      => 'Ver a mensaxe do sistema',
'tooltip-ca-nstab-template'       => 'Ver o modelo',
'tooltip-ca-nstab-help'           => 'Ver a páxina de axuda',
'tooltip-ca-nstab-category'       => 'Ver a páxina da categoría',
'tooltip-minoredit'               => 'Marcar isto coma unha edición pequena',
'tooltip-save'                    => 'Gravar os seus cambios',
'tooltip-preview'                 => 'Vista previa dos seus cambios; por favor, úsea antes de gravalos!',
'tooltip-diff'                    => 'Mostrar os cambios que fixo no texto',
'tooltip-compareselectedversions' => 'Ver as diferenzas entre as dúas versións seleccionadas desta páxina',
'tooltip-watch'                   => 'Engadir esta páxina á súa lista de vixilancia [alt-w]',
'tooltip-recreate'                => 'Recrear a páxina a pesar de que foi borrada',
'tooltip-upload'                  => 'Comezar a enviar',
'tooltip-rollback'                => '"Reverter" desfai, cun só clic, a(s) edición(s) feita(s) nesta páxina polo último colaborador.',
'tooltip-undo'                    => '"Desfacer" reverte esta edición e abre o formulario de edición nun modo previo. Permite engadir un motivo no resumo de edición.',
'tooltip-preferences-save'        => 'Gardar as preferencias',
'tooltip-summary'                 => 'Escriba un breve resumo',

# Stylesheets
'common.css'   => '/** O CSS que se coloque aquí será aplicado a todas as aparencias */',
'monobook.css' => '/* O CSS que se coloque aquí afectará a quen use a aparencia Monobook */',

# Scripts
'common.js'   => '/* Calquera JavaScript será cargado para todos os usuarios en cada páxina cargada. */',
'monobook.js' => '/* O JavaScript que apareza aquí só será cargado aos usuarios que usan a aparencia MonoBook. */',

# Metadata
'nodublincore'      => 'A opción de metadatos RDF do Dublin Core está desactivada neste servidor.',
'nocreativecommons' => 'A opción de metadatos Creative Commons RDF está desactivada neste servidor.',
'notacceptable'     => 'O servidor wiki non pode fornecer datos nun formato que o seu cliente poida ler.',

# Attribution
'anonymous'        => '{{PLURAL:$1|Usuario anónimo|Usuarios anónimos}} de {{SITENAME}}',
'siteuser'         => '$1 de {{SITENAME}}',
'anonuser'         => 'o usuario anónimo $1 de {{SITENAME}}',
'lastmodifiedatby' => 'A última modificación desta páxina foi o $1 ás $2 por $3.',
'othercontribs'    => 'Baseado no traballo feito por $1.',
'others'           => 'outros',
'siteusers'        => '{{PLURAL:$2|$1}} de {{SITENAME}}',
'anonusers'        => '{{PLURAL:$2|o usuario anónimo|os usuarios anónimos}} $1 de {{SITENAME}}',
'creditspage'      => 'Páxina de créditos',
'nocredits'        => 'Non hai información de créditos dispoñíbel para esta páxina.',

# Spam protection
'spamprotectiontitle' => "Filtro de protección de ''spam''",
'spamprotectiontext'  => "A páxina que quixo gardar foi bloqueada polo filtro ''antispam''.
Isto, probabelmente, se debe a unha ligazón cara a un sitio externo que está na lista negra.",
'spamprotectionmatch' => "O seguinte texto foi o que activou o noso filtro de ''spam'': $1",
'spambot_username'    => "MediaWiki limpeza de ''spam''",
'spam_reverting'      => 'Revertida á última edición sen ligazóns a $1',
'spam_blanking'       => 'Limpáronse todas as revisións con ligazóns a "$1"',

# Info page
'infosubtitle'   => 'Información da páxina',
'numedits'       => 'Número de edicións (artigo): $1',
'numtalkedits'   => 'Número de edicións (páxina de conversa): $1',
'numwatchers'    => 'Número de vixiantes: $1',
'numauthors'     => 'Número de autores distintos (artigo): $1',
'numtalkauthors' => 'Número de autores distintos (páxina de conversa): $1',

# Skin names
'skinname-standard'    => 'Clásica',
'skinname-nostalgia'   => 'Morriña',
'skinname-cologneblue' => 'Azul colonial',
'skinname-monobook'    => 'MonoBook',
'skinname-myskin'      => 'A miña aparencia',
'skinname-chick'       => 'Parrulo',
'skinname-simple'      => 'Sinxela',
'skinname-modern'      => 'Moderna',

# Math options
'mw_math_png'    => 'Orixinar sempre unha imaxe PNG',
'mw_math_simple' => 'HTML se é moi simple, en caso contrario PNG',
'mw_math_html'   => 'Se é posible HTML, se non PNG',
'mw_math_source' => 'Deixalo como TeX (para navegadores de texto)',
'mw_math_modern' => 'Recomendado para as versións recentes dos navegadores',
'mw_math_mathml' => 'MathML se é posible (experimental)',

# Math errors
'math_failure'          => 'Fallou a conversión do código',
'math_unknown_error'    => 'erro descoñecido',
'math_unknown_function' => 'función descoñecida',
'math_lexing_error'     => 'erro de léxico',
'math_syntax_error'     => 'erro de sintaxe',
'math_image_error'      => 'Fallou a conversión a PNG; comprobe que latex, dvips, gs e convert están ben instalados',
'math_bad_tmpdir'       => 'Non se puido crear ou escribir no directorio temporal de fórmulas',
'math_bad_output'       => 'Non se puido crear ou escribir no directorio de saída de fórmulas',
'math_notexvc'          => 'Falta o executable texvc. Por favor consulte math/README para configurar.',

# Patrolling
'markaspatrolleddiff'                 => 'Marcar como revisada',
'markaspatrolledtext'                 => 'Marcar esta páxina como revisada',
'markedaspatrolled'                   => 'Marcar coma revisado',
'markedaspatrolledtext'               => 'A revisión seleccionada de "[[:$1]]" foi marcada como revisada.',
'rcpatroldisabled'                    => 'A patrulla dos cambios recentes está desactivada',
'rcpatroldisabledtext'                => 'A funcionalidade da patrulla dos cambios recentes está actualmente desactivada.',
'markedaspatrollederror'              => 'Non se pode marcar coma revisada',
'markedaspatrollederrortext'          => 'É preciso especificar unha revisión para marcala como revisada.',
'markedaspatrollederror-noautopatrol' => 'Non está permitido que un mesmo marque as propias edicións como revisadas.',

# Patrol log
'patrol-log-page'      => 'Rexistro de revisións',
'patrol-log-header'    => 'Este é un rexistro das revisións patrulladas.',
'patrol-log-line'      => 'marcou a $1 de "$2" como revisada $3',
'patrol-log-auto'      => '(automático)',
'patrol-log-diff'      => 'revisión $1',
'log-show-hide-patrol' => '$1 o rexistro de patrullas',

# Image deletion
'deletedrevision'                 => 'A revisión vella $1 foi borrada.',
'filedeleteerror-short'           => 'Erro ao eliminar o ficheiro: $1',
'filedeleteerror-long'            => 'Atopáronse erros ao eliminar o ficheiro:

$1',
'filedelete-missing'              => 'Non se pode eliminar o ficheiro "$1" porque non existe.',
'filedelete-old-unregistered'     => 'A versión do ficheiro especificada, "$1", non figura na base de datos.',
'filedelete-current-unregistered' => 'O ficheiro especificado, "$1", non figura na base de datos.',
'filedelete-archive-read-only'    => 'O servidor web non pode escribir no directorio de arquivo "$1".',

# Browsing diffs
'previousdiff' => '← Edición máis vella',
'nextdiff'     => 'Edición máis nova →',

# Media information
'mediawarning'         => "'''Aviso:''' este tipo de ficheiro pode conter código malicioso.
O seu sistema pode quedar comprometido se o executa.<hr />",
'imagemaxsize'         => "Límite de tamaño das imaxes:<br />''(nas páxinas de descrición de ficheiros)''",
'thumbsize'            => 'Tamaño da miniatura:',
'widthheightpage'      => '$1×$2, $3 {{PLURAL:$3|páxina|páxinas}}',
'file-info'            => 'Tamaño do ficheiro: $1, tipo MIME: $2',
'file-info-size'       => '($1 × $2 píxeles, tamaño do ficheiro: $3, tipo MIME: $4)',
'file-nohires'         => '<small>Non se dispón dunha resolución máis grande.</small>',
'svg-long-desc'        => '(ficheiro SVG, nominalmente $1 × $2 píxeles, tamaño do ficheiro: $3)',
'show-big-image'       => 'Imaxe na máxima resolución',
'show-big-image-thumb' => '<small>Tamaño desta presentación da imaxe: $1 × $2 píxeles</small>',
'file-info-gif-looped' => 'en bucle',
'file-info-gif-frames' => '$1 {{PLURAL:$1|fotograma|fotogramas}}',

# Special:NewFiles
'newimages'             => 'Galería de imaxes novas',
'imagelisttext'         => "A continuación amósase unha lista de '''$1''' {{PLURAL:$1|ficheiro|ficheiros}} ordenados $2.",
'newimages-summary'     => 'Esta páxina especial amosa os ficheiros cargados máis recentemente.',
'newimages-legend'      => 'Filtro',
'newimages-label'       => 'Nome do ficheiro (ou parte del):',
'showhidebots'          => '($1 os bots)',
'noimages'              => 'Non hai imaxes para ver.',
'ilsubmit'              => 'Procurar',
'bydate'                => 'por data',
'sp-newimages-showfrom' => 'Mostrar os novos ficheiros comezando polo $1 ás $2',

# Video information, used by Language::formatTimePeriod() to format lengths in the above messages
'minutes-abbrev' => 'min',

# Bad image list
'bad_image_list' => 'O formato é o seguinte:

Só se consideran os elementos dunha lista (liñas que comezan por *).
A primeira ligazón dunha liña ten que apuntar cara a un ficheiro mala.
As ligazóns posteriores da mesma liña considéranse excepcións, isto é, páxinas nas que o ficheiro pode aparecer inserido na liña.',

# Metadata
'metadata'          => 'Metadatos',
'metadata-help'     => 'Este ficheiro contén información adicional, probabelmente engadida pola cámara dixital ou polo escáner usado para crear ou dixitalizar a imaxe. Se o ficheiro orixinal foi modificado, pode que algúns detalles non se reflictan no ficheiro modificado.',
'metadata-expand'   => 'Mostrar os detalles',
'metadata-collapse' => 'Agochar os detalles',
'metadata-fields'   => 'Os campos de datos meta EXIF listados nesta mensaxe incluiranse ao exhibir a páxina da imaxe cando se reduza a táboa dos datos meta.
Os demais agocharanse por omisión.
* make
* model
* datetimeoriginal
* exposuretime
* fnumber
* isospeedratings
* focallength',

# EXIF tags
'exif-imagewidth'                  => 'Ancho',
'exif-imagelength'                 => 'Alto',
'exif-bitspersample'               => 'Bits por compoñente',
'exif-compression'                 => 'Esquema de compresión',
'exif-photometricinterpretation'   => 'Composición do píxel',
'exif-orientation'                 => 'Orientación',
'exif-samplesperpixel'             => 'Número de compoñentes',
'exif-planarconfiguration'         => 'Disposición dos datos',
'exif-ycbcrsubsampling'            => 'Razón de submostraxe de Y a C',
'exif-ycbcrpositioning'            => 'Posicionamentos Y e C',
'exif-xresolution'                 => 'Resolución horizontal',
'exif-yresolution'                 => 'Resolución vertical',
'exif-resolutionunit'              => 'Unidade de resolución X e Y',
'exif-stripoffsets'                => 'Localización dos datos da imaxe',
'exif-rowsperstrip'                => 'Número de filas por tira',
'exif-stripbytecounts'             => 'Bytes por tira comprimida',
'exif-jpeginterchangeformat'       => 'Distancia ao inicio (SOI) do JPEG',
'exif-jpeginterchangeformatlength' => 'Bytes de datos JPEG',
'exif-transferfunction'            => 'Función de transferencia',
'exif-whitepoint'                  => 'Coordenadas cromáticas de referencia do branco',
'exif-primarychromaticities'       => 'Cromacidades primarias',
'exif-ycbcrcoefficients'           => 'Coeficientes da matriz de transformación do espazo de cores',
'exif-referenceblackwhite'         => 'Par de valores de referencia branco e negro',
'exif-datetime'                    => 'Data e hora de modificación do ficheiro',
'exif-imagedescription'            => 'Título da imaxe',
'exif-make'                        => 'Fabricante da cámara',
'exif-model'                       => 'Modelo da cámara',
'exif-software'                    => 'Software utilizado',
'exif-artist'                      => 'Autor',
'exif-copyright'                   => 'Titular dos dereitos de autor',
'exif-exifversion'                 => 'Versión Exif',
'exif-flashpixversion'             => 'Versión de Flashpix soportada',
'exif-colorspace'                  => 'Espazo de cor',
'exif-componentsconfiguration'     => 'Significado de cada compoñente',
'exif-compressedbitsperpixel'      => 'Modo de compresión da imaxe',
'exif-pixelydimension'             => 'Ancho de imaxe válido',
'exif-pixelxdimension'             => 'Altura de imaxe válida',
'exif-makernote'                   => 'Notas do fabricante',
'exif-usercomment'                 => 'Comentarios do usuario',
'exif-relatedsoundfile'            => 'Ficheiro de son relacionado',
'exif-datetimeoriginal'            => 'Data e hora de xeración do ficheiro',
'exif-datetimedigitized'           => 'Data e hora de dixitalización',
'exif-subsectime'                  => 'DataHora subsegundos',
'exif-subsectimeoriginal'          => 'DataHoraOrixinal subsegundos',
'exif-subsectimedigitized'         => 'DataHoraDixitalización subsegundos',
'exif-exposuretime'                => 'Tempo de exposición',
'exif-exposuretime-format'         => '$1 segundos ($2)',
'exif-fnumber'                     => 'Número f',
'exif-exposureprogram'             => 'Programa de exposición',
'exif-spectralsensitivity'         => 'Sensibilidade espectral',
'exif-isospeedratings'             => 'Relación da velocidade ISO',
'exif-oecf'                        => 'Factor de conversión optoelectrónica',
'exif-shutterspeedvalue'           => 'Velocidade de obturación electrónica',
'exif-aperturevalue'               => 'Apertura',
'exif-brightnessvalue'             => 'Brillo',
'exif-exposurebiasvalue'           => 'Corrección da exposición',
'exif-maxaperturevalue'            => 'Máxima apertura do diafragma',
'exif-subjectdistance'             => 'Distancia do suxeito',
'exif-meteringmode'                => 'Modo de medida da exposición',
'exif-lightsource'                 => 'Fonte da luz',
'exif-flash'                       => 'Flash',
'exif-focallength'                 => 'Lonxitude focal',
'exif-subjectarea'                 => 'Área do suxeito',
'exif-flashenergy'                 => 'Enerxía do flash',
'exif-spatialfrequencyresponse'    => 'Resposta de frecuencia espacial',
'exif-focalplanexresolution'       => 'Resolución X do plano focal',
'exif-focalplaneyresolution'       => 'Resolución Y do plano focal',
'exif-focalplaneresolutionunit'    => 'Unidade de resolución do plano focal',
'exif-subjectlocation'             => 'Posición do suxeito',
'exif-exposureindex'               => 'Índice de exposición',
'exif-sensingmethod'               => 'Tipo de sensor',
'exif-filesource'                  => 'Fonte do ficheiro',
'exif-scenetype'                   => 'Tipo de escena',
'exif-cfapattern'                  => 'Patrón da matriz de filtro de cor',
'exif-customrendered'              => 'Procesamento da imaxe personalizado',
'exif-exposuremode'                => 'Modo de exposición',
'exif-whitebalance'                => 'Balance de brancos',
'exif-digitalzoomratio'            => 'Valor do zoom dixital',
'exif-focallengthin35mmfilm'       => 'Lonxitude focal na película de 35 mm',
'exif-scenecapturetype'            => 'Tipo de captura da escena',
'exif-gaincontrol'                 => 'Control de escena',
'exif-contrast'                    => 'Contraste',
'exif-saturation'                  => 'Saturación',
'exif-sharpness'                   => 'Nitidez',
'exif-devicesettingdescription'    => 'Descrición da configuración do dispositivo',
'exif-subjectdistancerange'        => 'Distancia ao suxeito',
'exif-imageuniqueid'               => 'ID única da imaxe',
'exif-gpsversionid'                => 'Versión da etiqueta GPS',
'exif-gpslatituderef'              => 'Latitude norte ou sur',
'exif-gpslatitude'                 => 'Latitude',
'exif-gpslongituderef'             => 'Lonxitude leste ou oeste',
'exif-gpslongitude'                => 'Lonxitude',
'exif-gpsaltituderef'              => 'Referencia da altitude',
'exif-gpsaltitude'                 => 'Altitude',
'exif-gpstimestamp'                => 'Hora GPS (reloxo atómico)',
'exif-gpssatellites'               => 'Satélites utilizados para a medida',
'exif-gpsstatus'                   => 'Estado do receptor',
'exif-gpsmeasuremode'              => 'Modo de medida',
'exif-gpsdop'                      => 'Precisión da medida',
'exif-gpsspeedref'                 => 'Unidade de velocidade',
'exif-gpsspeed'                    => 'Velocidade do receptor GPS',
'exif-gpstrackref'                 => 'Referencia para a dirección do movemento',
'exif-gpstrack'                    => 'Dirección do movemento',
'exif-gpsimgdirectionref'          => 'Referencia para a dirección da imaxe',
'exif-gpsimgdirection'             => 'Dirección da imaxe',
'exif-gpsmapdatum'                 => 'Usados datos xeodésicos de enquisas',
'exif-gpsdestlatituderef'          => 'Referencia para a latitude do destino',
'exif-gpsdestlatitude'             => 'Latitude do destino',
'exif-gpsdestlongituderef'         => 'Referencia para a lonxitude do destino',
'exif-gpsdestlongitude'            => 'Lonxitude do destino',
'exif-gpsdestbearingref'           => 'Referencia para a coordenada de destino',
'exif-gpsdestbearing'              => 'Coordenada de destino',
'exif-gpsdestdistanceref'          => 'Referencia para a distancia ao destino',
'exif-gpsdestdistance'             => 'Distancia ao destino',
'exif-gpsprocessingmethod'         => 'Nome do método de procesamento GPS',
'exif-gpsareainformation'          => 'Nome da área GPS',
'exif-gpsdatestamp'                => 'Data do GPS',
'exif-gpsdifferential'             => 'Corrección diferencial do GPS',

# EXIF attributes
'exif-compression-1' => 'Sen comprimir',

'exif-unknowndate' => 'Data descoñecida',

'exif-orientation-1' => 'Normal',
'exif-orientation-2' => 'Volteada horizontalmente',
'exif-orientation-3' => 'Rotada 180°',
'exif-orientation-4' => 'Volteada verticalmente',
'exif-orientation-5' => 'Rotada 90° CCW e volteada verticalmente',
'exif-orientation-6' => 'Rotada 90° CW',
'exif-orientation-7' => 'Rotada 90° CW e volteada verticalmente',
'exif-orientation-8' => 'Rotada 90° CCW',

'exif-planarconfiguration-1' => 'Formato de paquete de píxeles',
'exif-planarconfiguration-2' => 'Formato de planos',

'exif-componentsconfiguration-0' => 'non hai',

'exif-exposureprogram-0' => 'Sen definir',
'exif-exposureprogram-1' => 'Manual',
'exif-exposureprogram-2' => 'Programa normal',
'exif-exposureprogram-3' => 'Prioridade da apertura',
'exif-exposureprogram-4' => 'Prioridade da obturación',
'exif-exposureprogram-5' => 'Programa creativo (preferencia pola profundidade de campo)',
'exif-exposureprogram-6' => 'Programa de acción (preferencia por unha velocidade de exposición máis rápida)',
'exif-exposureprogram-7' => 'Modo retrato (para primeiros planos co fondo desenfocado)',
'exif-exposureprogram-8' => 'Modo paisaxe (para paisaxes co fondo enfocado)',

'exif-subjectdistance-value' => '$1 metros',

'exif-meteringmode-0'   => 'Descoñecido',
'exif-meteringmode-1'   => 'Media',
'exif-meteringmode-2'   => 'Ponderado no centro',
'exif-meteringmode-3'   => 'Un punto',
'exif-meteringmode-4'   => 'Varios puntos',
'exif-meteringmode-5'   => 'Patrón de medición',
'exif-meteringmode-6'   => 'Parcial',
'exif-meteringmode-255' => 'Outro',

'exif-lightsource-0'   => 'Descoñecida',
'exif-lightsource-1'   => 'Luz do día',
'exif-lightsource-2'   => 'Fluorescente',
'exif-lightsource-3'   => 'Tungsteno (luz incandescente)',
'exif-lightsource-4'   => 'Flash',
'exif-lightsource-9'   => 'Bo tempo',
'exif-lightsource-10'  => 'Tempo anubrado',
'exif-lightsource-11'  => 'Sombra',
'exif-lightsource-12'  => 'Fluorescente luz de día (D 5700 – 7100K)',
'exif-lightsource-13'  => 'Fluorescente branco día (N 4600 – 5400K)',
'exif-lightsource-14'  => 'Fluorescente branco frío (W 3900 – 4500K)',
'exif-lightsource-15'  => 'Fluorescente branco (WW 3200 – 3700K)',
'exif-lightsource-17'  => 'Luz estándar A',
'exif-lightsource-18'  => 'Luz estándar B',
'exif-lightsource-19'  => 'Luz estándar C',
'exif-lightsource-24'  => 'Tungsteno de estudio ISO',
'exif-lightsource-255' => 'Outra fonte de luz',

# Flash modes
'exif-flash-fired-0'    => 'Non se disparou o flash',
'exif-flash-fired-1'    => 'Disparouse o flash',
'exif-flash-return-0'   => 'sen a función de detección do retorno da luz',
'exif-flash-return-2'   => 'non se detectou a función do retorno da luz',
'exif-flash-return-3'   => 'detectouse a función do retorno da luz',
'exif-flash-mode-1'     => 'disparo obrigatorio do flash',
'exif-flash-mode-2'     => 'disparo do flash desactivado',
'exif-flash-mode-3'     => 'modo automático',
'exif-flash-function-1' => 'Sen función flash',
'exif-flash-redeye-1'   => 'modo de redución de ollos vermellos',

'exif-focalplaneresolutionunit-2' => 'polgadas',

'exif-sensingmethod-1' => 'Sen definir',
'exif-sensingmethod-2' => 'Sensor da área de cor dun chip',
'exif-sensingmethod-3' => 'Sensor da área de cor de dous chips',
'exif-sensingmethod-4' => 'Sensor da área de cor de tres chips',
'exif-sensingmethod-5' => 'Sensor secuencial da área de cor',
'exif-sensingmethod-7' => 'Sensor trilineal',
'exif-sensingmethod-8' => 'Sensor secuencial da liña de cor',

'exif-scenetype-1' => 'Unha imaxe fotografada directamente',

'exif-customrendered-0' => 'Procesamento normal',
'exif-customrendered-1' => 'Procesamento personalizado',

'exif-exposuremode-0' => 'Exposición automática',
'exif-exposuremode-1' => 'Exposición manual',
'exif-exposuremode-2' => 'Compensación de exposición automática',

'exif-whitebalance-0' => 'Balance de brancos automático',
'exif-whitebalance-1' => 'Balance de brancos manual',

'exif-scenecapturetype-0' => 'Estándar',
'exif-scenecapturetype-1' => 'Paisaxe',
'exif-scenecapturetype-2' => 'Retrato',
'exif-scenecapturetype-3' => 'Escena nocturna',

'exif-gaincontrol-0' => 'Ningunha',
'exif-gaincontrol-1' => 'Baixa ganancia superior',
'exif-gaincontrol-2' => 'Alta ganancia superior',
'exif-gaincontrol-3' => 'Baixa ganancia inferior',
'exif-gaincontrol-4' => 'Alta ganancia inferior',

'exif-contrast-0' => 'Normal',
'exif-contrast-1' => 'Suave',
'exif-contrast-2' => 'Forte',

'exif-saturation-0' => 'Normal',
'exif-saturation-1' => 'Saturación baixa',
'exif-saturation-2' => 'Saturación alta',

'exif-sharpness-0' => 'Normal',
'exif-sharpness-1' => 'Suave',
'exif-sharpness-2' => 'Forte',

'exif-subjectdistancerange-0' => 'Descoñecida',
'exif-subjectdistancerange-1' => 'Macro',
'exif-subjectdistancerange-2' => 'Primeiro plano',
'exif-subjectdistancerange-3' => 'Paisaxe',

# Pseudotags used for GPSLatitudeRef and GPSDestLatitudeRef
'exif-gpslatitude-n' => 'Latitude norte',
'exif-gpslatitude-s' => 'Latitude sur',

# Pseudotags used for GPSLongitudeRef and GPSDestLongitudeRef
'exif-gpslongitude-e' => 'Lonxitude leste',
'exif-gpslongitude-w' => 'Lonxitude oeste',

'exif-gpsstatus-a' => 'Medida en progreso',
'exif-gpsstatus-v' => 'Interoperabilidade da medida',

'exif-gpsmeasuremode-2' => 'Medida bidimensional',
'exif-gpsmeasuremode-3' => 'Medida tridimensional',

# Pseudotags used for GPSSpeedRef
'exif-gpsspeed-k' => 'Quilómetros por hora',
'exif-gpsspeed-m' => 'Millas por hora',
'exif-gpsspeed-n' => 'Nós',

# Pseudotags used for GPSTrackRef, GPSImgDirectionRef and GPSDestBearingRef
'exif-gpsdirection-t' => 'Dirección verdadeira',
'exif-gpsdirection-m' => 'Dirección magnética',

# External editor support
'edit-externally'      => 'Editar este ficheiro cunha aplicación externa',
'edit-externally-help' => '(Vexa as seguintes [http://www.mediawiki.org/wiki/Manual:External_editors instrucións] <small>(en inglés)</small> para máis información.)',

# 'all' in various places, this might be different for inflected languages
'recentchangesall' => 'todos',
'imagelistall'     => 'todas',
'watchlistall2'    => 'todo',
'namespacesall'    => 'todos',
'monthsall'        => 'todos',
'limitall'         => 'todas',

# E-mail address confirmation
'confirmemail'              => 'Confirmar o enderezo de correo electrónico',
'confirmemail_noemail'      => 'Non ten rexistrado ningún enderezo de correo electrónico válido nas súas [[Special:Preferences|preferencias de usuario]].',
'confirmemail_text'         => '{{SITENAME}} require que lle dea validez ao seu enderezo de correo electrónico antes de utilizar as funcións relacionadas con el.
Prema no botón de embaixo para enviar un correo de confirmación ao seu enderezo.
O correo incluirá unha ligazón cun código:
faga clic nesta ligazón para abrila no seu navegador web e así confirmar que o seu enderezo é válido.',
'confirmemail_pending'      => 'Envióuselle un código de confirmación ao enderezo de correo electrónico;
se creou a conta hai pouco debe esperar uns minutos antes de solicitar un novo código.',
'confirmemail_send'         => 'Enviar por correo elecrónico un código de confirmación',
'confirmemail_sent'         => 'Correo electrónico de confirmación enviado.',
'confirmemail_oncreate'     => 'Envióuselle un código de confirmación ao enderezo de correo electrónico. Este código non é imprescindible para entrar no wiki, pero é preciso para activar as funcións do wiki baseadas no correo.',
'confirmemail_sendfailed'   => '{{SITENAME}} non puido enviar a mensaxe de confirmación do correo.
Por favor, comprobe que no enderezo de correo electrónico non haxa caracteres inválidos.

O programa de correo informa do seguinte: $1',
'confirmemail_invalid'      => 'O código de confirmación non é válido.
Pode ser que caducase.',
'confirmemail_needlogin'    => 'Necesita $1 para confirmar o seu enderezo de correo electrónico.',
'confirmemail_success'      => 'Confirmouse o seu enderezo de correo electrónico. Agora xa pode [[Special:UserLogin|acceder ao sistema]] e facer uso do wiki.',
'confirmemail_loggedin'     => 'Xa se confirmou o seu enderezo de correo electrónico.',
'confirmemail_error'        => 'Houbo un problema ao gardar a súa confirmación.',
'confirmemail_subject'      => '{{SITENAME}} - Verificación do enderezo de correo electrónico',
'confirmemail_body'         => 'Alguén, probablemente vostede, desde o enderezo IP $1,
rexistrou a conta "$2" con este enderezo de correo electrónico en {{SITENAME}}.

Para confirmar que esta conta realmente lle pertence e así poder activar
as funcións de correo electrónico en {{SITENAME}}, abra esta ligazón no seu navegador:

$3

Se *non* rexistrou a conta siga estoutra ligazón
para cancelar a confirmación do enderezo de correo electrónico:

$5

Este código de confirmación caducará o $6 ás $7.',
'confirmemail_body_changed' => 'Alguén, probablemente vostede, desde o enderezo IP $1,
cambiou o enderezo de correo electrónico da conta "$2" a estoutro en {{SITENAME}}.

Para confirmar que esta conta realmente lle pertence e así poder reactivar
as funcións do correo electrónico en {{SITENAME}}, abra esta ligazón no seu navegador:

$3

Se a conta *non* lle pertence siga estoutra ligazón
para cancelar a confirmación do enderezo de correo electrónico:

$5

Este código de confirmación caducará o $4.',
'confirmemail_invalidated'  => 'A confirmación do enderezo de correo electrónico foi cancelada',
'invalidateemail'           => 'Cancelar a confirmación do correo electrónico',

# Scary transclusion
'scarytranscludedisabled' => '[A transclusión interwiki está desactivada]',
'scarytranscludefailed'   => '[Fallou a busca do modelo "$1"]',
'scarytranscludetoolong'  => '[O enderezo URL é demasiado longo]',

# Trackbacks
'trackbackbox'      => 'Trackbacks para esta páxina:<br />
$1',
'trackbackremove'   => '([$1 Borrar])',
'trackbacklink'     => 'Trackback',
'trackbackdeleteok' => 'O trackback foi eliminado sen problemas.',

# Delete conflict
'deletedwhileediting' => "'''Aviso:''' esta páxina foi borrada despois de que comezase a editala!",
'confirmrecreate'     => "O usuario [[User:$1|$1]] ([[User talk:$1|conversa]]) borrou este artigo despois de que vostede comezara a editalo, dando o seguinte motivo:
: ''$2'' 
Por favor, confirme que realmente quere recrear esta páxina.",
'recreate'            => 'Recrear',

# action=purge
'confirm_purge_button' => 'Si',
'confirm-purge-top'    => 'Quere limpar a memoria caché desta páxina?',
'confirm-purge-bottom' => 'Ao purgar unha páxina, límpase a memoria caché e isto obriga tamén a que apareza a versión máis recente da páxina.',

# Multipage image navigation
'imgmultipageprev' => '← páxina anterior',
'imgmultipagenext' => 'seguinte páxina →',
'imgmultigo'       => 'Ir!',
'imgmultigoto'     => 'Ir á páxina $1',

# Table pager
'ascending_abbrev'         => 'asc',
'descending_abbrev'        => 'desc',
'table_pager_next'         => 'Páxina seguinte',
'table_pager_prev'         => 'Páxina anterior',
'table_pager_first'        => 'Primeira páxina',
'table_pager_last'         => 'Última páxina',
'table_pager_limit'        => 'Mostrar $1 elementos por páxina',
'table_pager_limit_label'  => 'Elementos por páxina:',
'table_pager_limit_submit' => 'Ir',
'table_pager_empty'        => 'Sen resultados',

# Auto-summaries
'autosumm-blank'   => 'O contido da páxina foi eliminado',
'autosumm-replace' => 'O contido da páxina foi substituído por "$1"',
'autoredircomment' => 'Redirixida cara a "[[$1]]"',
'autosumm-new'     => 'Nova páxina: "$1"',

# Live preview
'livepreview-loading' => 'Cargando...',
'livepreview-ready'   => 'Cargando… Listo!',
'livepreview-failed'  => 'Fallou a vista previa en tempo real! Inténteo coa vista previa normal.',
'livepreview-error'   => 'Fallou a conexión: $1 "$2".
Probe coa vista previa normal.',

# Friendlier slave lag warnings
'lag-warn-normal' => 'Pode que os cambios feitos {{PLURAL:$1|no último segundo|nos últimos $1 segundos}} non aparezan nesta lista.',
'lag-warn-high'   => 'Debido a unha gran demora do servidor da base de datos, pode que nesta lista non aparezan os cambios feitos {{PLURAL:$1|no último segundo|nos últimos $1 segundos}}.',

# Watchlist editor
'watchlistedit-numitems'       => 'A súa lista de vixilancia inclúe {{PLURAL:$1|un título|$1 títulos}}, excluíndo as páxinas de conversa.',
'watchlistedit-noitems'        => 'A súa lista de vixilancia non contén ningún título.',
'watchlistedit-normal-title'   => 'Editar a lista de vixilancia',
'watchlistedit-normal-legend'  => 'Eliminar títulos da lista de vixilancia',
'watchlistedit-normal-explain' => 'Os títulos da súa lista de vixilancia aparecen a continuación.
Para eliminar un título, escóllao na súa caixa de selección e prema en "{{int:Watchlistedit-normal-submit}}".
Tamén pode [[Special:Watchlist/raw|editar a lista simple]].',
'watchlistedit-normal-submit'  => 'Eliminar os títulos',
'watchlistedit-normal-done'    => '{{PLURAL:$1|Eliminouse un título|Elimináronse $1 títulos}} da súa lista de vixilancia:',
'watchlistedit-raw-title'      => 'Editar a lista de vixilancia simple',
'watchlistedit-raw-legend'     => 'Editar a lista de vixilancia simple',
'watchlistedit-raw-explain'    => 'Os títulos da súa lista de vixilancia aparecen a continuación. Pódense editar engadíndoos ou retirándoos da lista; un título por liña.
Ao rematar, prema en "{{int:Watchlistedit-raw-submit}}".
Tamén pode [[Special:Watchlist/edit|empregar o editor normal]].',
'watchlistedit-raw-titles'     => 'Títulos:',
'watchlistedit-raw-submit'     => 'Actualizar a lista de vixilancia',
'watchlistedit-raw-done'       => 'Actualizouse a súa lista de vixilancia.',
'watchlistedit-raw-added'      => '{{PLURAL:$1|Engadiuse un título|Engadíronse $1 títulos}}:',
'watchlistedit-raw-removed'    => '{{PLURAL:$1|Eliminouse un título|Elimináronse $1 títulos}}:',

# Watchlist editing tools
'watchlisttools-view' => 'Ver as modificacións relevantes',
'watchlisttools-edit' => 'Ver e editar a lista de vixilancia',
'watchlisttools-raw'  => 'Editar a lista de vixilancia simple',

# Core parser functions
'unknown_extension_tag' => 'Etiqueta de extensión descoñecida "$1"',
'duplicate-defaultsort' => 'Aviso: a clave de ordenación por defecto "$2" anula a clave de ordenación anterior por defecto "$1".',

# Special:Version
'version'                          => 'Versión',
'version-extensions'               => 'Extensións instaladas',
'version-specialpages'             => 'Páxinas especiais',
'version-parserhooks'              => 'Asociadores analíticos',
'version-variables'                => 'Variábeis',
'version-other'                    => 'Outros',
'version-mediahandlers'            => 'Executadores de multimedia',
'version-hooks'                    => 'Asociadores',
'version-extension-functions'      => 'Funcións das extensións',
'version-parser-extensiontags'     => 'Etiquetas das extensións do analizador (parser)',
'version-parser-function-hooks'    => 'Asociadores da función do analizador',
'version-skin-extension-functions' => 'Funcións da extensión da aparencia',
'version-hook-name'                => 'Nome do hook',
'version-hook-subscribedby'        => 'Subscrito por',
'version-version'                  => '(Versión $1)',
'version-license'                  => 'Licenza',
'version-software'                 => 'Software instalado',
'version-software-product'         => 'Produto',
'version-software-version'         => 'Versión',

# Special:FilePath
'filepath'         => 'Ruta do ficheiro',
'filepath-page'    => 'Ficheiro:',
'filepath-submit'  => 'Ir',
'filepath-summary' => 'Esta páxina especial devolve a ruta completa dun ficheiro.
As imaxes móstranse na súa resolución completa; outros tipos de ficheiros inícianse directamente co seu programa asociado.

Introduza o nome do ficheiro sen o prefixo "{{ns:file}}:"',

# Special:FileDuplicateSearch
'fileduplicatesearch'          => 'Procurar ficheiros duplicados',
'fileduplicatesearch-summary'  => 'Procurar ficheiros duplicados a partir do valor de <i>hash</i> (un mecanismo de comprobación).

Introduza o nome do ficheiro sen o prefixo "{{ns:file}}:".',
'fileduplicatesearch-legend'   => 'Procurar un duplicado',
'fileduplicatesearch-filename' => 'Nome do ficheiro:',
'fileduplicatesearch-submit'   => 'Procurar',
'fileduplicatesearch-info'     => '$1 × $2 píxeles<br />Tamaño do ficheiro: $3<br />Tipo MIME: $4',
'fileduplicatesearch-result-1' => 'O ficheiro "$1" non ten un duplicado idéntico.',
'fileduplicatesearch-result-n' => 'O ficheiro "$1" ten {{PLURAL:$2|1 duplicado idéntico|$2 duplicados idénticos}}.',

# Special:SpecialPages
'specialpages'                   => 'Páxinas especiais',
'specialpages-note'              => '----
* Páxinas especiais normais.
* <strong class="mw-specialpagerestricted">Páxinas especiais restrinxidas.</strong>',
'specialpages-group-maintenance' => 'Informes de mantemento',
'specialpages-group-other'       => 'Outras páxinas especiais',
'specialpages-group-login'       => 'Rexistro',
'specialpages-group-changes'     => 'Cambios recentes e rexistros',
'specialpages-group-media'       => 'Informes multimedia e cargas',
'specialpages-group-users'       => 'Usuarios e dereitos',
'specialpages-group-highuse'     => 'Páxinas con máis uso',
'specialpages-group-pages'       => 'Listas de páxinas',
'specialpages-group-pagetools'   => 'Ferramentas das páxinas',
'specialpages-group-wiki'        => 'Datos do wiki e ferramentas',
'specialpages-group-redirects'   => 'Páxinas de redirección especiais',
'specialpages-group-spam'        => "Ferramentas contra o ''spam''",

# Special:BlankPage
'blankpage'              => 'Baleirar a páxina',
'intentionallyblankpage' => 'Esta páxina foi baleirada intencionadamente',

# External image whitelist
'external_image_whitelist' => ' #Deixe esta liña tal e como está<pre>
#Poña embaixo fragmentos de expresións regulares (tan só a parte que vai entre //)
#Isto coincidirá cos enderezos URL das imaxes externas (hotlinked)
#Aquelas que coincidan serán amosadas como imaxes, senón, só será amosada unha ligazón cara a esta
#As liñas que comecen por "#" son comentarios
#Non diferencia entre maiúsculas e minúsculas

#Poña todos os fragmentos por riba desta liña. Deixe esta liña tal e como está</pre>',

# Special:Tags
'tags'                    => 'Etiquetas de cambios válidas',
'tag-filter'              => 'Filtrar as [[Special:Tags|etiquetas]]:',
'tag-filter-submit'       => 'Filtro',
'tags-title'              => 'Etiquetas',
'tags-intro'              => 'Esta páxina lista as etiquetas coas que o software pode marcar unha edición, e mailos seus significados.',
'tags-tag'                => 'Nome da etiqueta',
'tags-display-header'     => 'Aparición nas listas de cambios',
'tags-description-header' => 'Descrición completa do significado',
'tags-hitcount-header'    => 'Edicións etiquetadas',
'tags-edit'               => 'editar',
'tags-hitcount'           => '$1 {{PLURAL:$1|cambio|cambios}}',

# Database error messages
'dberr-header'      => 'Este wiki ten un problema',
'dberr-problems'    => 'Sentímolo! Este sitio está experimentando dificultades técnicas.',
'dberr-again'       => 'Por favor, agarde uns minutos e logo probe a cargar de novo a páxina.',
'dberr-info'        => '(Non se pode conectar coa base de datos do servidor: $1)',
'dberr-usegoogle'   => 'Mentres tanto, pode probar a buscar co Google.',
'dberr-outofdate'   => 'Teña en conta que os índices de Google do noso contido poden non estar actualizados.',
'dberr-cachederror' => 'O seguinte contido é unha copia da memoria caché da páxina solicitada, polo que pode non estar actualizada.',

# HTML forms
'htmlform-invalid-input'       => 'Hai algún problema con partes do texto que inseriu',
'htmlform-select-badoption'    => 'O valor que especificou non é unha opción válida.',
'htmlform-int-invalid'         => 'O valor que especificou non é un número enteiro.',
'htmlform-float-invalid'       => 'O valor que especificou non é un número.',
'htmlform-int-toolow'          => 'O valor que especificou está por baixo do mínimo de $1',
'htmlform-int-toohigh'         => 'O valor que especificou está por riba do máximo de $1',
'htmlform-required'            => 'Este valor é obrigatorio',
'htmlform-submit'              => 'Enviar',
'htmlform-reset'               => 'Desfacer os cambios',
'htmlform-selectorother-other' => 'Outra',

# Add categories per AJAX
'ajax-add-category'            => 'Engadir unha categoría',
'ajax-add-category-submit'     => 'Engadir',
'ajax-confirm-title'           => 'Confirmar a acción',
'ajax-confirm-prompt'          => 'Pode proporcionar un resumo de edición a continuación.
Prema sobre o botón "Gardar" para gardar a súa edición.',
'ajax-confirm-save'            => 'Gardar',
'ajax-add-category-summary'    => 'Engadir a categoría "$1"',
'ajax-remove-category-summary' => 'Eliminar a categoría "$1"',
'ajax-confirm-actionsummary'   => 'Acción a levar a cabo:',
'ajax-error-title'             => 'Erro',
'ajax-error-dismiss'           => 'De acordo',
'ajax-remove-category-error'   => 'Non se puido eliminar esta categoría.
Normalmente isto ocorre cando a categoría foi engadida á páxina a través dun modelo.',

);
