<?php
/** Portuguese (Português)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Crazymadlover
 * @author Giro720
 * @author Hamilton Abreu
 * @author Heldergeovane
 * @author Indech
 * @author Jens Liebenau
 * @author Jorge Morais
 * @author Leonardo.stabile
 * @author Lijealso
 * @author Lugusto
 * @author MCruz
 * @author MF-Warburg
 * @author Malafaya
 * @author Manuel Menezes de Sequeira
 * @author McDutchie
 * @author Minh Nguyen
 * @author Nuno Tavares
 * @author Paulo Juntas
 * @author Rafael Vargas
 * @author Rei-artur
 * @author Remember the dot
 * @author Rodrigo Calanca Nishino
 * @author Sir Lestaty de Lioncourt
 * @author Sérgio Ribeiro
 * @author Urhixidur
 * @author Villate
 * @author Waldir
 * @author Yves Marques Junqueira
 * @author לערי ריינהארט
 * @author 555
 */

$namespaceNames = array(
	NS_MEDIA            => 'Media',
	NS_SPECIAL          => 'Especial',
	NS_TALK             => 'Discussão',
	NS_USER             => 'Usuário',
	NS_USER_TALK        => 'Usuário_Discussão',
	NS_PROJECT_TALK     => '$1_Discussão',
	NS_FILE             => 'Ficheiro',
	NS_FILE_TALK        => 'Ficheiro_Discussão',
	NS_MEDIAWIKI        => 'MediaWiki',
	NS_MEDIAWIKI_TALK   => 'MediaWiki_Discussão',
	NS_TEMPLATE         => 'Predefinição',
	NS_TEMPLATE_TALK    => 'Predefinição_Discussão',
	NS_HELP             => 'Ajuda',
	NS_HELP_TALK        => 'Ajuda_Discussão',
	NS_CATEGORY         => 'Categoria',
	NS_CATEGORY_TALK    => 'Categoria_Discussão',
);

$namespaceAliases = array(
	'Imagem' => NS_FILE,
	'Imagem_Discussão' => NS_FILE_TALK,
	'Arquivo' => NS_FILE,
	'Arquivo_Discussão' => NS_FILE_TALK,
);

$defaultDateFormat = 'dmy';

$dateFormats = array(

	'dmy time' => 'H\hi\m\i\n',
	'dmy date' => 'j \d\e F \d\e Y',
	'dmy both' => 'H\hi\m\i\n \d\e j \d\e F \d\e Y',

);

$separatorTransformTable = array(',' => ' ', '.' => ',' );
$linkTrail = '/^([áâãàéêçíóôõúüa-z]+)(.*)$/sDu'; # Bug 21168

$specialPageAliases = array(
	'DoubleRedirects'           => array( 'Redireccionamentos duplos', 'Redirecionamentos duplos' ),
	'BrokenRedirects'           => array( 'Redireccionamentos quebrados', 'Redirecionamentos quebrados' ),
	'Disambiguations'           => array( 'Páginas de desambiguação', 'Desambiguar', 'Desambiguações' ),
	'Userlogin'                 => array( 'Entrar', 'Login' ),
	'Userlogout'                => array( 'Sair', 'Logout' ),
	'CreateAccount'             => array( 'Criar conta' ),
	'Preferences'               => array( 'Preferências' ),
	'Watchlist'                 => array( 'Páginas vigiadas', 'Artigos vigiados', 'Vigiados' ),
	'Recentchanges'             => array( 'Mudanças recentes' ),
	'Upload'                    => array( 'Carregar imagem', 'Carregar ficheiro', 'Carregar arquivo', 'Enviar' ),
	'Listfiles'                 => array( 'Lista de imagens', 'Lista de ficheiros', 'Lista de arquivos' ),
	'Newimages'                 => array( 'Imagens novas', 'Ficheiros novos', 'Arquivos novos' ),
	'Listusers'                 => array( 'Lista de utilizadores', 'Lista de usuários' ),
	'Listgrouprights'           => array( 'Listar privilégios de grupos' ),
	'Statistics'                => array( 'Estatísticas' ),
	'Randompage'                => array( 'Aleatória', 'Aleatório', 'Página aleatória', 'Artigo aleatório' ),
	'Lonelypages'               => array( 'Páginas órfãs', 'Artigos órfãos', 'Páginas sem afluentes', 'Artigos sem afluentes' ),
	'Uncategorizedpages'        => array( 'Páginas sem categorias', 'Artigos sem categorias' ),
	'Uncategorizedcategories'   => array( 'Categorias sem categorias' ),
	'Uncategorizedimages'       => array( 'Imagens sem categorias', 'Ficheiros sem categorias', 'Arquivos sem categorias' ),
	'Uncategorizedtemplates'    => array( 'Predefinições não categorizadas', 'Predefinições sem categorias' ),
	'Unusedcategories'          => array( 'Categorias não utilizadas', 'Categorias sem uso' ),
	'Unusedimages'              => array( 'Imagens sem uso', 'Imagens não utilizadas', 'Ficheiros sem uso', 'Ficheiros não utilizados', 'Arquivos sem uso', 'Arquivos não utilizados' ),
	'Wantedpages'               => array( 'Páginas em falta', 'Artigos em falta', 'Páginas pedidas', 'Artigos pedidos' ),
	'Wantedcategories'          => array( 'Categorias em falta', 'Categorias inexistentes' ),
	'Wantedfiles'               => array( 'Ficheiros em falta', 'Arquivos em falta', 'Imagens em falta' ),
	'Wantedtemplates'           => array( 'Predefinições em falta' ),
	'Mostlinked'                => array( 'Páginas com mais afluentes', 'Artigos com mais afluentes' ),
	'Mostlinkedcategories'      => array( 'Categorias com mais afluentes' ),
	'Mostlinkedtemplates'       => array( 'Predefinições com mais afluentes' ),
	'Mostimages'                => array( 'Imagens com mais afluentes', 'Ficheiros com mais afluentes', 'Arquivos com mais afluentes' ),
	'Mostcategories'            => array( 'Páginas com mais categorias', 'Artigos com mais categorias' ),
	'Mostrevisions'             => array( 'Páginas com mais edições', 'Artigos com mais edições' ),
	'Fewestrevisions'           => array( 'Páginas com menos edições', 'Artigos com menos edições', 'Artigos menos editados' ),
	'Shortpages'                => array( 'Páginas curtas', 'Artigos curtos' ),
	'Longpages'                 => array( 'Páginas longas', 'Artigos extensos' ),
	'Newpages'                  => array( 'Páginas novas', 'Artigos novos' ),
	'Ancientpages'              => array( 'Páginas inativas', 'Artigos inativos' ),
	'Deadendpages'              => array( 'Páginas sem saída', 'Artigos sem saída' ),
	'Protectedpages'            => array( 'Páginas protegidas', 'Artigos protegidos' ),
	'Protectedtitles'           => array( 'Títulos protegidos' ),
	'Allpages'                  => array( 'Todas as páginas', 'Todos os artigos', 'Todas páginas', 'Todos artigos' ),
	'Prefixindex'               => array( 'Índice de prefixo', 'Índice por prefixo' ),
	'Ipblocklist'               => array( 'Registo de bloqueios', 'Registro de bloqueios', 'IPs bloqueados', 'Utilizadores bloqueados', 'Usuários bloqueados' ),
	'Specialpages'              => array( 'Páginas especiais' ),
	'Contributions'             => array( 'Contribuições' ),
	'Emailuser'                 => array( 'Contactar utilizador', 'Contactar usuário', 'Contatar usuário' ),
	'Confirmemail'              => array( 'Confirmar e-mail', 'Confirmar email' ),
	'Whatlinkshere'             => array( 'Páginas afluentes', 'Artigos afluentes' ),
	'Recentchangeslinked'       => array( 'Novidades relacionadas', 'Mudanças relacionadas' ),
	'Movepage'                  => array( 'Mover', 'Mover página', 'Mover artigo' ),
	'Blockme'                   => array( 'Bloquear-me', 'Auto-bloqueio' ),
	'Booksources'               => array( 'Fontes de livros' ),
	'Categories'                => array( 'Categorias' ),
	'Export'                    => array( 'Exportar' ),
	'Version'                   => array( 'Versão', 'Sobre' ),
	'Allmessages'               => array( 'Todas as mensagens', 'Todas mensagens' ),
	'Log'                       => array( 'Registo', 'Registro', 'Registos', 'Registros' ),
	'Blockip'                   => array( 'Bloquear', 'Bloquear IP', 'Bloquear utilizador', 'Bloquear usuário' ),
	'Undelete'                  => array( 'Restaurar', 'Restaurar páginas eliminadas', 'Restaurar artigos eliminados' ),
	'Import'                    => array( 'Importar' ),
	'Lockdb'                    => array( 'Bloquear a base de dados', 'Bloquear banco de dados' ),
	'Unlockdb'                  => array( 'Desbloquear a base de dados', 'Desbloquear banco de dados' ),
	'Userrights'                => array( 'Privilégios', 'Direitos', 'Estatutos' ),
	'MIMEsearch'                => array( 'Busca MIME' ),
	'FileDuplicateSearch'       => array( 'Busca de ficheiros duplicados', 'Busca de arquivos duplicados' ),
	'Unwatchedpages'            => array( 'Páginas não-vigiadas', 'Páginas não vigiadas', 'Artigos não-vigiados', 'Artigos não vigiados' ),
	'Listredirects'             => array( 'Redireccionamentos', 'Redirecionamentos', 'Lista de redireccionamentos', 'Lista de redirecionamentos' ),
	'Revisiondelete'            => array( 'Eliminar edição', 'Eliminar revisão', 'Apagar edição', 'Apagar revisão' ),
	'Unusedtemplates'           => array( 'Predefinições sem uso', 'Predefinições não utilizadas' ),
	'Randomredirect'            => array( 'Redireccionamento aleatório', 'Redirecionamento aleatório' ),
	'Mypage'                    => array( 'Minha página' ),
	'Mytalk'                    => array( 'Minha discussão' ),
	'Mycontributions'           => array( 'Minhas contribuições', 'Minhas edições', 'Minhas constribuições' ),
	'Listadmins'                => array( 'Administradores', 'Admins', 'Lista de administradores', 'Lista de admins' ),
	'Listbots'                  => array( 'Bots', 'Lista de bots' ),
	'Popularpages'              => array( 'Páginas populares', 'Artigos populares' ),
	'Search'                    => array( 'Busca', 'Buscar', 'Procurar', 'Pesquisar', 'Pesquisa' ),
	'Resetpass'                 => array( 'Repor senha', 'Zerar senha' ),
	'Withoutinterwiki'          => array( 'Páginas sem interwikis', 'Artigos sem interwikis' ),
	'MergeHistory'              => array( 'Fundir históricos', 'Fundir edições' ),
	'Filepath'                  => array( 'Diretório de ficheiro', 'Diretório de arquivo' ),
	'Invalidateemail'           => array( 'Invalidar e-mail' ),
	'Blankpage'                 => array( 'Página em branco' ),
	'LinkSearch'                => array( 'Pesquisar links' ),
	'DeletedContributions'      => array( 'Contribuições eliminadas', 'Edições eliminadas' ),
);

$magicWords = array(
	'redirect'              => array( '0', '#REDIRECIONAMENTO', '#REDIRECT' ),
	'notoc'                 => array( '0', '__SEMTDC__', '__SEMSUMÁRIO__', '__NOTOC__' ),
	'nogallery'             => array( '0', '__SEMGALERIA__', '__NOGALLERY__' ),
	'forcetoc'              => array( '0', '__FORCARTDC__', '__FORCARSUMARIO__', '__FORÇARTDC__', '__FORÇARSUMÁRIO__', '__FORCETOC__' ),
	'toc'                   => array( '0', '__TDC__', '__SUMÁRIO__', '__TOC__' ),
	'noeditsection'         => array( '0', '__NÃOEDITARSEÇÃO__', '__SEMEDITARSEÇÃO__', '__NOEDITSECTION__' ),
	'noheader'              => array( '0', '__SEMCABECALHO__', '__SEMCABEÇALHO__', '__SEMTITULO__', '__SEMTÍTULO__', '__NOHEADER__' ),
	'currentmonth'          => array( '1', 'MESATUAL', 'MESATUAL2', 'CURRENTMONTH', 'CURRENTMONTH2' ),
	'currentmonth1'         => array( '1', 'MESATUAL1', 'CURRENTMONTH1' ),
	'currentmonthname'      => array( '1', 'NOMEDOMESATUAL', 'CURRENTMONTHNAME' ),
	'currentmonthabbrev'    => array( '1', 'MESATUALABREV', 'MESATUALABREVIADO', 'ABREVIATURADOMESATUAL', 'CURRENTMONTHABBREV' ),
	'currentday'            => array( '1', 'DIAATUAL', 'CURRENTDAY' ),
	'currentday2'           => array( '1', 'DIAATUAL2', 'CURRENTDAY2' ),
	'currentdayname'        => array( '1', 'NOMEDODIAATUAL', 'CURRENTDAYNAME' ),
	'currentyear'           => array( '1', 'ANOATUAL', 'CURRENTYEAR' ),
	'currenttime'           => array( '1', 'HORARIOATUAL', 'CURRENTTIME' ),
	'currenthour'           => array( '1', 'HORAATUAL', 'CURRENTHOUR' ),
	'localmonth'            => array( '1', 'MESLOCAL', 'LOCALMONTH', 'LOCALMONTH2' ),
	'localmonth1'           => array( '1', 'MESLOCAL1', 'LOCALMONTH1' ),
	'localmonthname'        => array( '1', 'NOMEDOMESLOCAL', 'LOCALMONTHNAME' ),
	'localmonthabbrev'      => array( '1', 'MESLOCALABREV', 'MESLOCALABREVIADO', 'ABREVIATURADOMESLOCAL', 'LOCALMONTHABBREV' ),
	'localday'              => array( '1', 'DIALOCAL', 'LOCALDAY' ),
	'localday2'             => array( '1', 'DIALOCAL2', 'LOCALDAY2' ),
	'localdayname'          => array( '1', 'NOMEDODIALOCAL', 'LOCALDAYNAME' ),
	'localyear'             => array( '1', 'ANOLOCAL', 'LOCALYEAR' ),
	'localtime'             => array( '1', 'HORARIOLOCAL', 'LOCALTIME' ),
	'localhour'             => array( '1', 'HORALOCAL', 'LOCALHOUR' ),
	'numberofpages'         => array( '1', 'NUMERODEPAGINAS', 'NÚMERODEPÁGINAS', 'NUMBEROFPAGES' ),
	'numberofarticles'      => array( '1', 'NUMERODEARTIGOS', 'NÚMERODEARTIGOS', 'NUMBEROFARTICLES' ),
	'numberoffiles'         => array( '1', 'NUMERODEARQUIVOS', 'NÚMERODEARQUIVOS', 'NUMBEROFFILES' ),
	'numberofusers'         => array( '1', 'NUMERODEUSUARIOS', 'NÚMERODEUSUÁRIOS', 'NUMBEROFUSERS' ),
	'numberofactiveusers'   => array( '1', 'NUMERODEUSUARIOSATIVOS', 'NÚMERODEUSUÁRIOSATIVOS', 'NUMBEROFACTIVEUSERS' ),
	'numberofedits'         => array( '1', 'NUMERODEEDICOES', 'NÚMERODEEDIÇÕES', 'NUMBEROFEDITS' ),
	'numberofviews'         => array( '1', 'NUMERODEEXIBICOES', 'NÚMERODEEXIBIÇÕES', 'NUMBEROFVIEWS' ),
	'pagename'              => array( '1', 'NOMEDAPAGINA', 'NOMEDAPÁGINA', 'PAGENAME' ),
	'pagenamee'             => array( '1', 'NOMEDAPAGINAC', 'NOMEDAPÁGINAC', 'PAGENAMEE' ),
	'namespace'             => array( '1', 'DOMINIO', 'DOMÍNIO', 'ESPACONOMINAL', 'ESPAÇONOMINAL', 'NAMESPACE' ),
	'namespacee'            => array( '1', 'DOMINIOC', 'DOMÍNIOC', 'ESPACONOMINALC', 'ESPAÇONOMINALC', 'NAMESPACEE' ),
	'talkspace'             => array( '1', 'PAGINADEDISCUSSAO', 'PÁGINADEDISCUSSÃO', 'TALKSPACE' ),
	'talkspacee'            => array( '1', 'PAGINADEDISCUSSAOC', 'PÁGINADEDISCUSSÃOC', 'TALKSPACEE' ),
	'subjectspace'          => array( '1', 'PAGINADECONTEUDO', 'PAGINADECONTEÚDO', 'SUBJECTSPACE', 'ARTICLESPACE' ),
	'subjectspacee'         => array( '1', 'PAGINADECONTEUDOC', 'PAGINADECONTEÚDOC', 'SUBJECTSPACEE', 'ARTICLESPACEE' ),
	'fullpagename'          => array( '1', 'NOMECOMPLETODAPAGINA', 'NOMECOMPLETODAPÁGINA', 'FULLPAGENAME' ),
	'fullpagenamee'         => array( '1', 'NOMECOMPLETODAPAGINAC', 'NOMECOMPLETODAPÁGINAC', 'FULLPAGENAMEE' ),
	'subpagename'           => array( '1', 'NOMEDASUBPAGINA', 'NOMEDASUBPÁGINA', 'SUBPAGENAME' ),
	'subpagenamee'          => array( '1', 'NOMEDASUBPAGINAC', 'NOMEDASUBPÁGINAC', 'SUBPAGENAMEE' ),
	'basepagename'          => array( '1', 'NOMEDAPAGINABASE', 'NOMEDAPÁGINABASE', 'BASEPAGENAME' ),
	'basepagenamee'         => array( '1', 'NOMEDAPAGINABASEC', 'NOMEDAPÁGINABASEC', 'BASEPAGENAMEE' ),
	'talkpagename'          => array( '1', 'NOMEDAPAGINADEDISCUSSAO', 'NOMEDAPÁGINADEDISCUSSÃO', 'TALKPAGENAME' ),
	'talkpagenamee'         => array( '1', 'NOMEDAPAGINADEDISCUSSAOC', 'NOMEDAPÁGINADEDISCUSSÃOC', 'TALKPAGENAMEE' ),
	'subjectpagename'       => array( '1', 'NOMEDAPAGINADECONTEUDO', 'NOMEDAPÁGINADECONTEÚDO', 'SUBJECTPAGENAME', 'ARTICLEPAGENAME' ),
	'subjectpagenamee'      => array( '1', 'NOMEDAPAGINADECONTEUDOC', 'NOMEDAPÁGINADECONTEÚDOC', 'SUBJECTPAGENAMEE', 'ARTICLEPAGENAMEE' ),
	'img_right'             => array( '1', 'direita', 'right' ),
	'img_left'              => array( '1', 'esquerda', 'left' ),
	'img_none'              => array( '1', 'nenhum', 'none' ),
	'img_center'            => array( '1', 'centro', 'center', 'centre' ),
	'img_framed'            => array( '1', 'comborda', 'framed', 'enframed', 'frame' ),
	'img_frameless'         => array( '1', 'semborda', 'frameless' ),
	'img_page'              => array( '1', 'página=$1', 'página $1', 'page=$1', 'page $1' ),
	'img_upright'           => array( '1', 'superiordireito', 'superiordireito=$1', 'superiordireito $1', 'upright', 'upright=$1', 'upright $1' ),
	'img_border'            => array( '1', 'borda', 'border' ),
	'img_top'               => array( '1', 'acima', 'top' ),
	'img_middle'            => array( '1', 'meio', 'middle' ),
	'img_bottom'            => array( '1', 'abaixo', 'bottom' ),
	'img_link'              => array( '1', 'ligação=$1', 'link=$1' ),
	'sitename'              => array( '1', 'NOMEDOSITE', 'NOMEDOSÍTIO', 'NOMEDOSITIO', 'SITENAME' ),
	'server'                => array( '0', 'SERVIDOR', 'SERVER' ),
	'servername'            => array( '0', 'NOMEDOSERVIDOR', 'SERVERNAME' ),
	'scriptpath'            => array( '0', 'CAMINHODOSCRIPT', 'SCRIPTPATH' ),
	'gender'                => array( '0', 'GENERO', 'GÊNERO', 'GENDER:' ),
	'notitleconvert'        => array( '0', '__SEMCONVERTERTITULO__', '__SEMCONVERTERTÍTULO__', '__SEMCT__', '__NOTITLECONVERT__', '__NOTC__' ),
	'nocontentconvert'      => array( '0', '__SEMCONVERTERCONTEUDO__', '__SEMCONVERTERCONTEÚDO__', '__SEMCC__', '__NOCONTENTCONVERT__', '__NOCC__' ),
	'currentweek'           => array( '1', 'SEMANAATUAL', 'CURRENTWEEK' ),
	'currentdow'            => array( '1', 'DIADASEMANAATUAL', 'CURRENTDOW' ),
	'localweek'             => array( '1', 'SEMANALOCAL', 'LOCALWEEK' ),
	'localdow'              => array( '1', 'DIADASEMANALOCAL', 'LOCALDOW' ),
	'revisionid'            => array( '1', 'IDDAREVISAO', 'IDDAREVISÃO', 'REVISIONID' ),
	'revisionday'           => array( '1', 'DIADAREVISAO', 'DIADAREVISÃO', 'REVISIONDAY' ),
	'revisionday2'          => array( '1', 'DIADAREVISAO2', 'DIADAREVISÃO2', 'REVISIONDAY2' ),
	'revisionmonth'         => array( '1', 'MESDAREVISAO', 'MÊSDAREVISÃO', 'REVISIONMONTH' ),
	'revisionyear'          => array( '1', 'ANODAREVISAO', 'ANODAREVISÃO', 'REVISIONYEAR' ),
	'revisionuser'          => array( '1', 'USUARIODAREVISAO', 'USUÁRIODAREVISÃO', 'REVISIONUSER' ),
	'fullurl'               => array( '0', 'URLCOMPLETO:', 'FULLURL:' ),
	'fullurle'              => array( '0', 'URLCOMPLETOC:', 'FULLURLE:' ),
	'lcfirst'               => array( '0', 'PRIMEIRAMINUSCULA:', 'PRIMEIRAMINÚSCULA:', 'LCFIRST:' ),
	'ucfirst'               => array( '0', 'PRIMEIRAMAIUSCULA:', 'PRIMEIRAMAIÚSCULA:', 'UCFIRST:' ),
	'lc'                    => array( '0', 'MINUSCULA', 'MINÚSCULA', 'MINUSCULAS', 'MINÚSCULAS', 'LC:' ),
	'uc'                    => array( '0', 'MAIUSCULA', 'MAIÚSCULA', 'MAIUSCULAS', 'MAIÚSCULAS', 'UC:' ),
	'displaytitle'          => array( '1', 'EXIBETITULO', 'EXIBETÍTULO', 'DISPLAYTITLE' ),
	'newsectionlink'        => array( '1', '__LINKDENOVASECAO__', '__LINKDENOVASEÇÃO__', '__LIGACAODENOVASECAO__', '__LIGAÇÃODENOVASEÇÃO__', '__NEWSECTIONLINK__' ),
	'nonewsectionlink'      => array( '1', '__SEMLINKDENOVASECAO__', '__SEMLINKDENOVASEÇÃO__', '__SEMLIGACAODENOVASECAO__', '__SEMLIGAÇÃODENOVASEÇÃO__', '__NONEWSECTIONLINK__' ),
	'currentversion'        => array( '1', 'REVISAOATUAL', 'REVISÃOATUAL', 'CURRENTVERSION' ),
	'urlencode'             => array( '0', 'CODIFICAURL:', 'URLENCODE:' ),
	'anchorencode'          => array( '0', 'CODIFICAANCORA:', 'CODIFICAÂNCORA:', 'ANCHORENCODE' ),
	'language'              => array( '0', '#IDIOMA:', '#LANGUAGE:' ),
	'contentlanguage'       => array( '1', 'IDIOMADOCONTEUDO', 'IDIOMADOCONTEÚDO', 'CONTENTLANGUAGE', 'CONTENTLANG' ),
	'pagesinnamespace'      => array( '1', 'PAGINASNOESPACONOMINAL', 'PÁGINASNOESPAÇONOMINAL', 'PAGINASNODOMINIO', 'PÁGINASNODOMÍNIO', 'PAGESINNAMESPACE:', 'PAGESINNS:' ),
	'numberofadmins'        => array( '1', 'NUMERODEADMINISTRADORES', 'NÚMERODEADMINISTRADORES', 'NUMBEROFADMINS' ),
	'defaultsort'           => array( '1', 'ORDENACAOPADRAO', 'ORDENAÇÃOPADRÃO', 'ORDEMPADRAO', 'ORDEMPADRÃO', 'DEFAULTSORT:', 'DEFAULTSORTKEY:', 'DEFAULTCATEGORYSORT:' ),
	'filepath'              => array( '0', 'CAMINHODOARQUIVO', 'FILEPATH:' ),
	'hiddencat'             => array( '1', '__CATEGORIAOCULTA__', '__CATOCULTA__', '__HIDDENCAT__' ),
	'pagesincategory'       => array( '1', 'PAGINASNACATEGORIA', 'PÁGINASNACATEGORIA', 'PAGINASNACAT', 'PÁGINASNACAT', 'PAGESINCATEGORY', 'PAGESINCAT' ),
	'pagesize'              => array( '1', 'TAMANHODAPAGINA', 'TAMANHODAPÁGINA', 'PAGESIZE' ),
	'index'                 => array( '1', '__INDEXAR__', '__INDEX__' ),
	'noindex'               => array( '1', '__NAOINDEXAR__', '__NÃOINDEXAR__', '__NOINDEX__' ),
	'numberingroup'         => array( '1', 'NUMERONOGRUPO', 'NÚMERONOGRUPO', 'NUMBERINGROUP', 'NUMINGROUP' ),
	'staticredirect'        => array( '1', '__REDIRECIONAMENTOESTATICO__', '__REDIRECIONAMENTOESTÁTICO__', '__STATICREDIRECT__' ),
	'protectionlevel'       => array( '1', 'NIVELDEPROTECAO', 'NÍVELDEPROTEÇÃO', 'PROTECTIONLEVEL' ),
);

$messages = array(
# User preference toggles
'tog-underline'               => 'Sublinhar hiperligações:',
'tog-highlightbroken'         => 'Formatar links quebrados <a href="" class="new">assim</a> (alternativa: assim<a href="" class="internal">?</a>).',
'tog-justify'                 => 'Justificar parágrafos',
'tog-hideminor'               => 'Esconder edições secundárias nas mudanças recentes',
'tog-hidepatrolled'           => 'Esconder edições patrulhadas nas mudanças recentes',
'tog-newpageshidepatrolled'   => 'Ocultar páginas patrulhadas da lista de páginas novas',
'tog-extendwatchlist'         => 'Expandir a lista de vigiados para mostrar todas as alterações, e não apenas as mais recentes',
'tog-usenewrc'                => 'Usar a versão melhorada das mudanças recentes (requer JavaScript)',
'tog-numberheadings'          => 'Auto-numerar cabeçalhos',
'tog-showtoolbar'             => 'Mostrar barra de edição (JavaScript)',
'tog-editondblclick'          => 'Editar páginas quando houver clique duplo (JavaScript)',
'tog-editsection'             => 'Habilitar edição de secção via links [editar]',
'tog-editsectiononrightclick' => 'Habilitar edição de secção por clique com o botão direito no título da secção (JavaScript)',
'tog-showtoc'                 => 'Mostrar Tabela de Conteúdos (para páginas com mais de três cabeçalhos)',
'tog-rememberpassword'        => 'Lembrar palavra-chave entre sessões',
'tog-editwidth'               => 'Aumentar a largura da caixa de edição para preencher todo o ecrã',
'tog-watchcreations'          => 'Adicionar páginas criadas por mim à minha lista de vigiados',
'tog-watchdefault'            => 'Adicionar páginas editadas por mim à minha lista de vigiados',
'tog-watchmoves'              => 'Adicionar páginas movidas por mim à minha lista de vigiados',
'tog-watchdeletion'           => 'Adicionar páginas eliminadas por mim à minha lista de vigiados',
'tog-minordefault'            => 'Marcar todas as edições como secundárias, por defeito',
'tog-previewontop'            => 'Mostrar previsão antes da caixa de edição',
'tog-previewonfirst'          => 'Mostrar previsão na primeira edição',
'tog-nocache'                 => 'Desactivar caching de páginas',
'tog-enotifwatchlistpages'    => 'Enviar-me um email quando uma página da minha lista de vigiados for alterada',
'tog-enotifusertalkpages'     => 'Enviar-me um email quando a minha página de discussão for editada',
'tog-enotifminoredits'        => 'Enviar-me um email também quando forem edições menores',
'tog-enotifrevealaddr'        => 'Revelar o meu endereço de email nas notificações',
'tog-shownumberswatching'     => 'Mostrar o número de utilizadores a vigiar',
'tog-oldsig'                  => 'Previsualização da assinatura atual:',
'tog-fancysig'                => 'Tratar assinatura como wikitexto (sem ligação automática)',
'tog-externaleditor'          => 'Utilizar editor externo por defeito (apenas para usuários avançados, já que serão necessárias configurações adicionais em seus computadores)',
'tog-externaldiff'            => 'Utilizar diferenças externas por defeito (apenas para usuários avançados, já que serão necessárias configurações adicionais em seus computadores)',
'tog-showjumplinks'           => 'Activar hiperligações de acessibilidade "ir para"',
'tog-uselivepreview'          => 'Utilizar pré-visualização em tempo real (JavaScript) (Experimental)',
'tog-forceeditsummary'        => 'Avisar-me ao introduzir um sumário vazio',
'tog-watchlisthideown'        => 'Esconder as minhas edições da lista de vigiados',
'tog-watchlisthidebots'       => 'Esconder edições efectuadas por robôs da lista de vigiados',
'tog-watchlisthideminor'      => 'Esconder edições menores da lista de vigiados',
'tog-watchlisthideliu'        => 'Ocultar edições de utilizadores autenticados da lista de vigiados',
'tog-watchlisthideanons'      => 'Ocultar edições de utilizadores anónimos da lista de vigiados',
'tog-watchlisthidepatrolled'  => 'Esconder edições patrulhadas na lista de artigos vigiados',
'tog-nolangconversion'        => 'Desactivar conversão de variantes de idioma',
'tog-ccmeonemails'            => 'Enviar para mim cópias de e-mails que eu enviar a outros utilizadores',
'tog-diffonly'                => 'Não mostrar o conteúdo da página ao comparar duas edições',
'tog-showhiddencats'          => 'Exibir categorias ocultas',
'tog-noconvertlink'           => 'Desactivar conversão de títulos de ligações',
'tog-norollbackdiff'          => 'Omitir diferenças depois de desfazer edições em bloco',

'underline-always'  => 'Sempre',
'underline-never'   => 'Nunca',
'underline-default' => 'Padrão do navegador',

# Font style option in Special:Preferences
'editfont-style'     => 'Editar estilo da área da fonte:',
'editfont-default'   => 'Navegador padrão',
'editfont-monospace' => 'Fonte monoespaçada',
'editfont-sansserif' => 'Fonte sans-serif',
'editfont-serif'     => 'Fonte serifada',

# Dates
'sunday'        => 'domingo',
'monday'        => 'segunda-feira',
'tuesday'       => 'terça-feira',
'wednesday'     => 'quarta-feira',
'thursday'      => 'quinta-feira',
'friday'        => 'sexta-feira',
'saturday'      => 'sábado',
'sun'           => 'dom',
'mon'           => 'seg',
'tue'           => 'ter',
'wed'           => 'qua',
'thu'           => 'qui',
'fri'           => 'sex',
'sat'           => 'sáb',
'january'       => 'janeiro',
'february'      => 'fevereiro',
'march'         => 'março',
'april'         => 'abril',
'may_long'      => 'maio',
'june'          => 'junho',
'july'          => 'julho',
'august'        => 'agosto',
'september'     => 'setembro',
'october'       => 'outubro',
'november'      => 'novembro',
'december'      => 'dezembro',
'january-gen'   => 'janeiro',
'february-gen'  => 'fevereiro',
'march-gen'     => 'março',
'april-gen'     => 'abril',
'may-gen'       => 'maio',
'june-gen'      => 'junho',
'july-gen'      => 'julho',
'august-gen'    => 'agosto',
'september-gen' => 'setembro',
'october-gen'   => 'outubro',
'november-gen'  => 'novembro',
'december-gen'  => 'dezembro',
'jan'           => 'jan.',
'feb'           => 'fev.',
'mar'           => 'mar.',
'apr'           => 'abr.',
'may'           => 'maio',
'jun'           => 'jun.',
'jul'           => 'jul.',
'aug'           => 'ago.',
'sep'           => 'set.',
'oct'           => 'out.',
'nov'           => 'nov.',
'dec'           => 'dez.',

# Categories related messages
'pagecategories'                 => '{{PLURAL:$1|Categoria|Categorias}}',
'category_header'                => 'Páginas na categoria "$1"',
'subcategories'                  => 'Subcategorias',
'category-media-header'          => 'Multimédia na categoria "$1"',
'category-empty'                 => "''Esta categoria de momento não possui nenhuma página ou ficheiro multimédia.''",
'hidden-categories'              => '{{PLURAL:$1|Categoria oculta|Categorias ocultas}}',
'hidden-category-category'       => 'Categorias ocultas',
'category-subcat-count'          => '{{PLURAL:$2|Esta categoria possui apenas a subcategoria a seguir.|Há, nesta categoria, {{PLURAL:$1|uma subcategoria|$1 subcategorias}} (dentre um total de $2).}}',
'category-subcat-count-limited'  => 'Esta categoria possui {{PLURAL:$1|a seguinte subcategoria|as $1 subcategorias a seguir}}.',
'category-article-count'         => '{{PLURAL:$2|Esta categoria possui apenas a página a seguir.|Há, nesta categoria, {{PLURAL:$1|a página a seguir|as $1 páginas a seguir}} (dentre um total de $2).}}',
'category-article-count-limited' => 'Há, nesta categoria, {{PLURAL:$1|a página a seguir|as $1 páginas a seguir}}.',
'category-file-count'            => '{{PLURAL:$2|Esta categoria possui apenas o ficheiro a seguir.|Há, nesta categoria, {{PLURAL:$1|o ficheiro a seguir|os $1 ficheiros a seguir}} (dentre um total de $2).}}',
'category-file-count-limited'    => 'Nesta categoria há {{PLURAL:$1|um ficheiro|$1 ficheiros}}.',
'listingcontinuesabbrev'         => 'cont.',
'index-category'                 => 'Páginas indexadas',
'noindex-category'               => 'Páginas não indexadas',

'mainpagetext'      => "<big>'''MediaWiki instalado com sucesso.'''</big>",
'mainpagedocfooter' => 'Consulte o [http://meta.wikimedia.org/wiki/Help:Contents Guia de Utilizadores] para informações acerca de como utilizar o software wiki.

== Começando ==

* [http://www.mediawiki.org/wiki/Manual:Configuration_settings Lista de opções de configuração]
* [http://www.mediawiki.org/wiki/Manual:FAQ MediaWiki Perguntas e respostas frequentes]
* [https://lists.wikimedia.org/mailman/listinfo/mediawiki-announce Lista de correio de anúncios de novas versões do MediaWiki]',

'about'         => 'Sobre',
'article'       => 'Página de conteúdo',
'newwindow'     => '(abre numa nova janela)',
'cancel'        => 'Cancelar',
'moredotdotdot' => 'Mais...',
'mypage'        => 'Minha página',
'mytalk'        => 'Minha discussão',
'anontalk'      => 'Discussão para este IP',
'navigation'    => 'Navegação',
'and'           => '&#32;e',

# Cologne Blue skin
'qbfind'         => 'Procurar',
'qbbrowse'       => 'Navegar',
'qbedit'         => 'Editar',
'qbpageoptions'  => 'Esta página',
'qbpageinfo'     => 'Contexto',
'qbmyoptions'    => 'Minhas páginas',
'qbspecialpages' => 'Páginas especiais',
'faq'            => 'FAQ',
'faqpage'        => 'Project:FAQ',

# Vector skin
'vector-action-addsection'   => 'Adicionar&nbsp;tópico',
'vector-action-delete'       => 'Eliminar',
'vector-action-move'         => 'Mover',
'vector-action-protect'      => 'Proteger',
'vector-action-undelete'     => 'Recuperar',
'vector-action-unprotect'    => 'Desproteger',
'vector-namespace-category'  => 'Categoria',
'vector-namespace-help'      => 'Página de ajuda',
'vector-namespace-image'     => 'Ficheiro',
'vector-namespace-main'      => 'Página',
'vector-namespace-media'     => 'Página de multimédia',
'vector-namespace-mediawiki' => 'Mensagem',
'vector-namespace-project'   => 'Página de projeto',
'vector-namespace-special'   => 'Página especial',
'vector-namespace-talk'      => 'Discussão',
'vector-namespace-template'  => 'Predefinição',
'vector-namespace-user'      => 'Página de utilizador',
'vector-view-create'         => 'Criar',
'vector-view-edit'           => 'Editar',
'vector-view-history'        => 'Ver histórico',
'vector-view-view'           => 'Ler',
'vector-view-viewsource'     => 'Ver fonte',
'actions'                    => 'Ações',
'namespaces'                 => 'Espaços nominais',
'variants'                   => 'Variantes',

# Metadata in edit box
'metadata_help' => 'Metadados:',

'errorpagetitle'    => 'Erro',
'returnto'          => 'Voltar para $1.',
'tagline'           => 'De {{SITENAME}}',
'help'              => 'Ajuda',
'search'            => 'Pesquisa',
'searchbutton'      => 'Pesquisa',
'go'                => 'Ir',
'searcharticle'     => 'Ir',
'history'           => 'Histórico',
'history_short'     => 'Histórico',
'updatedmarker'     => 'actualizado desde a minha última visita',
'info_short'        => 'Informação',
'printableversion'  => 'Versão para impressão',
'permalink'         => 'Ligação permanente',
'print'             => 'Imprimir',
'edit'              => 'Editar',
'create'            => 'Criar',
'editthispage'      => 'Editar esta página',
'create-this-page'  => 'Criar/iniciar esta página',
'delete'            => 'Eliminar',
'deletethispage'    => 'Eliminar esta página',
'undelete_short'    => 'Restaurar {{PLURAL:$1|uma edição|$1 edições}}',
'protect'           => 'Proteger',
'protect_change'    => 'alterar',
'protectthispage'   => 'Proteger esta página',
'unprotect'         => 'Desproteger',
'unprotectthispage' => 'Desproteger esta página',
'newpage'           => 'Nova página',
'talkpage'          => 'Discutir esta página',
'talkpagelinktext'  => 'disc',
'specialpage'       => 'Página especial',
'personaltools'     => 'Ferramentas pessoais',
'postcomment'       => 'Nova secção',
'articlepage'       => 'Ver página de conteúdo',
'talk'              => 'Discussão',
'views'             => 'Acessos',
'toolbox'           => 'Ferramentas',
'userpage'          => 'Ver página de utilizador',
'projectpage'       => 'Ver página de projecto',
'imagepage'         => 'Ver página de ficheiro',
'mediawikipage'     => 'Ver página de mensagens',
'templatepage'      => 'Ver página de predefinições',
'viewhelppage'      => 'Ver página de ajuda',
'categorypage'      => 'Ver página de categorias',
'viewtalkpage'      => 'Ver discussão',
'otherlanguages'    => 'Outras línguas',
'redirectedfrom'    => '(Redireccionado de <b>$1</b>)',
'redirectpagesub'   => 'Página de redireccionamento',
'lastmodifiedat'    => 'Esta página foi modificada pela última vez às $2 de $1.',
'viewcount'         => 'Esta página foi acedida {{PLURAL:$1|uma vez|$1 vezes}}.',
'protectedpage'     => 'Página protegida',
'jumpto'            => 'Ir para:',
'jumptonavigation'  => 'navegação',
'jumptosearch'      => 'pesquisa',
'view-pool-error'   => 'Desculpe-nos, os servidores estão sobrecarregados no momento.
Muitos utilizadores estão tentando visualizar essa página.
Por favor espere um pouco antes de tentar acessar a página novamente.

$1',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'            => 'Sobre {{SITENAME}}',
'aboutpage'            => 'Project:Sobre',
'copyright'            => 'Conteúdo disponível sob $1.',
'copyrightpage'        => '{{ns:project}}:Direitos_de_autor',
'currentevents'        => 'Eventos actuais',
'currentevents-url'    => 'Project:Eventos actuais',
'disclaimers'          => 'Alerta de Conteúdo',
'disclaimerpage'       => 'Project:Aviso_geral',
'edithelp'             => 'Ajuda de edição',
'edithelppage'         => 'Help:Editar',
'helppage'             => 'Help:Conteúdos',
'mainpage'             => 'Página principal',
'mainpage-description' => 'Página principal',
'policy-url'           => 'Project:Políticas',
'portal'               => 'Portal comunitário',
'portal-url'           => 'Project:Portal comunitário',
'privacy'              => 'Política de privacidade',
'privacypage'          => 'Project:Política_de_privacidade',

'badaccess'        => 'Erro de permissão',
'badaccess-group0' => 'Você não está autorizado a executar a acção requisitada.',
'badaccess-groups' => 'A acção que você requisitou está limitada a utilizadores {{PLURAL:$2|do grupo|de um dos seguintes grupos}}: $1.',

'versionrequired'     => 'É necessária a versão $1 do MediaWiki',
'versionrequiredtext' => 'Esta página requer a versão $1 do MediaWiki para poder ser utilizada. Consulte [[Special:Version|a página sobre a versão do sistema]]',

'ok'                      => 'OK',
'retrievedfrom'           => 'Obtido em "$1"',
'youhavenewmessages'      => 'Você tem $1 ($2).',
'newmessageslink'         => 'novas mensagens',
'newmessagesdifflink'     => 'comparar com a penúltima revisão',
'youhavenewmessagesmulti' => 'Tem novas mensagens em $1',
'editsection'             => 'editar',
'editold'                 => 'editar',
'viewsourceold'           => 'ver código',
'editlink'                => 'editar',
'viewsourcelink'          => 'ver fonte',
'editsectionhint'         => 'Editar secção: $1',
'toc'                     => 'Tabela de conteúdo',
'showtoc'                 => 'mostrar',
'hidetoc'                 => 'esconder',
'thisisdeleted'           => 'Ver ou restaurar $1?',
'viewdeleted'             => 'Ver $1?',
'restorelink'             => '{{PLURAL:$1|uma edição eliminada|$1 edições eliminadas}}',
'feedlinks'               => 'Feed:',
'feed-invalid'            => 'Tipo de subscrição feed inválido.',
'feed-unavailable'        => 'Os "feeds" não se encontram disponíveis',
'site-rss-feed'           => 'Feed RSS $1',
'site-atom-feed'          => 'Feed Atom $1',
'page-rss-feed'           => 'Feed RSS de "$1"',
'page-atom-feed'          => 'Feed Atom de "$1"',
'red-link-title'          => '$1 (página não existe)',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'Página',
'nstab-user'      => 'Página de utilizador',
'nstab-media'     => 'Multimédia',
'nstab-special'   => 'Página especial',
'nstab-project'   => 'Página de projecto',
'nstab-image'     => 'Ficheiro',
'nstab-mediawiki' => 'Mensagem',
'nstab-template'  => 'Predefinição',
'nstab-help'      => 'Ajuda',
'nstab-category'  => 'Categoria',

# Main script and global functions
'nosuchaction'      => 'Acção não existente',
'nosuchactiontext'  => 'A ação especificada pela URL é inválida.
Você poderá ter introduzido mal a URL, ou ter seguido uma ligação incorreta.
Isto poderá também ser indicador de um defeito em {{SITENAME}}.',
'nosuchspecialpage' => 'Não existe a página especial requisitada',
'nospecialpagetext' => '<strong>Você requisitou uma página especial inválida.</strong>

Uma lista de páginas especiais válidas poderá ser encontrada em [[Special:SpecialPages|{{int:specialpages}}]].',

# General errors
'error'                => 'Erro',
'databaseerror'        => 'Erro na base de dados',
'dberrortext'          => 'Ocorreu um erro de sintaxe na pesquisa à base de dados.
Isto pode indicar um bug no software.
A última tentativa de busca na base de dados foi:
<blockquote><tt>$1</tt></blockquote>
na função "<tt>$2</tt>".
base de dados retornou o erro "<tt>$3: $4</tt>".',
'dberrortextcl'        => 'Ocorreu um erro de sintaxe na pesquisa à base de dados.
A última tentativa de busca na base de dados foi:
"$1"
na função "$2".
Base de dados retornou o erro "$3: $4"',
'laggedslavemode'      => 'Aviso: A página poderá não conter actualizações recentes.',
'readonly'             => 'Base de dados no modo "somente leitura"',
'enterlockreason'      => 'Introduza um motivo para trancar, incluindo uma estimativa de quando poderá ser destrancada',
'readonlytext'         => 'A base de dados está actualmente trancada para novas entradas e outras modificações, provavelmente por uma manutenção de rotina; a situação deverá ser normalizada dentro de algum tempo.

Quem fez o bloqueio oferece a seguinte explicação: $1',
'missing-article'      => 'A base de dados não encontrou o texto de uma página que deveria ter encontrado, com o nome "$1" $2.

Isto geralmente é causado pelo seguimento de uma ligação de diferença desactualizada ou de história de uma página que foi removida.

Se não for este o caso, você pode ter encontrado um defeito no software.
Por favor, reporte este facto a um [[Special:ListUsers/sysop|administrador]], tomando nota da URL.',
'missingarticle-rev'   => '(revisão#: $1)',
'missingarticle-diff'  => '(Dif.: $1, $2)',
'readonly_lag'         => 'A base de dados foi automaticamente bloqueada enquanto os servidores secundários se sincronizam com o principal',
'internalerror'        => 'Erro interno',
'internalerror_info'   => 'Erro interno: $1',
'fileappenderror'      => 'Não foi possível adicionar "$1" a "$2".',
'filecopyerror'        => 'Não foi possível copiar o ficheiro "$1" para "$2".',
'filerenameerror'      => 'Não foi possível renomear o ficheiro "$1" para "$2".',
'filedeleteerror'      => 'Não foi possível eliminar o ficheiro "$1".',
'directorycreateerror' => 'Não foi possível criar o diretório "$1".',
'filenotfound'         => 'Não foi possível encontrar o ficheiro "$1".',
'fileexistserror'      => 'Não foi possível gravar no ficheiro "$1": ele já existe',
'unexpected'           => 'Valor não esperado: "$1"="$2".',
'formerror'            => 'Erro: Não foi possível enviar o formulário',
'badarticleerror'      => 'Esta acção não pode ser realizada nesta página.',
'cannotdelete'         => 'Não foi possível eliminar a página ou ficheiro "$1".
A sua eliminação pode ter sido já feita por outro utilizador.',
'badtitle'             => 'Título inválido',
'badtitletext'         => 'O título de página requisitado é inválido, vazio, ou uma ligação incorrecta de inter-linguagem ou título inter-wiki. Pode ser que ele contenha um ou mais caracteres que não podem ser utilizados em títulos.',
'perfcached'           => 'Os dados seguintes encontram-se na cache e podem não estar actualizados.',
'perfcachedts'         => 'Os seguintes dados encontram-se armazenados na cache e foram actualizados pela última vez a $1.',
'querypage-no-updates' => 'Momentaneamente as atualizações para esta página estão desativadas. Por enquanto, os dados aqui presentes não poderão ser atualizados.',
'wrong_wfQuery_params' => 'Parâmetros incorrectos para wfQuery()<br />
Function: $1<br />
Query: $2',
'viewsource'           => 'Ver código',
'viewsourcefor'        => 'para $1',
'actionthrottled'      => 'Acção limitada',
'actionthrottledtext'  => 'Como medida "anti-spam", está impedido de realizar esta operação demasiadas vezes num curto espaço de tempo, e já excedeu esse limite. Por favor, tente de novo dentro de alguns minutos.',
'protectedpagetext'    => 'Esta página foi protegida contra novas edições.',
'viewsourcetext'       => 'Você pode ver e copiar o código desta página:',
'protectedinterface'   => 'Esta página fornece texto de interface ao software e encontra-se trancada para prevenir abusos.',
'editinginterface'     => "'''Aviso:''' Encontra-se a editar uma página que é utilizada para fornecer texto de interface ao software. Alterações nesta página irão afectar a aparência da interface de utilizador para outros utilizadores. Para traduções, considere utilizar a [http://translatewiki.net/wiki/Main_Page?setlang=pt translatewiki.net], um projeto destinado à tradução do MediaWiki.",
'sqlhidden'            => '(Consulta SQL em segundo-plano)',
'cascadeprotected'     => 'Esta página foi protegida contra edições por estar incluída {{PLURAL:$1|na página listada|nas páginas listadas}} a seguir, ({{PLURAL:$1|página essa que está protegida|páginas essas que estão protegidas}} com a opção de "proteção progressiva" ativada):
$2',
'namespaceprotected'   => "Você não possui permissão para editar páginas no espaço nominal '''$1'''.",
'customcssjsprotected' => 'Você não possui permissão de editar esta página, já que ela contém configurações pessoais de outro utilizador.',
'ns-specialprotected'  => 'Não é possível editar páginas especiais',
'titleprotected'       => "Este título foi protegido, para que não seja criado.
Quem o protegeu foi [[User:$1|$1]], com a justificativa: ''$2''.",

# Virus scanner
'virus-badscanner'     => "Má configuração: antivírus desconhecido: ''$1''",
'virus-scanfailed'     => 'a verificação falhou (código $1)',
'virus-unknownscanner' => 'antivírus desconhecido:',

# Login and logout pages
'logouttext'                 => "'''Você agora está desautenticado.'''

Pode continuar a utilizar a {{SITENAME}} anonimamente, ou pode [[Special:UserLogin|autenticar-se novamente]] com o mesmo nome de utilizador ou com um nome de utilizador diferente.
Tenha em atenção que algumas páginas poderão continuar a ser apresentadas como se você ainda estivesse autenticado até que a cache de seu navegador seja limpa.",
'welcomecreation'            => '== Bem-vindo, $1! ==
A sua conta foi criada.
Não se esqueça de personalizar as suas [[Special:Preferences|preferências na {{SITENAME}}]].',
'yourname'                   => 'Nome de utilizador:',
'yourpassword'               => 'Palavra-chave:',
'yourpasswordagain'          => 'Repita a sua palavra-chave',
'remembermypassword'         => 'Lembrar a minha palavra-chave entre sessões.',
'yourdomainname'             => 'Seu domínio',
'externaldberror'            => 'Ocorreu um erro externo à base de dados durante a autenticação ou não lhe é permitido actualizar a sua conta externa.',
'login'                      => 'Autenticar-se',
'nav-login-createaccount'    => 'Entrar / criar conta',
'loginprompt'                => 'Você necessita de ter os <i>cookies</i> ligados para poder autenticar-se na {{SITENAME}}.',
'userlogin'                  => 'Criar uma conta ou entrar',
'logout'                     => 'Sair',
'userlogout'                 => 'Sair',
'notloggedin'                => 'Não autenticado',
'nologin'                    => "Não possui uma conta? '''$1'''.",
'nologinlink'                => 'Criar uma conta',
'createaccount'              => 'Criar nova conta',
'gotaccount'                 => "Já possui uma conta? '''$1'''.",
'gotaccountlink'             => 'Autentique-se',
'createaccountmail'          => 'por email',
'badretype'                  => 'As palavras-chaves que introduziu não são iguais.',
'userexists'                 => 'O nome de utilizador que introduziu já existe.
Escolha um nome diferente.',
'loginerror'                 => 'Erro de autenticação',
'createaccounterror'         => 'Não foi possível criar a conta: $1',
'nocookiesnew'               => 'A conta de utilizador foi criada, mas você não foi autenticado. {{SITENAME}} utiliza <i>cookies</i> para ligar os utilizadores às suas contas. Por favor, os active, depois autentique-se com o seu nome de utilizador e a sua palavra-chave.',
'nocookieslogin'             => 'Você tem os <i>cookies</i> desactivados no seu navegador, e a {{SITENAME}} utiliza <i>cookies</i> para autenticar os utilizadores. Por favor active-os e tente novamente.',
'noname'                     => 'Você não colocou um nome de utilizador válido.',
'loginsuccesstitle'          => 'Login bem sucedido',
'loginsuccess'               => "'''Encontra-se agora ligado à {{SITENAME}} como \"\$1\"'''.",
'nosuchuser'                 => 'Não existe nenhum utilizador com o nome "$1".
Os nomes de utilizador são sensíveis à capitalização.
Verifique a ortografia, ou [[Special:UserLogin/signup|crie uma nova conta]].',
'nosuchusershort'            => 'Não existe um utilizador com o nome "<nowiki>$1</nowiki>". Verifique o nome que introduziu.',
'nouserspecified'            => 'Precisa de especificar um nome de utilizador.',
'wrongpassword'              => 'A palavra-chave que introduziu é inválida. Por favor, tente novamente.',
'wrongpasswordempty'         => 'A palavra-chave introduzida está em branco. Por favor, tente novamente.',
'passwordtooshort'           => 'A sua palavra-chave deve de ter no mínimo {{PLURAL:$1|1 caráter|$1 carateres}}.',
'password-name-match'        => 'A sua palavra-passe deverá ser diferente do seu nome de utilizador.',
'mailmypassword'             => 'Enviar uma nova palavra-chave por e-mail',
'passwordremindertitle'      => 'Nova palavra-chave temporária em {{SITENAME}}',
'passwordremindertext'       => 'Alguém (provavelmente você, a partir do endereço de IP $1) solicitou que fosse lhe enviada uma nova palavra-chave para {{SITENAME}} ($4).
Foi criada uma palavra-chave temporária para o utilizador "$2", e foi reposta como "$3". Caso esta tenha sido a sua intenção, entre na sua conta e escolha uma nova palavra-chave agora.
A sua palavra-chave temporária expirará em {{PLURAL:$5|um dia|$5 dias}}.

Caso tenha sido outra pessoa a fazer este pedido, ou caso você já se tenha lembrado da sua palavra-chave e não deseja alterá-la, ignore esta mensagem e continue a utilizar a palavra-chave antiga.',
'noemail'                    => 'Não há um endereço de correio electrónico associado ao utilizador "$1".',
'noemailcreate'              => 'Você precisa fornecer um endereço de e-mail válido',
'passwordsent'               => 'Uma nova palavra-chave encontra-se a ser enviada para o endereço de correio electrónico associado ao utilizador "$1".
Por favor, volte a efectuar a autenticação ao recebê-la.',
'blocked-mailpassword'       => 'O seu endereço de IP foi bloqueado de editar e, portanto, não será possível utilizar o lembrete de palavra-chave (para serem evitados envios abusivos a outras pessoas).',
'eauthentsent'               => 'Um email de confirmação foi enviado para o endereço de correio electrónico nomeado.
Antes de qualquer outro email seja enviado para a conta, terá seguir as instruções no email,
de modo a confirmar que a conta é mesmo sua.',
'throttled-mailpassword'     => 'Um lembrete de palavra-chave já foi enviado {{PLURAL:$1|na última hora|nas últimas $1 horas}}.
Para prevenir abusos, apenas um lembrete poderá ser enviado a cada {{PLURAL:$1|hora|$1 horas}}.',
'mailerror'                  => 'Erro a enviar o email: $1',
'acct_creation_throttle_hit' => 'Visitantes desta wiki utilizando o seu endereço IP criaram $1 {{PLURAL:$1|conta|contas}} no último dia, o que é o máximo permitido neste período de tempo.
Em resultado, visitantes que usam este endereço IP não podem criar mais nenhuma conta de momento.',
'emailauthenticated'         => 'O seu endereço de e-mail foi autenticado às $3 de $2.',
'emailnotauthenticated'      => 'O seu endereço de correio electrónico ainda não foi autenticado. Não lhe será enviado nenhum correio sobre nenhuma das seguintes funcionalidades.',
'noemailprefs'               => 'Especifique um endereço de e-mail nas suas preferências para activar estas funcionalidades.',
'emailconfirmlink'           => 'Confirme o seu endereço de correio electrónico',
'invalidemailaddress'        => 'O endereço de e-mail não pode ser aceite devido a talvez possuir um formato inválido.
Introduza um endereço correctamente formatado ou esvazie o campo.',
'accountcreated'             => 'Conta criada',
'accountcreatedtext'         => 'A conta de utilizador para $1 foi criada.',
'createaccount-title'        => 'Criação de conta em {{SITENAME}}',
'createaccount-text'         => 'Alguém criou uma conta de nome $2 para o seu endereço de email no wiki {{SITENAME}} ($4), tendo como palavra-chave #$3". Você deve se autenticar e alterar sua palavra-chave.

Você pode ignorar esta mensagem caso a conta tenha sido criada por engano.',
'usernamehasherror'          => 'O nome de utilizador não pode conter o símbolo de cardinal (#).',
'login-throttled'            => 'Você fez muitas tentativas recentes de se autenticar com esta conta.
Por favor, aguarde antes de tentar novamente.',
'loginlanguagelabel'         => 'Idioma: $1',

# Password reset dialog
'resetpass'                 => 'Alterar palavra-chave',
'resetpass_announce'        => 'Você foi autenticado através de uma palavra-chave temporária. Para prosseguir, será necessário definir uma nova palavra-chave.',
'resetpass_text'            => '<!-- Adicionar texto aqui -->',
'resetpass_header'          => 'Alterar palavra-chave da conta',
'oldpassword'               => 'Palavra-chave antiga',
'newpassword'               => 'Nova palavra-chave',
'retypenew'                 => 'Reintroduza a nova palavra-chave',
'resetpass_submit'          => 'Definir palavra-chave e entrar',
'resetpass_success'         => 'Sua palavra-chave foi alterada com sucesso! Autenticando-se...',
'resetpass_forbidden'       => 'Não é possível alterar palavras-chave',
'resetpass-no-info'         => 'Você precisa estar autenticado para aceder a esta página directamente.',
'resetpass-submit-loggedin' => 'Alterar palavra-chave',
'resetpass-wrong-oldpass'   => 'Palavra-chave temporária ou actual inválida. 
Você pode já ter alterado com sucesso a sua palavra-chave, ou solicitado uma nova palavra-chave temporária.',
'resetpass-temp-password'   => 'Palavra-chave temporária:',

# Edit page toolbar
'bold_sample'     => 'Texto a negrito',
'bold_tip'        => 'Texto a negrito',
'italic_sample'   => 'Texto em itálico',
'italic_tip'      => 'Texto em itálico',
'link_sample'     => 'Título da ligação',
'link_tip'        => 'Ligação interna',
'extlink_sample'  => 'http://www.example.com ligação externa',
'extlink_tip'     => 'Ligação externa (lembre-se do prefixo http://)',
'headline_sample' => 'Texto do cabeçalho',
'headline_tip'    => 'Secção de nível 2',
'math_sample'     => 'Inserir fórmula aqui',
'math_tip'        => 'Fórmula matemática (LaTeX)',
'nowiki_sample'   => 'Inserir texto não-formatado aqui',
'nowiki_tip'      => 'Ignorar formatação wiki',
'image_sample'    => 'Exemplo.jpg',
'image_tip'       => 'Ficheiro embutido',
'media_sample'    => 'Exemplo.ogg',
'media_tip'       => 'Ligação para ficheiro',
'sig_tip'         => 'Sua assinatura, com hora e data',
'hr_tip'          => 'Linha horizontal (utilize moderadamente)',

# Edit pages
'summary'                          => 'Sumário:',
'subject'                          => 'Assunto/cabeçalho:',
'minoredit'                        => 'Marcar como edição menor',
'watchthis'                        => 'Observar esta página',
'savearticle'                      => 'Gravar página',
'preview'                          => 'Prever',
'showpreview'                      => 'Mostrar previsão',
'showlivepreview'                  => 'Pré-visualização em tempo real',
'showdiff'                         => 'Mostrar alterações',
'anoneditwarning'                  => "'''Atenção''': Você não se encontra autenticado. O seu endereço de IP será registado no histórico de edições desta página.",
'missingsummary'                   => "'''Lembrete:''' Você não introduziu um sumário de edição. Se carregar novamente em Salvar a sua edição será salva sem um sumário.",
'missingcommenttext'               => 'Por favor, introduzida um comentário abaixo.',
'missingcommentheader'             => "'''Lembrete:''' Você não introduziu um assunto/título para este comentário. Se carregar novamente em Salvar a sua edição será salva sem um título/assunto.",
'summary-preview'                  => 'Previsão de sumário:',
'subject-preview'                  => 'Previsão de assunto/título:',
'blockedtitle'                     => 'O utilizador está bloqueado',
'blockedtext'                      => '<big>O seu nome de utilizador ou endereço de IP foi bloqueado</big>

O bloqueio foi realizado por $1.
O motivo apresentado foi \'\'$2\'\'.

* Início do bloqueio: $8
* Expiração do bloqueio: $6
* Destino do bloqueio: $7

Você pode contactar $1 ou outro [[{{MediaWiki:Grouppage-sysop}}|administrador]] para discutir sobre o bloqueio.

Note que não poderá utilizar a funcionalidade "Contactar utilizador" se não possuir uma conta neste wiki ({{SITENAME}}) com um endereço de email válido indicado nas suas [[Special:Preferences|preferências de utilizador]] e se tiver sido bloqueado de utilizar tal recurso.

O seu endereço de IP atual é $3 e a ID de bloqueio é $5.
Por favor, inclua tais dados em quaisquer tentativas de esclarecimentos.',
'autoblockedtext'                  => 'O seu endereço de IP foi bloqueado de forma automática, uma vez que foi utilizado recentemente por outro utilizador, o qual foi bloqueado por $1.
O motivo apresentado foi:

:\'\'$2\'\'

* Início do bloqueio: $8
* Expiração do bloqueio: $6
* Destino do bloqueio: $7

Você pode contactar $1 ou outro [[{{MediaWiki:Grouppage-sysop}}|administrador]] para discutir o bloqueio.

Note que para utilizar a funcionalidade "Contactar utilizador" precisa de ter um endereço de email válido nas suas [[Special:Preferences|preferências de utilizador]] e de não lhe ter sido bloqueado o uso desta funcionalidade.

O seu endereço de IP neste momento é $3 e sua ID de bloqueio é #$5.
Por favor, inclua estes dados em qualquer contacto connosco.',
'blockednoreason'                  => 'sem motivo especificado',
'blockedoriginalsource'            => "O código de '''$1''' é mostrado abaixo:",
'blockededitsource'                => "O texto das '''suas edições''' em '''$1''' é mostrado abaixo:",
'whitelistedittitle'               => 'É necessário autenticar-se para editar páginas',
'whitelistedittext'                => 'Precisa de se $1 para poder editar páginas.',
'confirmedittext'                  => 'Você precisa confirmar o seu endereço de e-mail antes de começar a editar páginas.
Por favor, introduza um e valide-o através das suas [[Special:Preferences|preferências de utilizador]].',
'nosuchsectiontitle'               => 'Secção inexistente',
'nosuchsectiontext'                => 'Você tentou editar uma secção que não existe. Uma vez que não há a secção $1, não há um local para salvar a sua edição.',
'loginreqtitle'                    => 'Autenticação Requerida',
'loginreqlink'                     => 'autenticar-se',
'loginreqpagetext'                 => 'Você precisa de $1 para poder visualizar outras páginas.',
'accmailtitle'                     => 'Palavra-chave enviada.',
'accmailtext'                      => "Uma palavra-chave gerada aleatoriamente para [[User talk:$1|$1]] foi enviada para $2.

A palavra-chave para este nova conta pode ser alterada na página para ''[[Special:ChangePassword|alterar palavra-chave]]'' após autenticação.",
'newarticle'                       => '(Nova)',
'newarticletext'                   => "Você seguiu uma ligação para uma página que ainda não existe.
Para criá-la, escreva o seu conteúdo na caixa abaixo
(veja a [[{{MediaWiki:Helppage}}|página de ajuda]] para mais detalhes).
Se você chegou até aqui por engano, clique no botão '''voltar''' (ou ''back'') do seu navegador.",
'anontalkpagetext'                 => "----''Esta é a página de discussão para um utilizador anónimo que ainda não criou uma conta ou que não a utiliza, de modo a que temos que utilizar o endereço de IP para identificá-lo(a).
Um endereço de IP pode ser partilhado por vários utilizadores.
Se é um utilizador anónimo e sente que comentários irrelevantes foram direccionados a você, por favor [[Special:UserLogin/signup|crie uma conta]] ou [[Special:UserLogin|autentique-se]] para evitar futuras confusões com outros utilizadores anónimos.''",
'noarticletext'                    => 'Não existe atualmente texto nesta página.
Você pode [[Special:Search/{{PAGENAME}}|pesquisar pelo título desta página]] noutras páginas,
<span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{urlencode:{{FULLPAGENAME}}}}}} pesquisar os registos relacionados],
ou [{{fullurl:{{FULLPAGENAME}}|action=edit}} editar esta página]</span>.',
'noarticletext-nopermission'       => 'Não há actualmente texto nesta página.
Você pode [[Special:Search/{{PAGENAME}}|procurar este título de página]] em outras páginas,
ou <span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{urlencode:{{FULLPAGENAME}}}}}} procurar os registos relacionados] </span>.',
'userpage-userdoesnotexist'        => 'A conta "$1" não se encontra registada. Por gentileza, verifique se deseja mesmo criar/editar esta página.',
'userpage-userdoesnotexist-view'   => 'A conta de utilizador "$1" não está registada.',
'clearyourcache'                   => "'''Nota:''' Após salvar, terá de limpar a cache do seu navegador para ver as alterações.'''
'''Mozilla / Firefox / Safari:''' pressione ''Shift'' enquanto clica em ''Recarregar'', ou pressione ou ''Ctrl-F5'' ou ''Ctrl-R'' (''Command-R'' num Macintosh); '''Konqueror:''': clique no botão ''Recarregar'' ou pressione ''F5''; '''Opera:''' limpe a sua cache em ''Ferramentas → Preferências'' (''Tools → Preferences''); '''Internet Explorer:''' pressione ''Ctrl'' enquanto clica em ''Recarregar'' ou pressione ''Ctrl-F5'';",
'usercssyoucanpreview'             => "'''Dica:''' Utilize o botão \"Mostrar previsão\" para testar seu novo CSS antes de salvar.",
'userjsyoucanpreview'              => "'''Dica:''' Utilize o botão \"Mostrar previsão\" para testar seu novo JS antes de salvar.",
'usercsspreview'                   => "'''Lembre-se que está apenas a prever o seu CSS particular.
Ele ainda não foi salvo!'''",
'userjspreview'                    => "'''Lembre-se que está apenas a testar/prever o seu JavaScript particular e que ele ainda não foi salvo!'''",
'userinvalidcssjstitle'            => "'''Aviso:''' Não existe um tema \"\$1\". Lembre-se que as páginas .css e  .js utilizam um título em minúsculas, exemplo: {{ns:user}}:Alguém/monobook.css aposto a {{ns:user}}:Alguém/Monobook.css.",
'updated'                          => '(Actualizado)',
'note'                             => "'''Nota:'''",
'previewnote'                      => "'''Isto é apenas uma previsão.
As modificações ainda não foram salvas!'''",
'previewconflict'                  => 'Esta previsão reflete o texto que está na área de edição acima e como ele aparecerá se você escolher salvar.',
'session_fail_preview'             => "'''Não foi possível processar a sua edição devido à perda de dados da sua sessão.
Por favor tente novamente.
Caso continue a não funcionar, tente [[Special:UserLogout|sair]] e voltar a entrar na sua conta.'''",
'session_fail_preview_html'        => "'''Não foi possível processar a sua edição devido a uma perda de dados de sessão.'''

''Devido a {{SITENAME}} possuir HTML bruto activo, a previsão não será exibida, como forma de precaução contra ataques por JavaScript.''

'''Por favor, tente novamente caso esta seja uma tentativa de edição legítima.
Caso continue a não funcionar, tente [[Special:UserLogout|desautenticar-se]] e voltar a entrar na sua conta.'''",
'token_suffix_mismatch'            => "'''A sua edição foi rejeitada uma vez que seu software de navegação mutilou os sinais de pontuação no identificador de edição. A edição foi rejeitada para evitar perdas no texto da página.
Isso acontece ocasionalmente quando se usa um serviço de proxy anonimizador mal configurado.'''",
'editing'                          => 'Editando $1',
'editingsection'                   => 'Editando $1 (secção)',
'editingcomment'                   => 'Editando $1 (nova secção)',
'editconflict'                     => 'Conflito de edição: $1',
'explainconflict'                  => 'Alguém mudou a página enquanto você a estava editando.
A área de texto acima mostra o texto da forma como está no momento.
Suas mudanças são mostradas na área abaixo
Você terá que mesclar suas modificações no texto existente.
<b>SOMENTE</b> o texto na área acima será salvo quando você pressionar
"Salvar página".<br />',
'yourtext'                         => 'Seu texto',
'storedversion'                    => 'Versão guardada',
'nonunicodebrowser'                => "'''AVISO: O seu navegador não é compatível com as especificações unicode.
Um contorno terá de ser utilizado para permitir que você possa editar as páginas com segurança: os caracteres não-ASCII aparecerão na caixa de edição no formato de códigos hexadecimais.'''",
'editingold'                       => "'''CUIDADO: Encontra-se a editar uma revisão
desactualizada desta página.
Se salvá-la, todas as mudanças feitas a partir desta revisão serão perdidas.'''",
'yourdiff'                         => 'Diferenças',
'copyrightwarning'                 => "Por favor, note que todas as suas contribuições em {{SITENAME}} são consideradas como lançadas nos termos da licença $2 (veja $1 para detalhes). Se não deseja que o seu texto seja inexoravelmente editado e redistribuído de tal forma, não o envie.<br />
Você está, ao mesmo tempo, a garantir-nos que isto é algo escrito por si, ou algo copiado de uma fonte de textos em domínio público ou similarmente de teor livre.
'''NÃO ENVIE TRABALHO PROTEGIDO POR DIREITOS DE AUTOR SEM A DEVIDA PERMISSÃO!'''",
'copyrightwarning2'                => "Por favor, note que todas as suas contribuições em {{SITENAME}} podem ser editadas, alteradas ou removidas por outros contribuidores. Se você não deseja que o seu texto seja inexoravelmente editado, não o envie.<br />
Você está, ao mesmo tempo, a garantir-nos que isto é algo escrito por si, ou algo copiado de alguma fonte de textos em domínio público ou similarmente de teor livre (veja $1 para detalhes).
'''NÃO ENVIE TRABALHO PROTEGIDO POR DIREITOS DE AUTOR SEM A DEVIDA PERMISSÃO!'''",
'longpagewarning'                  => "'''AVISO:''' Esta página possui $1 kilobytes; alguns
navegadores possuem problemas em editar páginas maiores que 32 kb.
Por favor, considere seccionar a página em secções de menor dimensão.",
'longpageerror'                    => "'''ERRO: O texto de página que você submeteu tem mais de $1 quilobytes em tamanho, que é maior que o máximo de $2 quilobytes. A página não pode ser salva.'''",
'readonlywarning'                  => "'''Aviso: A base de dados foi bloqueada para manutenção, pelo que não poderá gravar a sua edição neste momento.'''
Pode, no entanto, copiar o seu texto para um editor externo e guardá-lo para posterior submissão.

O administrador que bloqueou a base de dados forneceu a seguinte explicação: $1",
'protectedpagewarning'             => "'''AVISO: Esta página foi protegida e poderá ser editada apenas por utilizadores com privilégios sysop (administradores).'''",
'semiprotectedpagewarning'         => "'''Nota:''' Esta página foi protegida de modo a que apenas utilizadores registados a possam editar.",
'cascadeprotectedwarning'          => "'''Atenção:''' Esta página se encontra protegida de forma que apenas {{int:group-sysop}} possam editá-la, uma vez que se encontra incluída {{PLURAL:\$1|na seguinte página protegida|nas seguintes páginas protegidas}} com a \"proteção progressiva\":",
'titleprotectedwarning'            => "'''ATENÇÃO: Esta página foi protegida por forma a que [[Special:ListGroupRights|privilégios específicos]] sejam necessários para criá-la.'''",
'templatesused'                    => '{{PLURAL:$1|Predefinição utilizada|Predefinições utilizadas}} nesta página:',
'templatesusedpreview'             => '{{PLURAL:$1|Predefinição utilizada|Predefinições utilizadas}} nesta antevisão:',
'templatesusedsection'             => '{{PLURAL:$1|Predefinição utilizada|Predefinições utilizadas}} nesta secção:',
'template-protected'               => '(protegida)',
'template-semiprotected'           => '(semi-protegida)',
'hiddencategories'                 => 'Esta página integra {{PLURAL:$1|uma categoria oculta|$1 categorias ocultas}}:',
'edittools'                        => '<!-- O texto colocado aqui será mostrado abaixo dos formulários de edição e de envio de ficheiros. -->',
'nocreatetitle'                    => 'A criação de páginas encontra-se limitada',
'nocreatetext'                     => '{{SITENAME}} tem restringida a habilidade de criar novas páginas.
Pode voltar atrás e editar uma página já existente, ou [[Special:UserLogin|autenticar-se ou criar uma conta]].',
'nocreate-loggedin'                => 'Você não possui permissões de criar novas páginas.',
'permissionserrors'                => 'Erros de permissões',
'permissionserrorstext'            => 'Você não possui permissão de fazer isso, {{PLURAL:$1|pelo seguinte motivo|pelos seguintes motivos}}:',
'permissionserrorstext-withaction' => 'Você não possui permissão para $2, {{PLURAL:$1|pelo seguinte motivo|pelos seguintes motivos}}:',
'recreate-moveddeleted-warn'       => "'''Atenção: Você está a recriar uma página que já foi anteriormente eliminada.'''

Certifique-se de que seja adequado prosseguir editando esta página.
O registo de eliminação e de movimento desta página é exibido a seguir, para conveniência:",
'moveddeleted-notice'              => 'Esta página foi eliminada.
Disponibiliza-se abaixo o registo de eliminações e de movimento para esta página, para referência.',
'log-fulllog'                      => 'Ver registo detalhado',
'edit-hook-aborted'                => 'Edição abortada por hook.
Ele não deu nenhuma explicação.',
'edit-gone-missing'                => 'Não foi possível atualizar a página.
Ela foi, aparentemente, eliminada.',
'edit-conflict'                    => 'Conflito de edição.',
'edit-no-change'                   => 'A sua edição foi ignorada, uma vez que o texto não sofreu alterações.',
'edit-already-exists'              => 'Não foi possível criar uma nova página.
Ela já existia.',

# Parser/template warnings
'expensive-parserfunction-warning'        => 'Aviso: Esta página contém demasiadas chamadas custosas a funções do analisador "parser".

Deveria ter menos de $2 {{PLURAL:$2|chamada|chamadas}}. Neste momento {{PLURAL:$1|há $1 chamada|existem $1 chamadas}}.',
'expensive-parserfunction-category'       => 'Páginas com demasiadas chamadas custosas a funções do analisador "parser"',
'post-expand-template-inclusion-warning'  => 'Aviso: O tamanho de inclusão de predefinições é demasiado grande, algumas predefinições não serão incluídas.',
'post-expand-template-inclusion-category' => 'Páginas onde o tamanho de inclusão de predefinições é excedido',
'post-expand-template-argument-warning'   => 'Aviso: Esta página contém pelo menos um argumento de predefinição com um tamanho expandido demasiado grande.
Estes argumentos foram omitidos.',
'post-expand-template-argument-category'  => 'Páginas com omissões de argumentos em predefinições',
'parser-template-loop-warning'            => 'Ciclo de predefinições detectado: [[$1]]',
'parser-template-recursion-depth-warning' => 'Atingido o limite de profundidade de recursividade de predefinição ($1)',

# "Undo" feature
'undo-success' => 'A edição pode ser desfeita.
Por favor, verifique a seguinte comparação para se certificar de que é o que pretende fazer, e grave abaixo as alterações para finalizar e desfazer a edição.',
'undo-failure' => 'A edição não pôde ser desfeita devido a alterações intermediárias conflitantes.',
'undo-norev'   => 'A edição não pôde ser desfeita porque não existe ou foi apagada.',
'undo-summary' => 'Desfeita a edição $1 de [[Special:Contributions/$2|$2]] ([[User talk:$2|Discussão]])',

# Account creation failure
'cantcreateaccounttitle' => 'Não é possível criar uma conta',
'cantcreateaccount-text' => "Este IP ('''$1''') foi bloqueado de criar novas contas por [[User:$3|$3]].

A justificativa apresentada por $3 foi ''$2''",

# History pages
'viewpagelogs'           => 'Ver registos para esta página',
'nohistory'              => 'Não há histórico de edições para esta página.',
'currentrev'             => 'Revisão actual',
'currentrev-asof'        => 'Edição actual tal como $1',
'revisionasof'           => 'Edição tal como às $1',
'revision-info'          => 'Revisão de $1; $2',
'previousrevision'       => '← Versão anterior',
'nextrevision'           => 'Versão posterior →',
'currentrevisionlink'    => 'ver versão actual',
'cur'                    => 'atu',
'next'                   => 'prox',
'last'                   => 'ant',
'page_first'             => 'primeira',
'page_last'              => 'última',
'histlegend'             => "Seleção de diferença: marque as caixas de opção das versões que deseja comparar e carregue em 'Enter' ou no botão no fundo da página.<br />
Legenda: '''(atu)''' = diferenças da versão atual,
'''(ant)''' = diferenças da versão anterior, '''m''' = edição menor",
'history-fieldset-title' => 'Navegar pelo histórico',
'history-show-deleted'   => 'Somente eliminados',
'histfirst'              => 'Mais antigas',
'histlast'               => 'Mais recentes',
'historysize'            => '({{PLURAL:$1|1 byte|$1 bytes}})',
'historyempty'           => '(vazia)',

# Revision feed
'history-feed-title'          => 'História de revisão',
'history-feed-description'    => 'Histórico de edições para esta página nesta wiki',
'history-feed-item-nocomment' => '$1 em $2',
'history-feed-empty'          => 'A página requisitada não existe.
Poderá ter sido eliminada da wiki ou renomeada.
Tente [[Special:Search|pesquisar na wiki]] por páginas relevantes.',

# Revision deletion
'rev-deleted-comment'         => '(comentário removido)',
'rev-deleted-user'            => '(nome de utilizador removido)',
'rev-deleted-event'           => '(entrada removida)',
'rev-deleted-text-permission' => "Esta edição desta página foi '''eliminada'''.
Poderão existir detalhes no [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} registo de eliminação].",
'rev-deleted-text-unhide'     => "Esta edição desta página foi '''eliminada'''.
Poderão existir detalhes no [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} registo de eliminação].
Por ser um administrador, você pode mesmo assim [$1 ver esta edição] se desejar prosseguir.",
'rev-suppressed-text-unhide'  => "Esta edição desta página foi '''suprimida'''.
Poderão existir detalhes no [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} registo de supressão].
Por ser um administrador, você pode ainda [$1 ver esta edição] se desejar prosseguir.",
'rev-deleted-text-view'       => "Esta edição desta página foi '''eliminada'''.
Por ser um administrador, você pode vê-la; poderão existir detalhes no [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} registo de eliminação].",
'rev-suppressed-text-view'    => "Esta edição desta página foi '''suprimida'''.
Por ser um administrador, você pode vê-la; poderão existir detalhes no [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} registo de supressão].",
'rev-deleted-no-diff'         => "Você não pode ver esta diferença entre revisões porque uma das revisões foi '''eliminada'''.
Poderá haver detalhes no [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} registo de eliminação].",
'rev-deleted-unhide-diff'     => "Uma das revisões destas diferenças foi '''eliminada'''.
Poderá haver detalhes no [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} registo de eliminação].
Por ser um administrador, você pode mesmo assim [$1 ver estas diferenças], se desejar prosseguir.",
'rev-suppressed-unhide-diff'  => "Uma das revisões deste diferencial foi '''suprimido'''.
Podem haver detalhes no [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} registo de supressão].
Como administrador você pode ainda [$1 pode ver o diferencial] se desejar prosseguir.",
'rev-deleted-diff-view'       => "Uma das revisões nesta listagem de diferenças foi '''eliminada'''.
Como administrador, pode visualizar a listagem de diferenças; poderão existir mais detalhes no [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} registo de eliminações].",
'rev-suppressed-diff-view'    => "Uma das revisões nesta listagem de diferenças foi '''suprimida'''.
Como administrador, pode visualizar a listagem de diferenças; poderão existir mais detalhes no [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} registo de supressões].",
'rev-delundel'                => 'mostrar/esconder',
'revisiondelete'              => 'Eliminar/restaurar edições',
'revdelete-nooldid-title'     => 'Edição de destino inválida',
'revdelete-nooldid-text'      => 'Você ou não especificou uma(s) edição(ões) de destino, a edição especificada não existe ou, ainda, você está tentando ocultar a edição atual.',
'revdelete-nologtype-title'   => 'Tipo de registo não especificado',
'revdelete-nologtype-text'    => 'Você não especificou um tipo de registo sobre o qual executar esta ação.',
'revdelete-nologid-title'     => 'Entrada de registo inválida',
'revdelete-nologid-text'      => 'Você não especificou um evento de registo alvo para executar esta função ou a entrada especificada não existe.',
'revdelete-no-file'           => 'O ficheiro especificado não existe.',
'revdelete-show-file-confirm' => 'Tem a certeza de que quer visualizar uma revisão eliminada do ficheiro "<nowiki>$1</nowiki>" de $2 em $3?',
'revdelete-show-file-submit'  => 'Sim',
'revdelete-selected'          => "'''{{PLURAL:$2|Edição seleccionada|Edições seleccionadas}} de [[:$1]]:'''",
'logdelete-selected'          => "'''{{PLURAL:$1|Evento de registo seleccionado|Eventos de registo seleccionados}}:'''",
'revdelete-text'              => "'''Edições e eventos eliminados continuarão a aparecer no histórico e registos da página, mas partes do seu conteúdo estarão inacessíveis ao público.'''
Outros administradores em {{SITENAME}} continuarão a poder aceder ao conteúdo escondido e podem restaurá-lo novamente através desta mesma interface, a menos que restrições adicionais sejam definidas.",
'revdelete-confirm'           => 'Por favor confirme que pretende executar esta acção, que compreende as suas consequências e que o faz em concordância com as [[{{MediaWiki:Policy-url}}|políticas e recomendações]].',
'revdelete-suppress-text'     => "A supressão deverá '''apenas''' ser usada para os seguintes casos:
* Informação pessoa inapropriada
*: ''endereços de domicílio e números de telefone, números da segurança social, etc''",
'revdelete-legend'            => 'Definir restrições de visualização',
'revdelete-hide-text'         => 'Ocultar texto da edição',
'revdelete-hide-name'         => 'Ocultar acção e alvo',
'revdelete-hide-comment'      => 'Esconder comentário de edição',
'revdelete-hide-user'         => 'Ocultar nome de utilizador/IP',
'revdelete-hide-restricted'   => 'Suprimir dados a administradores bem como a outros',
'revdelete-suppress'          => 'Suprimir dados de administradores, bem como de outros',
'revdelete-hide-image'        => 'Ocultar conteúdos do ficheiro',
'revdelete-unsuppress'        => 'Remover restrições das edições restauradas',
'revdelete-log'               => 'Motivo da eliminação:',
'revdelete-submit'            => 'Aplicar à edição seleccionada',
'revdelete-logentry'          => 'modificou visibilidade de edições de [[$1]]',
'logdelete-logentry'          => 'alterou a visibilidade de eventos para [[$1]]',
'revdelete-success'           => 'Visibilidade de edição definida com sucesso.',
'revdelete-failure'           => "'''A visibilidade da revisão não pôde ser estabelecida:'''
$1",
'logdelete-success'           => "'''Visibilidade de evento definida com sucesso.'''",
'logdelete-failure'           => "'''A visibilidade do registo não pôde ser estabelecida:'''
$1",
'revdel-restore'              => 'Alterar visibilidade',
'pagehist'                    => 'Histórico da página',
'deletedhist'                 => 'Histórico de eliminações',
'revdelete-content'           => 'conteúdo',
'revdelete-summary'           => 'sumário de edição',
'revdelete-uname'             => 'nome de utilizador',
'revdelete-restricted'        => 'restrições a administradores aplicadas',
'revdelete-unrestricted'      => 'restrições a administradores removidas',
'revdelete-hid'               => 'ocultou $1',
'revdelete-unhid'             => 'desocultou $1',
'revdelete-log-message'       => '$1 para $2 {{PLURAL:$2|edição|edições}}',
'logdelete-log-message'       => '$1 para $2 {{PLURAL:$2|evento|eventos}}',
'revdelete-hide-current'      => 'Erro ao ocultar o item datado de $2, $1: esta é a revisão actual.
Não pode ser ocultada.',
'revdelete-show-no-access'    => 'Erro ao mostrar o item datado de $2, $1: este item foi marcado como "restrito".
Você não tem acesso a ele.',
'revdelete-modify-no-access'  => 'Erro ao modificar o item datado de $2, $1: este item foi marcado como "restrito".
Você não tem acesso a ele.',
'revdelete-modify-missing'    => 'Erro ao modificar o item ID $1: está em falta na base de dados!',
'revdelete-no-change'         => "'''Aviso:''' o item datado de $2, $1 já possui as configurações de visualização requeridas.",
'revdelete-concurrent-change' => 'Erro ao modificar o item com data/hora $2, $1: o seu estado parece ter sido alterado por outra pessoa enquanto você tentava modificá-lo.
Por favor, verifique os registos.',
'revdelete-only-restricted'   => 'Não pode suprimir itens de serem visualizados por administradores sem também escolher uma das outras opções de supressão.',
'revdelete-reason-dropdown'   => '*Razões comuns para eliminação
** Violação dos direitos autorais
** Informações pessoais inapropriadas
** Informações potencialmente difamatórias',
'revdelete-otherreason'       => 'Outro/motivo adicional:',
'revdelete-reasonotherlist'   => 'Outro motivo',
'revdelete-edit-reasonlist'   => 'Editar motivos de eliminação',
'revdelete-offender'          => 'Autor da revisão:',

# Suppression log
'suppressionlog'     => 'Registo de supressões',
'suppressionlogtext' => 'Abaixo está uma lista das remoções e bloqueios envolvendo conteúdo ocultado por administradores.
Veja a [[Special:IPBlockList|lista de bloqueios]] para uma lista de banimentos e bloqueios em efeito neste momento.',

# History merging
'mergehistory'                     => 'Fundir histórico de páginas',
'mergehistory-header'              => 'A partir desta página é possível fundir históricos de edições de uma página em outra.
Certifique-se de que tal alteração manterá a continuidade das ações.',
'mergehistory-box'                 => 'Fundir edições de duas páginas:',
'mergehistory-from'                => 'Página de origem:',
'mergehistory-into'                => 'Página de destino:',
'mergehistory-list'                => 'Histórico de edições habilitadas para fusão',
'mergehistory-merge'               => 'As edições de [[:$1]] a seguir poderão ser fundidas em [[:$2]]. Utilize a coluna de botões de opção para fundir apenas as edições feitas entre o intervalo de tempo especificado. Note que ao utilizar os links de navegação esta coluna será retornada a seus valores padrão.',
'mergehistory-go'                  => 'Exibir edições habilitadas a serem fundidas',
'mergehistory-submit'              => 'Fundir edições',
'mergehistory-empty'               => 'Não existem edições habilitadas a serem fundidas.',
'mergehistory-success'             => 'Foram fundidas $3 {{PLURAL:$3|edição|edições}} de [[:$1]] em [[:$2]].',
'mergehistory-fail'                => 'Não foi possível fundir os históricos; por gentileza, verifique a página e os parâmetros de tempo.',
'mergehistory-no-source'           => 'A página de origem ($1) não existe.',
'mergehistory-no-destination'      => 'A página de destino ($1) não existe.',
'mergehistory-invalid-source'      => 'A página de origem precisa ser um título válido.',
'mergehistory-invalid-destination' => 'A página de destino precisa ser um título válido.',
'mergehistory-autocomment'         => '[[:$1]] fundido em [[:$2]]',
'mergehistory-comment'             => '[[:$1]] fundido em [[:$2]]: $3',
'mergehistory-same-destination'    => 'As páginas de origem e de destino não podem ser as mesmas',
'mergehistory-reason'              => 'Motivo:',

# Merge log
'mergelog'           => 'Registo de fusão de históricos',
'pagemerge-logentry' => '[[$1]] foi fundida em [[$2]] (até a edição $3)',
'revertmerge'        => 'Desfazer fusão',
'mergelogpagetext'   => 'Segue-se um registo das mais recentes fusões de históricos de páginas.',

# Diffs
'history-title'            => 'Histórico de edições de "$1"',
'difference'               => '(Diferença entre edições)',
'lineno'                   => 'Linha $1:',
'compareselectedversions'  => 'Compare as versões seleccionadas',
'showhideselectedversions' => 'Mostrar/ocultar versões selecionadas',
'editundo'                 => 'desfazer',

# Search results
'searchresults'                    => 'Resultados de pesquisa',
'searchresults-title'              => 'Resultados da pesquisa por "$1"',
'searchresulttext'                 => 'Para mais informações de como pesquisar em {{SITENAME}}, consulte [[{{MediaWiki:Helppage}}|{{int:help}}]].',
'searchsubtitle'                   => 'Você pesquisou por \'\'\'[[:$1]]\'\'\' ([[Special:Prefixindex/$1|páginas iniciadas por "$1"]]{{int:pipe-separator}}[[Special:WhatLinksHere/$1|páginas que apontam para "$1"]])',
'searchsubtitleinvalid'            => 'Você pesquisou por "$1"',
'noexactmatch'                     => "'''Não existe uma página com o título \"\$1\".''' Você pode [[:\$1|criar tal página]].",
'noexactmatch-nocreate'            => "'''Não há uma página intitulada como \"\$1\".'''",
'toomanymatches'                   => 'Foram retornados demasiados resultados. Por favor, tente um filtro de pesquisa diferente',
'titlematches'                     => 'Resultados nos títulos das páginas',
'notitlematches'                   => 'Nenhum título de página coincide com o termo pesquisado',
'textmatches'                      => 'Resultados nos textos das páginas',
'notextmatches'                    => 'Não foi possível localizar, no conteúdo das páginas, o termo pesquisado',
'prevn'                            => 'anteriores {{PLURAL:$1|$1}}',
'nextn'                            => 'próximos {{PLURAL:$1|$1}}',
'prevn-title'                      => '$1 {{PLURAL:$1|resultado anterior|resultados anteriores}}',
'nextn-title'                      => '{{PLURAL:$1|próximo|próximos}} $1 {{PLURAL:$1|resultado|resultados}}',
'shown-title'                      => 'Mostrar $1 {{PLURAL:$1|resultado|resultados}} por página',
'viewprevnext'                     => 'Ver ($1 {{int:pipe-separator}} $2) ($3).',
'searchmenu-legend'                => 'Opções de pesquisa',
'searchmenu-exists'                => "*Página '''[[$1]]'''",
'searchmenu-new'                   => "'''Crie a página \"[[:\$1]]\" neste wiki!'''",
'searchhelp-url'                   => 'Help:Conteúdos',
'searchmenu-prefix'                => '[[Special:PrefixIndex/$1|Navegue por páginas com este prefixo]]',
'searchprofile-articles'           => 'Páginas de conteúdo',
'searchprofile-project'            => 'Ajuda e páginas de Projecto',
'searchprofile-images'             => 'Multimédia',
'searchprofile-everything'         => 'Tudo',
'searchprofile-advanced'           => 'Avançado',
'searchprofile-articles-tooltip'   => 'Pesquisar em $1',
'searchprofile-project-tooltip'    => 'Pesquisar em $1',
'searchprofile-images-tooltip'     => 'Pesquisar ficheiros',
'searchprofile-everything-tooltip' => 'Pesquisar em todo o conteúdo (incluindo páginas de discussão)',
'searchprofile-advanced-tooltip'   => 'Pesquisar nos espaços nominais personalizados',
'search-result-size'               => '$1 ({{PLURAL:$2|1 palavra|$2 palavras}})',
'search-result-score'              => 'Relevancia: $1%',
'search-redirect'                  => '(redirecionamento de $1)',
'search-section'                   => '(secção $1)',
'search-suggest'                   => 'Será que quis dizer: $1',
'search-interwiki-caption'         => 'Projetos irmãos',
'search-interwiki-default'         => 'Resultados de $1:',
'search-interwiki-more'            => '(mais)',
'search-mwsuggest-enabled'         => 'com sugestões',
'search-mwsuggest-disabled'        => 'sem sugestões',
'search-relatedarticle'            => 'Relacionado',
'mwsuggest-disable'                => 'Desactivar sugestões AJAX',
'searcheverything-enable'          => 'Procurar em todos os espaços nominais',
'searchrelated'                    => 'relacionados',
'searchall'                        => 'todos',
'showingresults'                   => "A seguir {{PLURAL:$1|é mostrado '''um''' resultado|são mostrados até '''$1''' resultados}}, iniciando no '''$2'''º.",
'showingresultsnum'                => "A seguir {{PLURAL:$3|é mostrado '''um''' resultado|são mostrados '''$3''' resultados}}, iniciando com o '''$2'''º.",
'showingresultsheader'             => "{{PLURAL:$5|Resultado '''$1''' de '''$3'''|Resultados '''$1–$2''' de '''$3'''}} para '''$4'''",
'nonefound'                        => "'''Nota''': apenas alguns espaços nominais são pesquisados por defeito. Tente utilizar o prefixo ''all:'' em sua busca, para pesquisar por todos os conteúdos deste wiki (inclusive páginas de discussão, predefinições etc), ou mesmo, utilizando o espaço nominal desejado como prefixo.",
'search-nonefound'                 => 'Não houve resultados para a pesquisa.',
'powersearch'                      => 'Pesquisa avançada',
'powersearch-legend'               => 'Pesquisa avançada',
'powersearch-ns'                   => 'Pesquisar nos espaços nominais:',
'powersearch-redir'                => 'Listar redireccionamentos',
'powersearch-field'                => 'Pesquisar',
'powersearch-togglelabel'          => 'Selecionar:',
'powersearch-toggleall'            => 'Todos',
'powersearch-togglenone'           => 'Nenhum',
'search-external'                  => 'Pesquisa externa',
'searchdisabled'                   => 'A pesquisa da {{SITENAME}} se encontra desabilitada.
Utilize nesse meio tempo mecanismos externos, tal como o do Google.
Note que os índices do conteúdo da {{SITENAME}} destes sites podem estar desactualizados.',

# Quickbar
'qbsettings'               => 'Barra Rápida',
'qbsettings-none'          => 'Nenhuma',
'qbsettings-fixedleft'     => 'Fixo à esquerda',
'qbsettings-fixedright'    => 'Fixo à direita',
'qbsettings-floatingleft'  => 'Flutuando à esquerda',
'qbsettings-floatingright' => 'Flutuando à direita',

# Preferences page
'preferences'                   => 'Preferências',
'mypreferences'                 => 'Minhas preferências',
'prefs-edits'                   => 'Número de edições:',
'prefsnologin'                  => 'Não autenticado',
'prefsnologintext'              => 'Precisa de estar <span class="plainlinks">[{{fullurl:{{#Special:UserLogin}}|returnto=$1}} autenticado]</span> para definir as suas preferências.',
'changepassword'                => 'Alterar palavra-chave',
'prefs-skin'                    => 'Tema',
'skin-preview'                  => 'Pré-visualizar',
'prefs-math'                    => 'Matemática',
'datedefault'                   => 'Sem preferência',
'prefs-datetime'                => 'Data e hora',
'prefs-personal'                => 'Perfil de utilizador',
'prefs-rc'                      => 'Mudanças recentes',
'prefs-watchlist'               => 'Lista de páginas vigiadas',
'prefs-watchlist-days'          => 'Dias a mostrar na lista de vigiados:',
'prefs-watchlist-days-max'      => '(máximo: 7 dias)',
'prefs-watchlist-edits'         => 'Número de edições a mostrar na lista de vigiados expandida:',
'prefs-watchlist-edits-max'     => '(máximo: 1000)',
'prefs-watchlist-token'         => 'Sinal da lista de vigiados:',
'prefs-misc'                    => 'Diversos',
'prefs-resetpass'               => 'Alterar palavra-chave',
'prefs-email'                   => 'Opções de email',
'prefs-rendering'               => 'Aparência',
'saveprefs'                     => 'Salvar',
'resetprefs'                    => 'Eliminar as alterações não-salvas',
'restoreprefs'                  => 'Restaurar todas as configurações padrão',
'prefs-editing'                 => 'Opções de edição',
'prefs-edit-boxsize'            => 'Tamanho da janela de edição.',
'rows'                          => 'Linhas:',
'columns'                       => 'Colunas:',
'searchresultshead'             => 'Pesquisa',
'resultsperpage'                => 'Resultados por página:',
'contextlines'                  => 'Linhas por resultado:',
'contextchars'                  => 'Contexto por linha:',
'stub-threshold'                => 'Links para páginas de conteúdo aparecerão <a href="#" class="stub">desta forma</a> se elas possuírem menos de (bytes):',
'recentchangesdays'             => 'Dias a serem exibidos nas Mudanças recentes:',
'recentchangesdays-max'         => '(máximo: $1 {{PLURAL:$1|dia|dias}})',
'recentchangescount'            => 'Número de edições a serem exibidas por defeito:',
'prefs-help-recentchangescount' => 'Isto inclui mudanças recentes, histórico de páginas e registos.',
'prefs-help-watchlist-token'    => 'O preenchimento desse campo com uma chave secreta irá gerar um feed RSS para a sua lista de vigiados.
Qualquer um que conhecer a chave desta área poderá ler os sua lista de vigiados, então escolha uma palavra secreta segura.
Aqui está uma palavra secreta gerada aleatoriamente que você poderá usar: $1',
'savedprefs'                    => 'As suas preferências foram gravadas.',
'timezonelegend'                => 'Fuso horário:',
'localtime'                     => 'Horário local:',
'timezoneuseserverdefault'      => 'Usar padrão do servidor',
'timezoneuseoffset'             => 'Outro (especificar diferença)',
'timezoneoffset'                => 'Diferença horária¹:',
'servertime'                    => 'Horário do servidor:',
'guesstimezone'                 => 'Preencher a partir do navegador (browser)',
'timezoneregion-africa'         => 'África',
'timezoneregion-america'        => 'América',
'timezoneregion-antarctica'     => 'Antártida',
'timezoneregion-arctic'         => 'Ártico',
'timezoneregion-asia'           => 'Ásia',
'timezoneregion-atlantic'       => 'Oceano Atlântico',
'timezoneregion-australia'      => 'Austrália',
'timezoneregion-europe'         => 'Europa',
'timezoneregion-indian'         => 'Oceano Índico',
'timezoneregion-pacific'        => 'Oceano Pacífico',
'allowemail'                    => 'Permitir email de outros utilizadores',
'prefs-searchoptions'           => 'Opções de busca',
'prefs-namespaces'              => 'Espaços nominais',
'defaultns'                     => 'Pesquisar por defeito nestes espaços nominais:',
'default'                       => 'padrão',
'prefs-files'                   => 'Ficheiros',
'prefs-custom-css'              => 'CSS personalizada',
'prefs-custom-js'               => 'JS personalizado',
'prefs-reset-intro'             => 'Você pode usar esta página para restaurar as suas preferências para os valores predefinidos do sítio.
Esta acção não pode ser desfeita.',
'prefs-emailconfirm-label'      => 'Confirmação do email:',
'prefs-textboxsize'             => 'Tamanho da janela de edição',
'youremail'                     => 'Endereço de email:',
'username'                      => 'Nome de utilizador:',
'uid'                           => 'Número de identificação:',
'prefs-memberingroups'          => 'Membro {{PLURAL:$1|do grupo|dos grupos}}:',
'prefs-registration'            => 'Hora de registo:',
'yourrealname'                  => 'Nome verdadeiro:',
'yourlanguage'                  => 'Idioma:',
'yourvariant'                   => 'Variante',
'yournick'                      => 'Assinatura:',
'prefs-help-signature'          => 'Comentários nas páginas de discussão devem ser assinados com "<nowiki>~~~~</nowiki>" que será convertido em sua assinatura e a data da edição',
'badsig'                        => 'Assinatura inválida; verifique o código HTML utilizado.',
'badsiglength'                  => 'A sua assinatura é muito longa.
Não deverá ter mais de $1 {{PLURAL:$1|caráter|carateres}}.',
'yourgender'                    => 'Sexo:',
'gender-unknown'                => 'Não especificado',
'gender-male'                   => 'Masculino',
'gender-female'                 => 'Feminino',
'prefs-help-gender'             => 'Opcional: usado para correto endereçamento por software baseado no sexo. Esta informação será pública.',
'email'                         => 'E-mail',
'prefs-help-realname'           => 'O fornecimento de seu Nome verdadeiro é opcional, mas, caso decida o revelar, este será utilizado para lhe dar crédito pelo seu trabalho.',
'prefs-help-email'              => 'O endereço de e-mail é opcional, mas permite que uma nova palavra-chave lhe seja enviada em caso de esquecimento da mesma.
Pode também escolher permitir que outros entrem em contacto consigo através da sua página de utilizador ou discussão sem que tenha de lhes revelar a sua identidade.',
'prefs-help-email-required'     => 'O endereço de correio electrónico é requerido.',
'prefs-info'                    => 'Informações básicas',
'prefs-i18n'                    => 'Internacionalização',
'prefs-signature'               => 'Assinatura',
'prefs-dateformat'              => 'Formato de data',
'prefs-timeoffset'              => 'Desvio horário',
'prefs-advancedediting'         => 'Opções avançadas',
'prefs-advancedrc'              => 'Opções avançadas',
'prefs-advancedrendering'       => 'Opções avançadas',
'prefs-advancedsearchoptions'   => 'Opções avançadas',
'prefs-advancedwatchlist'       => 'Opções avançadas',
'prefs-display'                 => 'Opções de visualização',
'prefs-diffs'                   => 'Diffs',

# User rights
'userrights'                  => 'Gestão de privilégios de utilizadores',
'userrights-lookup-user'      => 'Gerir grupos de utilizadores',
'userrights-user-editname'    => 'Introduza um nome de utilizador:',
'editusergroup'               => 'Editar Grupos de Utilizadores',
'editinguser'                 => "Modificando privilégios do utilizador '''[[User:$1|$1]]''' ([[User talk:$1|{{int:talkpagelinktext}}]]{{int:pipe-separator}}[[Special:Contributions/$1|{{int:contribslink}}]])",
'userrights-editusergroup'    => 'Editar grupos do utilizador',
'saveusergroups'              => 'Salvar Grupos do Utilizador',
'userrights-groupsmember'     => 'Membro de:',
'userrights-groups-help'      => 'É possível alterar os grupos em que este utilizador se encontra:
* Uma caixa de selecção seleccionada significa que o utilizador se encontra no grupo.
* Uma caixa de selecção desseleccionada significa que o utilizador não se encontra no grupo.
* Um * indica que não pode remover o grupo depois de o adicionar, ou vice-versa.',
'userrights-reason'           => 'Motivo de alterações:',
'userrights-no-interwiki'     => 'Você não tem permissão de alterar privilégios de utilizadores em outras wikis.',
'userrights-nodatabase'       => 'A base de dados $1 não existe ou não é uma base de dados local.',
'userrights-nologin'          => 'Você precisa [[Special:UserLogin|autenticar-se]] como um administrador para especificar os privilégios de utilizador.',
'userrights-notallowed'       => 'Sua conta não possui permissão para conceder privilégios a utilizadores.',
'userrights-changeable-col'   => 'Grupos que pode alterar',
'userrights-unchangeable-col' => 'Grupos que não pode alterar',

# Groups
'group'               => 'Grupo:',
'group-user'          => 'Utilizadores',
'group-autoconfirmed' => 'Utilizadores auto-confirmados',
'group-bot'           => 'Robôs',
'group-sysop'         => 'Administradores',
'group-bureaucrat'    => 'Burocratas',
'group-suppress'      => 'Oversights',
'group-all'           => '(todos)',

'group-user-member'          => 'Utilizador',
'group-autoconfirmed-member' => 'Utilizador auto-confirmado',
'group-bot-member'           => 'Robô',
'group-sysop-member'         => 'Administrador',
'group-bureaucrat-member'    => 'Burocrata',
'group-suppress-member'      => 'Oversight',

'grouppage-user'          => '{{ns:project}}:Utilizadores',
'grouppage-autoconfirmed' => '{{ns:project}}:Auto-confirmados',
'grouppage-bot'           => '{{ns:project}}:Robôs',
'grouppage-sysop'         => '{{ns:project}}:Administradores',
'grouppage-bureaucrat'    => '{{ns:project}}:Burocratas',
'grouppage-suppress'      => '{{ns:project}}:Oversight',

# Rights
'right-read'                  => 'Ler páginas',
'right-edit'                  => 'Editar páginas',
'right-createpage'            => 'Criar páginas (que não sejam páginas de discussão)',
'right-createtalk'            => 'Criar páginas de discussão',
'right-createaccount'         => 'Criar novas contas de utilizador',
'right-minoredit'             => 'Marcar edições como menores',
'right-move'                  => 'Mover páginas',
'right-move-subpages'         => 'Mover páginas com as suas subpáginas',
'right-move-rootuserpages'    => 'Mover páginas raiz de utilizadores',
'right-movefile'              => 'Mover ficheiros',
'right-suppressredirect'      => 'Não criar um redireccionamento do nome antigo quando uma página é movida',
'right-upload'                => 'Carregar ficheiros',
'right-reupload'              => 'Sobrescrever um ficheiro existente',
'right-reupload-own'          => 'Sobrescrever um ficheiro existente carregado pelo mesmo utilizador',
'right-reupload-shared'       => 'Sobrescrever localmente ficheiros no repositório partilhado de imagens',
'right-upload_by_url'         => 'Carregar um ficheiro de um endereço URL',
'right-purge'                 => 'Purgar a cache de uma página no sítio sem página de confirmação',
'right-autoconfirmed'         => 'Editar páginas semi-protegidas',
'right-bot'                   => 'Ser tratado como um processo automatizado',
'right-nominornewtalk'        => 'Não ter o aviso de novas mensagens despoletado quando são feitas edições menores a páginas de discussão',
'right-apihighlimits'         => 'Usar limites superiores em consultas (queries) via API',
'right-writeapi'              => 'Uso da API de escrita',
'right-delete'                => 'Eliminar páginas',
'right-bigdelete'             => 'Eliminar páginas com histórico grande',
'right-deleterevision'        => 'Eliminar e restaurar edições específicas de páginas',
'right-deletedhistory'        => 'Ver entradas de histórico eliminadas, sem o texto associado',
'right-deletedtext'           => 'Ver texto eliminado e mudanças entre revisões eliminadas',
'right-browsearchive'         => 'Buscar páginas eliminadas',
'right-undelete'              => 'Restaurar uma página',
'right-suppressrevision'      => 'Rever e restaurar edições ocultadas dos Sysops',
'right-suppressionlog'        => 'Ver registos privados',
'right-block'                 => 'Impedir outros utilizadores de editarem',
'right-blockemail'            => 'Impedir um utilizador de enviar email',
'right-hideuser'              => 'Bloquear um nome de utilizador, escondendo-o do público',
'right-ipblock-exempt'        => 'Contornar bloqueios de IP, automáticos e de intervalo',
'right-proxyunbannable'       => 'Contornar bloqueios automáticos de proxies',
'right-protect'               => 'Mudar níveis de protecção e editar páginas protegidas',
'right-editprotected'         => 'Editar páginas protegidas (sem protecção em cascata)',
'right-editinterface'         => 'Editar a interface de utilizador',
'right-editusercssjs'         => 'Editar os ficheiros CSS e JS de outros utilizadores',
'right-editusercss'           => 'Editar os ficheiros CSS de outros utilizadores',
'right-edituserjs'            => 'Editar os ficheiros JS de outros utilizadores',
'right-rollback'              => 'Reverter rapidamente o último utilizador que editou uma página em particular',
'right-markbotedits'          => 'Marcar edições revertidas como edições de bot',
'right-noratelimit'           => 'Não afectado pelos limites de velocidade de operação',
'right-import'                => 'Importar páginas de outros wikis',
'right-importupload'          => 'Importar páginas de um ficheiro xml',
'right-patrol'                => 'Marcar edições de outros utilizadores como patrulhadas',
'right-autopatrol'            => 'Ter edições automaticamente marcadas como patrulhadas',
'right-patrolmarks'           => 'Usar funcionalidades de patrulhagem das mudanças recentes',
'right-unwatchedpages'        => 'Ver uma lista de páginas não vigiadas',
'right-trackback'             => "Submeter um 'trackback'",
'right-mergehistory'          => 'Fundir o histórico de edições de páginas',
'right-userrights'            => 'Editar todos os privilégios de utilizador',
'right-userrights-interwiki'  => 'Editar privilégios de utilizador de utilizadores noutros sítios wiki',
'right-siteadmin'             => 'Bloquear e desbloquear a base de dados',
'right-reset-passwords'       => 'Repor a palavra-chave de outros utilizadores',
'right-override-export-depth' => 'Exportar páginas incluindo páginas ligadas até uma profundidade de 5',
'right-versiondetail'         => 'Mostrar informações completas da versão de software',
'right-sendemail'             => 'Enviar email a outros usuários',

# User rights log
'rightslog'      => 'Registo de privilégios de utilizador',
'rightslogtext'  => 'Este é um registo de mudanças nos privilégios dos utilizadores.',
'rightslogentry' => 'alterou grupo de acesso de $1 (de $2 para $3)',
'rightsnone'     => '(nenhum)',

# Associated actions - in the sentence "You do not have permission to X"
'action-read'                 => 'ler esta página',
'action-edit'                 => 'editar esta página',
'action-createpage'           => 'criar páginas',
'action-createtalk'           => 'criar páginas de discussão',
'action-createaccount'        => 'criar esta conta de utilizador',
'action-minoredit'            => 'marcar esta edição como uma edição menor',
'action-move'                 => 'mover esta página',
'action-move-subpages'        => 'mover esta página e suas subpáginas',
'action-move-rootuserpages'   => 'mover páginas raiz de utilizadores',
'action-movefile'             => 'mover este ficheiro',
'action-upload'               => 'enviar este ficheiro',
'action-reupload'             => 'sobrescrever o ficheiro existente',
'action-reupload-shared'      => 'sobrescrever este ficheiro disponível em um repositório partilhado',
'action-upload_by_url'        => 'enviar este ficheiro através de uma URL',
'action-writeapi'             => 'utilizar o modo de escrita da API',
'action-delete'               => 'eliminar esta página',
'action-deleterevision'       => 'eliminar esta edição',
'action-deletedhistory'       => 'ver o histórico de edições eliminadas desta página',
'action-browsearchive'        => 'pesquisar páginas eliminadas',
'action-undelete'             => 'restaurar esta página',
'action-suppressrevision'     => 'rever e restaurar esta edição oculta',
'action-suppressionlog'       => 'ver este registo privado',
'action-block'                => 'impedir este utilizador de editar',
'action-protect'              => 'alterar os níveis de proteção desta página',
'action-import'               => 'importar esta página a partir de outra wiki',
'action-importupload'         => 'importar esta página a partir de um ficheiro xml',
'action-patrol'               => 'marcar as edições de outros utilizadores como patrulhadas',
'action-autopatrol'           => 'ter suas edições marcadas como patrulhadas',
'action-unwatchedpages'       => 'ver a lista de páginas não-vigiadas',
'action-trackback'            => 'enviar um trackback',
'action-mergehistory'         => 'fundir o histórico de edições desta página',
'action-userrights'           => 'editar os privilégios de utilizadores',
'action-userrights-interwiki' => 'editar privilégios de utilizadores de outras wikis',
'action-siteadmin'            => 'bloquear ou desbloquear a base de dados',

# Recent changes
'nchanges'                          => '$1 {{PLURAL:$1|alteração|alterações}}',
'recentchanges'                     => 'Mudanças recentes',
'recentchanges-legend'              => 'Opções das mudanças recentes',
'recentchangestext'                 => 'Acompanhe as mudanças mais recentes em {{SITENAME}} nesta página.',
'recentchanges-feed-description'    => 'Acompanhe as Mudanças recentes deste wiki por este feed.',
'recentchanges-label-legend'        => 'Legenda: $1.',
'recentchanges-legend-newpage'      => '$1 -página nova',
'recentchanges-label-newpage'       => 'Esta edição criou uma página nova',
'recentchanges-legend-minor'        => '$1 - modificação menor',
'recentchanges-label-minor'         => 'Esta é uma edição menor',
'recentchanges-legend-bot'          => '$1 - edição de robô',
'recentchanges-label-bot'           => 'Esta modificação foi realizada por um robô',
'recentchanges-legend-unpatrolled'  => '$1 - edição não patrulhada',
'recentchanges-label-unpatrolled'   => 'Esta edição ainda não foi patrulhada',
'rcnote'                            => "A seguir {{PLURAL:$1|está listada '''uma''' alteração ocorrida|estão listadas '''$1''' alterações ocorridas}} {{PLURAL:$2|no último dia|nos últimos '''$2''' dias}}, a partir das $5 de $4.",
'rcnotefrom'                        => 'Abaixo estão as mudanças desde <b>$2</b> (mostradas até <b>$1</b>).',
'rclistfrom'                        => 'Mostrar as novas alterações a partir de $1',
'rcshowhideminor'                   => '$1 edições menores',
'rcshowhidebots'                    => '$1 robôs',
'rcshowhideliu'                     => '$1 utilizadores registados',
'rcshowhideanons'                   => '$1 utilizadores anónimos',
'rcshowhidepatr'                    => '$1 edições patrulhadas',
'rcshowhidemine'                    => '$1 as minhas edições',
'rclinks'                           => 'Mostrar as últimas $1 mudanças nos últimos $2 dias<br />$3',
'diff'                              => 'dif',
'hist'                              => 'hist',
'hide'                              => 'Esconder',
'show'                              => 'Mostrar',
'minoreditletter'                   => 'm',
'newpageletter'                     => 'N',
'boteditletter'                     => 'b',
'number_of_watching_users_pageview' => '[{{PLURAL:$1|$1 utilizador|$1 utilizadores}} a vigiar]',
'rc_categories'                     => 'Limite para categorias (separar com "|")',
'rc_categories_any'                 => 'Qualquer',
'newsectionsummary'                 => '/* $1 */ nova secção',
'rc-enhanced-expand'                => 'Mostrar detalhes (requer JavaScript)',
'rc-enhanced-hide'                  => 'Esconder detalhes',

# Recent changes linked
'recentchangeslinked'          => 'Alterações relacionadas',
'recentchangeslinked-feed'     => 'Alterações relacionadas',
'recentchangeslinked-toolbox'  => 'Alterações relacionadas',
'recentchangeslinked-title'    => 'Alterações relacionadas com "$1"',
'recentchangeslinked-noresult' => 'Não ocorreram alterações em páginas relacionadas no intervalo de tempo fornecido.',
'recentchangeslinked-summary'  => "Esta página especial lista as alterações mais recentes de páginas que possuam um link a outra (ou de membros de uma categoria especificada).
Páginas que estejam em [[Special:Watchlist|sua lista de vigiados]] são exibidas em '''negrito'''.",
'recentchangeslinked-page'     => 'Nome da página:',
'recentchangeslinked-to'       => 'Mostrar alterações a páginas relacionadas com a página fornecida',

# Upload
'upload'                      => 'Carregar ficheiro',
'uploadbtn'                   => 'Carregar ficheiro',
'reuploaddesc'                => 'Cancelar o envio e retornar ao formulário de upload',
'upload-tryagain'             => 'Submeta a descrição de ficheiro modificada',
'uploadnologin'               => 'Não autenticado',
'uploadnologintext'           => 'Você necessita estar [[Special:UserLogin|autenticado]] para enviar ficheiros.',
'upload_directory_missing'    => 'A directoria de carregamentos ($1) está em falta e não pôde ser criada pelo webserver.',
'upload_directory_read_only'  => 'O directório de recebimento de ficheiros ($1) não tem permissões de escrita para o servidor Web.',
'uploaderror'                 => 'Erro ao carregar',
'uploadtext'                  => "Utilize o formulário abaixo para carregar novos ficheiros.
Para ver ou pesquisar imagens anteriormente carregadas, consulte a [[Special:FileList|lista de ficheiros carregados]]. (Re)envios são também registados no [[Special:Log/upload|registo de carregamentos]], e as eliminações, no [[Special:Log/delete|registo de eliminação]].

Para incluir a imagem em uma página, utilize o ''link'' em um dos seguintes formatos:
* '''<tt><nowiki>[[</nowiki>{{ns:file}}<nowiki>:ficheiro.jpg]]</nowiki></tt>''' para utilizar a versão completa da imagem;
* '''<tt><nowiki>[[</nowiki>{{ns:file}}<nowiki>:ficheiro.png|200px|thumb|left|texto]]</nowiki></tt>''' para utilizar uma renderização de 200 pixels dentro de um box posicionado à esquerda contendo 'texto' como descrição;
* '''<tt><nowiki>[[</nowiki>{{ns:media}}<nowiki>:ficheiro.ogg]]</nowiki></tt>''' para uma ligação direta ao ficheiro.",
'upload-permitted'            => 'Tipos de ficheiros permitidos: $1.',
'upload-preferred'            => 'Tipos de ficheiros preferidos: $1.',
'upload-prohibited'           => 'Tipos de ficheiro proibidos: $1.',
'uploadlog'                   => 'registo de carregamento',
'uploadlogpage'               => 'Registo de carregamento',
'uploadlogpagetext'           => 'Segue-se uma lista dos carregamentos mais recentes.
Consulte a [[Special:NewFiles|galeria de novos ficheiros]] para uma visualização mais amigável.',
'filename'                    => 'Nome do ficheiro',
'filedesc'                    => 'Descrição do ficheiro',
'fileuploadsummary'           => 'Sumário:',
'filereuploadsummary'         => 'Alterações ao ficheiro:',
'filestatus'                  => 'Estado dos direitos de autor:',
'filesource'                  => 'Fonte:',
'uploadedfiles'               => 'Ficheiros carregados',
'ignorewarning'               => 'Ignorar aviso e salvar de qualquer forma.',
'ignorewarnings'              => 'Ignorar todos os avisos',
'minlength1'                  => 'Os nomes de ficheiros devem de ter pelo menos uma letra.',
'illegalfilename'             => 'O ficheiro "$1" possui caracteres que não são permitidos no título de uma página. Por favor, altere o nome do ficheiro e tente carregar novamente.',
'badfilename'                 => 'O nome do ficheiro foi alterado para "$1".',
'filetype-badmime'            => 'Ficheiros de tipo MIME "$1" não são permitidos de serem enviados.',
'filetype-bad-ie-mime'        => 'Este ficheiro não pôde ser carregado porque o Internet Explorer o iria detectar como "$1", que é um tipo de ficheiro não permitido e potencialmente perigoso.',
'filetype-unwanted-type'      => "'''\".\$1\"''' é um tipo de ficheiro não desejado.
{{PLURAL:\$3|O tipo preferível é|Os tipos preferíveis são}} \$2.",
'filetype-banned-type'        => "'''\".\$1\"''' é um tipo proibido de ficheiro.
{{PLURAL:\$3|O tipo permitido é|Os tipos permitidos são}} \$2.",
'filetype-missing'            => 'O ficheiro não possui uma extensão (como, por exemplo, ".jpg").',
'large-file'                  => 'É recomendável que os ficheiros não sejam maiores que $1; este possui $2.',
'largefileserver'             => 'O tamanho deste ficheiro é superior ao qual o servidor encontra-se configurado para permitir.',
'emptyfile'                   => 'O ficheiro que está a tentar carregar parece encontrar-se vazio. Isto poderá ser devido a um erro na escrita do nome do ficheiro. Por favor verifique se realmente deseja carregar este ficheiro.',
'fileexists'                  => "Já existe um ficheiro com este nome.
Por favor, verifique '''<tt>[[:$1]]</tt>''' caso não tenha a certeza se deseja alterar o ficheiro actual.
[[$1|thumb]]",
'filepageexists'              => "A página de descrição deste ficheiro já foi criada em '''<tt>[[:$1]]</tt>''', mas atualmente não existe nenhum ficheiro com este nome.
O sumário que introduzir não aparecerá na página de descrição.
Para o fazer aparecer, terá que o editar manualmente.
[[$1|thumb]]",
'fileexists-extension'        => "Já existe um ficheiro de nome similar: [[$2|thumb]]
* Nome do ficheiro que está sendo enviado: '''<tt>[[:$1]]</tt>'''
* Nome do ficheiro existente: '''<tt>[[:$2]]</tt>'''
Por gentileza, escolha um nome diferente.",
'fileexists-thumbnail-yes'    => "O ficheiro aparenta ser uma imagem de tamanho reduzido (''miniatura'', ou ''thumbnail)''. [[$1|thumb]]
Por gentileza, verifique o ficheiro '''<tt>[[:$1]]</tt>'''.
Se o ficheiro enviado é o mesmo do de tamanho original, não é necessário enviar uma versão de miniatura adicional.",
'file-thumbnail-no'           => "O nome do ficheiro começa com '''<tt>$1</tt>'''.
Isso faz parecer se tratar de uma imagem de tamanho reduzido (''miniatura'', ou ''thumbnail)''.
Se você tem acesso à imagem de resolução completa, prefira envia-la no lugar desta. Caso não seja o caso, altere o nome de ficheiro.",
'fileexists-forbidden'        => 'Já existe um ficheiro com este nome, e não pode ser reescrito.
Se ainda pretende carregar o seu ficheiro, por favor, volte atrás e use um novo nome. [[File:$1|thumb|center|$1]]',
'fileexists-shared-forbidden' => 'Já existe um ficheiro com este nome no repositório de ficheiros partilhados. 
Caso deseje mesmo assim enviar seu ficheiro, volte atrás e carregue-o sob um novo nome. [[File:$1|thumb|center|$1]]',
'file-exists-duplicate'       => 'Esta imagem é uma duplicata do seguinte {{PLURAL:$1|ficheiro|ficheiros}}:',
'file-deleted-duplicate'      => 'Um ficheiro idêntico a este ([[$1]]) foi eliminado anteriormente. Verifique o motivo da eliminação de tal ficheiro antes de prosseguir com o re-envio.',
'successfulupload'            => 'Envio efectuado com sucesso',
'uploadwarning'               => 'Aviso de envio',
'uploadwarning-text'          => 'Por favor modifique a descrição do ficheiro em baixo e tente novamente.',
'savefile'                    => 'Salvar ficheiro',
'uploadedimage'               => 'carregou "[[$1]]"',
'overwroteimage'              => 'foi enviada uma nova versão de "[[$1]]"',
'uploaddisabled'              => 'Carregamentos desactivados',
'uploaddisabledtext'          => 'O carregamento de ficheiros encontra-se desactivado.',
'php-uploaddisabledtext'      => 'O carregamento de ficheiros por PHP está desactivado. Por favor, verifique a configuração file_uploads.',
'uploadscripted'              => 'Este ficheiro contém HTML ou código que pode ser erradamente interpretado por um navegador web.',
'uploadcorrupt'               => 'O ficheiro encontra-se corrompido ou tem uma extensão incorreta. Por gentileza, verifique o ocorrido e tente novamente.',
'uploadvirus'                 => 'O ficheiro contém vírus! Detalhes: $1',
'upload-source'               => 'Ficheiro fonte',
'sourcefilename'              => 'Nome do ficheiro de origem:',
'sourceurl'                   => 'URL fonte:',
'destfilename'                => 'Nome do ficheiro de destino:',
'upload-maxfilesize'          => 'Tamanho máximo do ficheiro: $1',
'upload-description'          => 'Descrição do ficheiro',
'upload-options'              => 'Opções de transferência para o servidor',
'watchthisupload'             => 'Vigiar este ficheiro',
'filewasdeleted'              => 'Um ficheiro com este nome foi carregado anteriormente e subsequentemente eliminado. Você precisa verificar o $1 antes de proceder ao carregamento novamente.',
'upload-wasdeleted'           => "'''Atenção: Você está enviando um ficheiro eliminado anteriormente.'''

Verfique se é apropriado prosseguir enviando este ficheiro.
O registo de eliminação é exibido a seguir, para sua comodidade:",
'filename-bad-prefix'         => "O nome do ficheiro que você está enviando começa com '''\"\$1\"''', um nome pouco esclarecedor, comumente associado de forma automática por câmeras digitais. Por gentileza, escolha um nome de ficheiro mais explicativo.",
'filename-prefix-blacklist'   => ' #<!-- deixe esta linha exactamente como está --> <pre>
# A sintaxe é a seguinte:
#   * Tudo a partir do caractere "#" até ao fim da linha é um comentário
#   * Todas as linhas não vazias são um prefixo para nomes de ficheiros típicos atribuídos automaticamente por câmaras digitais
CIMG # Casio
DSC_ # Nikon
DSCF # Fuji
DSCN # Nikon
DUW # alguns telefones móveis
IMG # genérico
JD # Jenoptik
MGP # Pentax
PICT # misc.
 #</pre> <!-- deixe esta linha exactamente como está -->',

'upload-proto-error'        => 'Protocolo incorrecto',
'upload-proto-error-text'   => 'O envio de ficheiros remotos requer endereços (URLs) que iniciem com <code>http://</code> ou <code>ftp://</code>.',
'upload-file-error'         => 'Erro interno',
'upload-file-error-text'    => 'Ocorreu um erro interno ao se tentar criar um arquivo temporário no servidor.
Por gentileza, contate um [[Special:ListUsers/sysop|administrador]].',
'upload-misc-error'         => 'Erro desconhecido de envio',
'upload-misc-error-text'    => 'Ocorreu um erro desconhecido durante o envio.
Verifique se o endereço (URL) é válido e acessível e tente novamente.
Caso o problema persista, contacte um [[Special:ListUsers/sysop|administrador]].',
'upload-too-many-redirects' => 'O URL continha muitos redirecionamentos',
'upload-unknown-size'       => 'Tamanho desconhecido',
'upload-http-error'         => 'Ocorreu um erro HTTP: $1',

# img_auth script messages
'img-auth-accessdenied' => 'Acesso negado',
'img-auth-nopathinfo'   => 'Falta PATH_INFO
Seu servidor não está configurado para passar essa informação.
Pode ser baseado em CGI e não suportar img_auth.
Veja http://www.mediawiki.org/wiki/Manual:Image_Authorization.',
'img-auth-notindir'     => 'O caminho requerido não está no directório de carregamento configurado.',
'img-auth-badtitle'     => 'Não é possível construir um título válido a partir de "$1".',
'img-auth-nologinnWL'   => 'Você não está logado e "$1" não está na "lista branca".',
'img-auth-nofile'       => 'O ficheiro "$1" não existe.',
'img-auth-isdir'        => 'Você está a tentar aceder ao directório "$1".
Apenas o acesso a ficheiros é permitido.',
'img-auth-streaming'    => "A fazer o ''streaming'' de \"\$1\".",
'img-auth-public'       => 'Essa função de img_auth.php é para produzir arquivos a partir de uma wiki privada.
Essa wiki está configurada como uma wiki pública.
Para uma segurança otimizada, img_auth.php é desabilitado.',
'img-auth-noread'       => 'O usuário não tem acesso à leitura de "$1".',

# Some likely curl errors. More could be added from <http://curl.haxx.se/libcurl/c/libcurl-errors.html>
'upload-curl-error6'       => 'Não foi possível acessar a URL',
'upload-curl-error6-text'  => 'Não foi possível acessar o endereço (URL) fornecido. Por gentileza, se certifique de o endereço foi fornecido corretamente e de que o sítio esteja acessível.',
'upload-curl-error28'      => 'Tempo limite para o envio do ficheiro excedido',
'upload-curl-error28-text' => 'O sítio demorou muito tempo a responder. Por gentileza, verifique se o sítio está acessível, aguarde alguns momentos e tente novamente. Talvez você deseje fazer nova tentativa em um horário menos congestionado.',

'license'            => 'Licença:',
'license-header'     => 'Licenciamento',
'nolicense'          => 'Nenhuma seleccionada',
'license-nopreview'  => '(Previsão não disponível)',
'upload_source_url'  => ' (um URL válido, publicamente acessível)',
'upload_source_file' => ' (um ficheiro no seu computador)',

# Special:ListFiles
'listfiles-summary'     => 'Esta página especial mostra todos os ficheiros carregados.
Por defeito, os últimos ficheiros carregados são mostrados no topo da lista.
Um clique sobre um cabeçalho de coluna altera a ordenação.',
'listfiles_search_for'  => 'Pesquisar por nome de imagem:',
'imgfile'               => 'ficheiro',
'listfiles'             => 'Lista de ficheiros',
'listfiles_date'        => 'Data',
'listfiles_name'        => 'Nome',
'listfiles_user'        => 'Utilizador',
'listfiles_size'        => 'Tamanho',
'listfiles_description' => 'Descrição',
'listfiles_count'       => 'Versões',

# File description page
'file-anchor-link'          => 'Ficheiro',
'filehist'                  => 'Histórico do ficheiro',
'filehist-help'             => 'Clique em uma data/horário para ver o ficheiro tal como ele se encontrava em tal momento.',
'filehist-deleteall'        => 'eliminar todas',
'filehist-deleteone'        => 'eliminar',
'filehist-revert'           => 'restaurar',
'filehist-current'          => 'actual',
'filehist-datetime'         => 'Data/Horário',
'filehist-thumb'            => 'Miniatura',
'filehist-thumbtext'        => 'Miniatura da versão das $1',
'filehist-nothumb'          => 'Miniatura indisponível',
'filehist-user'             => 'Utilizador',
'filehist-dimensions'       => 'Dimensões',
'filehist-filesize'         => 'Tamanho do ficheiro',
'filehist-comment'          => 'Comentário',
'filehist-missing'          => 'Ficheiro em falta',
'imagelinks'                => 'Ligações de ficheiros',
'linkstoimage'              => '{{PLURAL:$1|A seguinte página aponta|As seguintes $1 páginas apontam}} para este ficheiro:',
'linkstoimage-more'         => 'Mais de $1 {{PLURAL:$1|página aponta|páginas apontam}} para este ficheiro.
A lista a seguir mostra apenas {{PLURAL:$1|o primeiro link de página|os primeiros $1 links de páginas}} para este ficheiro.
Uma [[Special:WhatLinksHere/$2|listagem completa]] se encontra disponível.',
'nolinkstoimage'            => 'Nenhuma página aponta para este ficheiro.',
'morelinkstoimage'          => 'Ver [[Special:WhatLinksHere/$1|mais ligações]] para este ficheiro.',
'redirectstofile'           => '{{PLURAL:$1|O seguinte ficheiro redirecciona|Os seguintes ficheiros redireccionam}} para este ficheiro:',
'duplicatesoffile'          => '{{PLURAL:$1|O seguinte ficheiro é duplicado|Os seguintes $1 ficheiros são duplicados}} deste ficheiro ([[Special:FileDuplicateSearch/$2|mais detalhes]]):',
'sharedupload'              => 'Este ficheiro provém de $1 e pode ser utilizado por outros projectos.',
'sharedupload-desc-there'   => 'Este ficheiro provém de $1 e pode ser utilizado por outros projectos.
Por favor veja a [$2 página de descrição do ficheiro] para mais informações.',
'sharedupload-desc-here'    => 'Este ficheiro provém de $1 e pode ser utilizado por outros projectos.
A descrição presente na sua [$2 página de descrição] é mostrada abaixo.',
'filepage-nofile'           => 'Não existe nenhum ficheiro com esse nome.',
'filepage-nofile-link'      => 'Não existe nenhum ficheiro com esse nome, mas você pode [$1 carregá-lo].',
'uploadnewversion-linktext' => 'Carregar uma nova versão deste ficheiro',
'shared-repo-from'          => 'de $1',
'shared-repo'               => 'um repositório partilhado',

# File reversion
'filerevert'                => 'Reverter $1',
'filerevert-legend'         => 'Reverter ficheiro',
'filerevert-intro'          => "Você está revertendo '''[[Media:$1|$1]]''' para a [$4 versão das $3 de $2].",
'filerevert-comment'        => 'Comentário:',
'filerevert-defaultcomment' => 'Revertido para a versão de $1 - $2',
'filerevert-submit'         => 'Reverter',
'filerevert-success'        => "'''[[Media:$1|$1]]''' foi revertida para a [$4 versão das $3 de $2].",
'filerevert-badversion'     => 'Não há uma versão local anterior deste ficheiro no período de tempo especificado.',

# File deletion
'filedelete'                  => 'Eliminar $1',
'filedelete-legend'           => 'Eliminar ficheiro',
'filedelete-intro'            => "Você está prestes a eliminar o ficheiro '''[[Media:$1|$1]]''' junto com todo o seu histórico.",
'filedelete-intro-old'        => "Você se encontra prestes a eliminar a versão de '''[[Media:$1|$1]]''' tal como se encontrava em [$4 $3, $2].",
'filedelete-comment'          => 'Motivo de eliminação:',
'filedelete-submit'           => 'Eliminar',
'filedelete-success'          => "'''$1''' foi eliminado.",
'filedelete-success-old'      => "A versão de '''[[Media:$1|$1]]''' tal como $3, $2 foi eliminada.",
'filedelete-nofile'           => "'''$1''' não existe.",
'filedelete-nofile-old'       => "Não há uma versão de '''$1''' em arquivo com os parâmetros especificados.",
'filedelete-otherreason'      => 'Outro/motivo adicional:',
'filedelete-reason-otherlist' => 'Outro motivo',
'filedelete-reason-dropdown'  => '*Motivos comuns para eliminação
** Violação de direitos de autor
** Ficheiro duplicado',
'filedelete-edit-reasonlist'  => 'Editar motivos de eliminação',
'filedelete-maintenance'      => 'Eliminação e restauro de ficheiros temporariamente desabilitados durante a manutenção.',

# MIME search
'mimesearch'         => 'Pesquisa MIME',
'mimesearch-summary' => 'Esta página possibilita que os ficheiros sejam filtrados a partir de seu tipo MIME. Sintaxe de busca: tipo/subtipo (por exemplo, <tt>image/jpeg</tt>).',
'mimetype'           => 'Tipo MIME:',
'download'           => 'download',

# Unwatched pages
'unwatchedpages' => 'Páginas não vigiadas',

# List redirects
'listredirects' => 'Listar redireccionamentos',

# Unused templates
'unusedtemplates'     => 'Predefinições não utilizadas',
'unusedtemplatestext' => 'Esta página lista todas as páginas no espaço nominal {{ns:template}} que não estão incluídas numa outra página. Lembre-se de verificar por outras ligações para as predefinições antes de as apagar.',
'unusedtemplateswlh'  => 'outras ligações',

# Random page
'randompage'         => 'Página aleatória',
'randompage-nopages' => 'Não há páginas {{PLURAL:$2|no seguinte espaço nominal|nos seguintes espaços nominais}}: $1.',

# Random redirect
'randomredirect'         => 'Redireccionamento aleatório',
'randomredirect-nopages' => 'Não há redireccionamentos no espaço nominal "$1".',

# Statistics
'statistics'                   => 'Estatísticas',
'statistics-header-pages'      => 'Estatísticas de páginas',
'statistics-header-edits'      => 'Estatísticas de edições',
'statistics-header-views'      => 'Ver estatísticas',
'statistics-header-users'      => 'Estatísticas de utilizadores',
'statistics-header-hooks'      => 'Outras estatísticas',
'statistics-articles'          => 'Páginas de conteúdo',
'statistics-pages'             => 'Páginas',
'statistics-pages-desc'        => 'Todas as páginas do wiki, incluindo páginas de discussão, redirecionamentos, etc.',
'statistics-files'             => 'Ficheiros carregados',
'statistics-edits'             => 'Edições de página desde que {{SITENAME}} foi instalado',
'statistics-edits-average'     => 'Média de edições por página',
'statistics-views-total'       => 'Total de visualizações',
'statistics-views-peredit'     => 'Visualizações por edição',
'statistics-jobqueue'          => 'Tamanho da [http://www.mediawiki.org/wiki/Manual:Job_queue fila de tarefas]',
'statistics-users'             => '[[Special:ListUsers|Utilizadores]] registados',
'statistics-users-active'      => 'Utilizadores activos',
'statistics-users-active-desc' => 'Utilizadores que efectuaram uma ação {{PLURAL:$1|no último dia|nos últimos $1 dias}}',
'statistics-mostpopular'       => 'Páginas mais vistas',

'disambiguations'      => 'Página de desambiguações',
'disambiguationspage'  => 'Template:disambig',
'disambiguations-text' => "As páginas a seguir ligam a \"''páginas de desambiguação''\" ao invés de aos tópicos adequados.<br /> 
Uma página é considerada como de desambiguação se utilizar uma predefinição que esteja definida em [[MediaWiki:Disambiguationspage]]",

'doubleredirects'            => 'Redireccionamentos duplos',
'doubleredirectstext'        => 'Esta página lista páginas que redirecionam para outras páginas de redirecionamento.
Cada linha contém ligações para o primeiro e segundo redirecionamento, bem como o destino do segundo redirecionamento, geralmente contendo a página destino "real", que devia ser o destino do primeiro redirecionamento.
<s>Entradas cortadas</s> foram já solucionadas.',
'double-redirect-fixed-move' => '[[$1]] foi movido, passando a redirecionar para [[$2]]',
'double-redirect-fixer'      => 'Corretor de redirecionamentos',

'brokenredirects'        => 'Redireccionamentos quebrados',
'brokenredirectstext'    => 'Os seguintes redireccionamentos ligam para páginas inexistentes:',
'brokenredirects-edit'   => 'editar',
'brokenredirects-delete' => 'eliminar',

'withoutinterwiki'         => 'Páginas sem interwikis de idiomas',
'withoutinterwiki-summary' => 'As seguintes páginas não possuem links para versões em outros idiomas.',
'withoutinterwiki-legend'  => 'Prefixo',
'withoutinterwiki-submit'  => 'Exibir',

'fewestrevisions' => 'Páginas de conteúdo com menos edições',

# Miscellaneous special pages
'nbytes'                  => '$1 {{PLURAL:$1|byte|bytes}}',
'ncategories'             => '$1 {{PLURAL:$1|categoria|categorias}}',
'nlinks'                  => '$1 {{PLURAL:$1|link|links}}',
'nmembers'                => '$1 {{PLURAL:$1|membro|membros}}',
'nrevisions'              => '$1 {{PLURAL:$1|edição|edições}}',
'nviews'                  => '$1 {{PLURAL:$1|visita|visitas}}',
'specialpage-empty'       => 'Actualmente não há dados a serem exibidos nesta página.',
'lonelypages'             => 'Páginas órfãs',
'lonelypagestext'         => 'As seguintes páginas ou não têm hiperligações a apontar para elas ou não são transcluídas a partir de outras páginas nesta wiki.',
'uncategorizedpages'      => 'Páginas não categorizadas',
'uncategorizedcategories' => 'Categorias não categorizadas',
'uncategorizedimages'     => 'Imagens não categorizadas',
'uncategorizedtemplates'  => 'Predefinições não categorizadas',
'unusedcategories'        => 'Categorias não utilizadas',
'unusedimages'            => 'Ficheiros não utilizados',
'popularpages'            => 'Páginas populares',
'wantedcategories'        => 'Categorias desejadas',
'wantedpages'             => 'Páginas pedidas',
'wantedpages-badtitle'    => 'Título inválido no conjunto de resultados: $1',
'wantedfiles'             => 'Ficheiros desejados',
'wantedtemplates'         => 'Predefinições desejadas',
'mostlinked'              => 'Páginas com mais afluentes',
'mostlinkedcategories'    => 'Categorias com mais membros',
'mostlinkedtemplates'     => 'Predefinições com mais afluentes',
'mostcategories'          => 'Páginas de conteúdo com mais categorias',
'mostimages'              => 'Imagens com mais afluentes',
'mostrevisions'           => 'Páginas de conteúdo com mais edições',
'prefixindex'             => 'Todas as páginas com prefixo',
'shortpages'              => 'Páginas curtas',
'longpages'               => 'Páginas longas',
'deadendpages'            => 'Páginas sem saída',
'deadendpagestext'        => 'As seguintes páginas não contêm hiperligações para outras páginas nesta wiki.',
'protectedpages'          => 'Páginas protegidas',
'protectedpages-indef'    => 'Apenas protecções infinitas',
'protectedpages-cascade'  => 'Apenas protecções progressivas',
'protectedpagestext'      => 'As seguintes páginas encontram-se protegidas contra edições ou movimentações',
'protectedpagesempty'     => 'Não existem páginas, neste momento, protegidas com tais parâmetros.',
'protectedtitles'         => 'Títulos protegidos',
'protectedtitlestext'     => 'Os títulos a seguir encontram-se protegidos contra criação',
'protectedtitlesempty'    => 'Não há títulos protegidos com os parâmetros fornecidos.',
'listusers'               => 'Lista de utilizadores',
'listusers-editsonly'     => 'Mostrar apenas utilizadores com edições',
'listusers-creationsort'  => 'Ordenar por data de criação',
'usereditcount'           => '$1 {{PLURAL:$1|edição|edições}}',
'usercreated'             => 'Criado em $1 às $2',
'newpages'                => 'Páginas recentes',
'newpages-username'       => 'Nome de utilizador:',
'ancientpages'            => 'Páginas mais antigas',
'move'                    => 'Mover',
'movethispage'            => 'Mover esta página',
'unusedimagestext'        => 'Por favor, note que outros websites podem apontar para um ficheiro através de um URL directo e, por isso, podem estar a ser listadas aqui, mesmo estando em uso.',
'unusedcategoriestext'    => 'As seguintes categorias existem, embora nenhuma página ou categoria faça uso delas.',
'notargettitle'           => 'Sem alvo',
'notargettext'            => 'Você não especificou uma página alvo ou um utilizador para executar esta função.',
'nopagetitle'             => 'Página alvo não existe',
'nopagetext'              => 'A página alvo especificada não existe.',
'pager-newer-n'           => '{{PLURAL:$1|1 posterior|$1 posteriores}}',
'pager-older-n'           => '{{PLURAL:$1|1 anterior|$1 anteriores}}',
'suppress'                => 'Oversight',

# Book sources
'booksources'               => 'Fontes de livros',
'booksources-search-legend' => 'Procurar por fontes livreiras',
'booksources-go'            => 'Ir',
'booksources-text'          => 'É exibida a seguir uma listagem de links para outros sítios que vendem livros novos e usados e que possam possuir informações adicionais sobre os livros que você está pesquisando:',
'booksources-invalid-isbn'  => 'O número ISBN fornecido não parece ser válido; verifique a existência de erros ao copiar da fonte original.',

# Special:Log
'specialloguserlabel'  => 'Utilizador:',
'speciallogtitlelabel' => 'Título:',
'log'                  => 'Registos',
'all-logs-page'        => 'Todos os registos públicos',
'alllogstext'          => 'Exposição combinada de todos registos disponíveis no wiki {{SITENAME}}.
Você pode diminuir a lista escolhendo um tipo de registo, um nome de utilizador (sensível a minúsculas), ou uma página afectada (também sensível a minúsculas).',
'logempty'             => 'Nenhum item idêntico no registo.',
'log-title-wildcard'   => 'Procurar por títulos que sejam iniciados com tal texto',

# Special:AllPages
'allpages'          => 'Todas as páginas',
'alphaindexline'    => '$1 até $2',
'nextpage'          => 'Próxima página ($1)',
'prevpage'          => 'Página anterior ($1)',
'allpagesfrom'      => 'Começar exibindo páginas com:',
'allpagesto'        => 'Terminar de exibir páginas em:',
'allarticles'       => 'Todas as páginas',
'allinnamespace'    => 'Todas as páginas (espaço nominal $1)',
'allnotinnamespace' => 'Todas as páginas (excepto as do espaço nominal $1)',
'allpagesprev'      => 'Anterior',
'allpagesnext'      => 'Próximo',
'allpagessubmit'    => 'Ir',
'allpagesprefix'    => 'Exibir páginas com o prefixo:',
'allpagesbadtitle'  => 'O título de página fornecido encontrava-se inválido ou tinha um prefixo interlíngua ou inter-wiki.
Ele talvez contenha um ou mais caracteres que não podem ser utilizados em títulos.',
'allpages-bad-ns'   => '{{SITENAME}} não possui o espaço nominal "$1".',

# Special:Categories
'categories'                    => 'Categorias',
'categoriespagetext'            => '{{PLURAL:$1|A seguinte categoria contém páginas ou media|As seguintes categorias contêm páginas ou media}}.
As [[Special:UnusedCategories|categorias não utilizadas]] não são exibidas nesta listagem.
Veja também as [[Special:WantedCategories|categorias em falta]].',
'categoriesfrom'                => 'Listar categorias começando por:',
'special-categories-sort-count' => 'ordenar por contagem',
'special-categories-sort-abc'   => 'ordenar alfabeticamente',

# Special:DeletedContributions
'deletedcontributions'             => 'Edições eliminadas',
'deletedcontributions-title'       => 'Edições eliminadas',
'sp-deletedcontributions-contribs' => 'contribuições',

# Special:LinkSearch
'linksearch'       => 'Ligações externas',
'linksearch-pat'   => 'Padrão de procura:',
'linksearch-ns'    => 'Espaço nominal:',
'linksearch-ok'    => 'Pesquisar',
'linksearch-text'  => 'É possível utilizar "caracteres mágicos" como em "*.wikipedia.org".<br />Protocolos suportados: <tt>$1</tt>',
'linksearch-line'  => '$1 está lincado em $2',
'linksearch-error' => '"Caracteres mágicos" (wildcards) podem ser utilizados apenas no início do endereço.',

# Special:ListUsers
'listusersfrom'      => 'Mostrar utilizadores começando em:',
'listusers-submit'   => 'Exibir',
'listusers-noresult' => 'Não foram encontrados utilizadores para a forma pesquisada.',
'listusers-blocked'  => '({{GENDER:$1|bloqueado|bloqueada}})',

# Special:ActiveUsers
'activeusers'          => 'Lista de utilizadores ativos',
'activeusers-intro'    => 'Esta é uma lista dos utilizadores com qualquer actividade nos últimos $1 {{PLURAL:$1|dia|dias}}.',
'activeusers-count'    => '$1 {{PLURAL:$1|edição recente|edições recentes}} {{PLURAL:$3|no último dia|nos últimos $3 days}}',
'activeusers-from'     => 'Mostrar utilizadores começando em:',
'activeusers-noresult' => 'Nenhum utilizador encontrado.',

# Special:Log/newusers
'newuserlogpage'              => 'Registo de criação de utilizadores',
'newuserlogpagetext'          => 'Este é um registo de novas contas de utilizador',
'newuserlog-byemail'          => 'palavra-chave enviada por correio-electrónico',
'newuserlog-create-entry'     => 'Novo utilizador',
'newuserlog-create2-entry'    => 'criou nova conta $1',
'newuserlog-autocreate-entry' => 'Conta criada automaticamente',

# Special:ListGroupRights
'listgrouprights'                      => 'Privilégios de grupo de utilizadores',
'listgrouprights-summary'              => 'A seguinte lista contém os grupos de utilizadores definidos neste wiki, com os seus privilégios de acessos associados.
Se encontram disponíveis [[{{MediaWiki:Listgrouprights-helppage}}|informações adicionais]] sobre privilégios individuais.',
'listgrouprights-key'                  => '* <span class="listgrouprights-granted">Privilégio concedido</span>
* <span class="listgrouprights-revoked">Privilégio revogado</span>',
'listgrouprights-group'                => 'Grupo',
'listgrouprights-rights'               => 'Privilégios',
'listgrouprights-helppage'             => 'Help:Privilégios de grupo',
'listgrouprights-members'              => '(lista de membros)',
'listgrouprights-addgroup'             => 'Podem conceder acesso {{PLURAL:$2|ao grupo|aos grupos}}: $1',
'listgrouprights-removegroup'          => 'Podem remover acesso {{PLURAL:$2|do grupo|dos grupos}}: $1',
'listgrouprights-addgroup-all'         => 'Podem conceder acesso a todos os grupos',
'listgrouprights-removegroup-all'      => 'Podem remover acesso a todos os grupos',
'listgrouprights-addgroup-self'        => 'Pode adicionar {{PLURAL:$2|grupo|grupos}} à própria conta: $1',
'listgrouprights-removegroup-self'     => 'Pode remover {{PLURAL:$2|grupo|grupos}} da própria conta: $1',
'listgrouprights-addgroup-self-all'    => 'Pode adicionar todos os grupos à própria conta',
'listgrouprights-removegroup-self-all' => 'Pode remover todos os grupos da própria conta',

# E-mail user
'mailnologin'      => 'Nenhum endereço de envio',
'mailnologintext'  => 'Necessita de estar [[Special:UserLogin|autenticado]] e de possuir um endereço de e-mail válido nas suas [[Special:Preferences|preferências]] para poder enviar um e-mail a outros utilizadores.',
'emailuser'        => 'Contactar este utilizador',
'emailpage'        => 'Contactar utilizador',
'emailpagetext'    => 'Utilize o formulário abaixo para enviar uma mensagem a este utilizador.
O endereço que você introduziu nas [[Special:Preferences|suas preferências]] irá aparecer no campo "Remetente" do e-mail, para que o destinatário lhe possa responder directamente.',
'usermailererror'  => 'Objecto de correio retornou um erro:',
'defemailsubject'  => 'E-mail: {{SITENAME}}',
'noemailtitle'     => 'Sem endereço de e-mail',
'noemailtext'      => 'Este utilizador não especificou um endereço de e-mail válido.',
'nowikiemailtitle' => 'E-mail não permitido',
'nowikiemailtext'  => 'Este utilizador optou por não receber e-mail de outros utilizadores.',
'email-legend'     => 'Enviar e-mail para outro utilizador da {{SITENAME}}',
'emailfrom'        => 'De:',
'emailto'          => 'Para:',
'emailsubject'     => 'Assunto:',
'emailmessage'     => 'Mensagem:',
'emailsend'        => 'Enviar',
'emailccme'        => 'Enviar ao meu e-mail uma cópia de minha mensagem.',
'emailccsubject'   => 'Cópia de sua mensagem para $1: $2',
'emailsent'        => 'E-mail enviado',
'emailsenttext'    => 'A sua mensagem foi enviada.',
'emailuserfooter'  => 'Este e-mail foi enviado por $1 para $2 através da opção de "contactar utilizador" da {{SITENAME}}.',

# Watchlist
'watchlist'            => 'Páginas vigiadas',
'mywatchlist'          => 'Páginas vigiadas',
'watchlistfor'         => "(para '''$1''')",
'nowatchlist'          => 'A sua lista de vigiados não possui títulos.',
'watchlistanontext'    => 'Por favor, $1 para ver ou editar os títulos na sua lista de vigiados.',
'watchnologin'         => 'Não está autenticado',
'watchnologintext'     => 'Você precisa estar [[Special:UserLogin|autenticado]] para modificar a sua lista de vigiados.',
'addedwatch'           => 'Adicionado à lista',
'addedwatchtext'       => "A página \"[[:\$1]]\" foi adicionada à sua [[Special:Watchlist|lista de vigiados]].
Modificações futuras em tal página e páginas de discussão a ela associadas serão listadas lá, com a página aparecendo a '''negrito''' na [[Special:RecentChanges|lista de mudanças recentes]], para que possa encontrá-la com maior facilidade.",
'removedwatch'         => 'Removida da lista de vigiados',
'removedwatchtext'     => 'A página "[[:$1]]" foi removida de [[Special:Watchlist|sua lista de vigiados]].',
'watch'                => 'Vigiar',
'watchthispage'        => 'Vigiar esta página',
'unwatch'              => 'Desinteressar-se',
'unwatchthispage'      => 'Parar de vigiar esta página',
'notanarticle'         => 'Não é uma página de conteúdo',
'notvisiblerev'        => 'Edição eliminada',
'watchnochange'        => 'Nenhum dos elementos vigiados foi editado durante o período considerado.',
'watchlist-details'    => 'Existem $1 {{PLURAL:$1|página|páginas}} na sua lista de vigiados, excluindo páginas de discussão.',
'wlheader-enotif'      => '* A notificação por email encontra-se activada.',
'wlheader-showupdated' => "* As páginas modificadas desde a sua última visita são mostradas a '''negrito'''.",
'watchmethod-recent'   => 'verificando edições recentes para os vigiados',
'watchmethod-list'     => 'verificando páginas vigiadas para edições recentes',
'watchlistcontains'    => 'A sua lista de vigiados contém $1 {{PLURAL:$1|página|páginas}}.',
'iteminvalidname'      => "Problema com item '$1', nome inválido...",
'wlnote'               => "A seguir {{PLURAL:$1|está a última alteração ocorrida|estão as últimas '''$1''' alterações ocorridas}} {{PLURAL:$2|na última hora|nas últimas '''$2''' horas}}.",
'wlshowlast'           => 'Ver últimas $1 horas $2 dias $3',
'watchlist-options'    => 'Opções da lista de vigiados',

# Displayed when you click the "watch" button and it is in the process of watching
'watching'   => 'Vigiando...',
'unwatching' => 'Deixando de vigiar...',

'enotif_mailer'                => '{{SITENAME}} Correio de Notificação',
'enotif_reset'                 => 'Marcar todas as páginas como visitadas',
'enotif_newpagetext'           => 'Esta é uma página nova.',
'enotif_impersonal_salutation' => 'Utilizador do projeto "{{SITENAME}}"',
'changed'                      => 'alterada',
'created'                      => 'criada',
'enotif_subject'               => '{{SITENAME}}: A página $PAGETITLE foi $CHANGEDORCREATED por $PAGEEDITOR',
'enotif_lastvisited'           => 'Consulte $1 para todas as alterações efectuadas desde a sua última visita.',
'enotif_lastdiff'              => 'Acesse $1 para ver esta alteração.',
'enotif_anon_editor'           => 'utilizador anonimo $1',
'enotif_body'                  => 'Caro $WATCHINGUSERNAME,


A página $PAGETITLE na {{SITENAME}} foi $CHANGEDORCREATED a $PAGEEDITDATEANDTIME por $PAGEEDITOR; consulte $PAGETITLE_URL para a versão atual.

$NEWPAGE

Sumário de edição: $PAGESUMMARY $PAGEMINOREDIT

Contacte o editor:
e-mail: $PAGEEDITOR_EMAIL
wiki: $PAGEEDITOR_WIKI

Não haverá mais notificações no caso de futuras alterações a não ser que visite esta página. Poderá também restaurar as bandeiras de notificação para todas as suas páginas vigiadas na sua lista de vigiados.

             O seu amigável sistema de notificação de {{SITENAME}}

--
Para alterar as suas preferências da lista de vigiados, visite
{{fullurl:Special:Watchlist/edit}}

Contacto e assistência:
{{fullurl:{{MediaWiki:Helppage}}}}',

# Delete
'deletepage'             => 'Eliminar página',
'confirm'                => 'Confirmar',
'excontent'              => "o conteúdo era: '$1'",
'excontentauthor'        => "o conteúdo era: '$1' (e o único editor era '[[Special:Contributions/$2|$2]]')",
'exbeforeblank'          => "o conteúdo antes de esvaziar era: '$1'",
'exblank'                => 'página esvaziada',
'delete-confirm'         => 'Eliminar "$1"',
'delete-legend'          => 'Eliminar',
'historywarning'         => "'''Aviso:''' A página que está prestes a eliminar possui um histórico com $1 {{PLURAL:$1|revisão|revisões}}:",
'confirmdeletetext'      => 'Encontra-se prestes a eliminar permanentemente uma página ou uma imagem e todo o seu histórico.
Por favor, confirme que possui a intenção de fazer isto, que compreende as consequências e que encontra-se a fazer isto de acordo com as [[{{MediaWiki:Policy-url}}|políticas]] do projecto.',
'actioncomplete'         => 'Acção completada',
'actionfailed'           => 'A ação falhou',
'deletedtext'            => '"<nowiki>$1</nowiki>" foi eliminada.
Consulte $2 para um registo de eliminações recentes.',
'deletedarticle'         => 'eliminou "[[$1]]"',
'suppressedarticle'      => 'suprimiu "[[$1]]"',
'dellogpage'             => 'Registo de eliminação',
'dellogpagetext'         => 'Abaixo uma lista das eliminações mais recentes.',
'deletionlog'            => 'registo de eliminação',
'reverted'               => 'Revertido para versão anterior',
'deletecomment'          => 'Motivo de eliminação',
'deleteotherreason'      => 'Outro/motivo adicional:',
'deletereasonotherlist'  => 'Outro motivo',
'deletereason-dropdown'  => '* Motivos de eliminação comuns
** Pedido do autor
** Violação de direitos de autor
** Vandalismo',
'delete-edit-reasonlist' => 'Editar motivos de eiliminação',
'delete-toobig'          => 'Esta página possui um longo histórico de edições, com mais de $1 {{PLURAL:$1|edição|edições}}.
A eliminação de tais páginas foi restrita, a fim de se evitarem problemas acidentais em {{SITENAME}}.',
'delete-warning-toobig'  => 'Esta página possui um longo histórico de edições, com mais de $1 {{PLURAL:$1|edição|edições}}.
Eliminá-la poderá causar problemas na base de dados de {{SITENAME}};
prossiga com cuidado.',

# Rollback
'rollback'          => 'Reverter edições',
'rollback_short'    => 'Voltar',
'rollbacklink'      => 'voltar',
'rollbackfailed'    => 'A reversão falhou',
'cantrollback'      => 'Não foi possível reverter a edição; o último contribuidor é o único autor desta página',
'alreadyrolled'     => 'Não foi possível reverter as edições de [[:$1]] por [[User:$2|$2]] ([[User talk:$2|discussão]]{{int:pipe-separator}}[[Special:Contributions/$2|{{int:contribslink}}]]);
alguém editou ou já reverteu a página.

A última edição foi de [[User:$3|$3]] ([[User talk:$3|discussão]]{{int:pipe-separator}}[[Special:Contributions/$3|{{int:contribslink}}]]).',
'editcomment'       => "O sumário de edição era: \"''\$1''\".",
'revertpage'        => 'Foram revertidas as edições de [[Special:Contributions/$2|$2]] ([[User talk:$2|disc]]) para a última versão por [[User:$1|$1]]',
'revertpage-nouser' => 'Revertidas as edições de (nome de usuário removido) para a última revisão por [[User:$1|$1]]',
'rollback-success'  => 'Foram revertidas as edições de $1, com o conteúdo passando a estar como na última edição de $2.',
'sessionfailure'    => 'Foram detectados problemas com a sua sessão;
Esta acção foi cancelada como medida de protecção contra a intercepção de sessões.
Experimente usar o botão "Voltar" e recarregar a página de onde veio e tente novamente.',

# Protect
'protectlogpage'              => 'Registo de protecção',
'protectlogtext'              => 'Abaixo encontra-se o registo de protecção e desprotecção de páginas.
Veja a [[Special:ProtectedPages|lista de páginas protegidas]] para uma listagem das páginas que se encontram protegidas no momento.',
'protectedarticle'            => 'protegeu "[[$1]]"',
'modifiedarticleprotection'   => 'alterou o nível de protecção para "[[$1]]"',
'unprotectedarticle'          => 'desprotegeu "[[$1]]"',
'movedarticleprotection'      => 'moveu as configurações de proteção de "[[$2]]" para "[[$1]]"',
'protect-title'               => 'Protegendo "$1"',
'prot_1movedto2'              => 'moveu [[$1]] para [[$2]]',
'protect-legend'              => 'Confirmar protecção',
'protectcomment'              => 'Motivo:',
'protectexpiry'               => 'Expiração',
'protect_expiry_invalid'      => 'O tempo de expiração fornecido é inválido.',
'protect_expiry_old'          => 'O tempo de expiração fornecido se situa no passado.',
'protect-unchain'             => 'Desbloquear permissões de moção',
'protect-text'                => "Você pode, nesta página, alterar o nível de proteção para '''<nowiki>$1</nowiki>'''.",
'protect-locked-blocked'      => "Você não poderá alterar os níveis de proteção enquanto estiver bloqueado. Esta é a configuração atual para a página '''$1''':",
'protect-locked-dblock'       => "Não é possível alterar os níveis de proteção, uma vez que a base de dados se encontra trancada.
Esta é a configuração atual para a página '''$1''':",
'protect-locked-access'       => "Sua conta não possui permissões para alterar os níveis de proteção de uma página.
Esta é a configuração atual para a página '''$1''':",
'protect-cascadeon'           => 'Esta página encontra-se protegida, uma vez que se encontra incluída {{PLURAL:$1|na página listada a seguir, protegida|nas páginas listadas a seguir, protegidas}} com a "protecção progressiva" activada. Você poderá alterar o nível de protecção desta página, mas isso não afectará a "protecção progressiva".',
'protect-default'             => 'Permitir todos os utilizadores',
'protect-fallback'            => 'É necessário o privilégio de "$1"',
'protect-level-autoconfirmed' => 'Bloquear utilizadores novos e não registados',
'protect-level-sysop'         => 'Apenas administradores',
'protect-summary-cascade'     => 'p. progressiva',
'protect-expiring'            => 'expira em $1 (UTC)',
'protect-expiry-indefinite'   => 'infinito',
'protect-cascade'             => 'Proteja quaisquer páginas que estejam incluídas nesta (proteção progressiva)',
'protect-cantedit'            => 'Você não pode alterar o nível de proteção desta página uma vez que você não se encontra habilitado a editá-la.',
'protect-othertime'           => 'Outra duração:',
'protect-othertime-op'        => 'outra duração',
'protect-existing-expiry'     => 'A proteção atual expirará às $3 de $2',
'protect-otherreason'         => 'Outro motivo/motivo adicional:',
'protect-otherreason-op'      => 'outro/motivo adicional',
'protect-dropdown'            => '*Motivos comuns para proteção
** Vandalismo excessivo
** Inserção excessiva de spams
** Guerra de edições improdutiva
** Página bastante acessada',
'protect-edit-reasonlist'     => 'Editar motivos de proteções',
'protect-expiry-options'      => '1 hora:1 hour,1 dia:1 day,1 semana:1 week,2 semanas:2 weeks,1 mês:1 month,3 meses:3 months,6 meses:6 months,1 ano:1 year,indefinido:infinite',
'restriction-type'            => 'Permissão:',
'restriction-level'           => 'Nível de restrição:',
'minimum-size'                => 'Tam. mínimo',
'maximum-size'                => 'Tam. máximo:',
'pagesize'                    => '(bytes)',

# Restrictions (nouns)
'restriction-edit'   => 'Editar',
'restriction-move'   => 'Mover',
'restriction-create' => 'Criar',
'restriction-upload' => 'Carregar',

# Restriction levels
'restriction-level-sysop'         => 'totalmente protegida',
'restriction-level-autoconfirmed' => 'semi-protegida',
'restriction-level-all'           => 'qualquer nível',

# Undelete
'undelete'                     => 'Ver páginas eliminadas',
'undeletepage'                 => 'Ver e restaurar páginas eliminadas',
'undeletepagetitle'            => "'''Seguem-se as edições eliminadas de [[:$1]]'''.",
'viewdeletedpage'              => 'Ver páginas eliminadas',
'undeletepagetext'             => '{{PLURAL:$1|A seguinte página foi eliminada|As seguintes páginas foram eliminadas}}, mas ainda {{PLURAL:$1|permanece|permanecem}} na base de dados e poderem ser restauradas. O arquivo pode ser limpo periodicamente.',
'undelete-fieldset-title'      => 'Restaurar edições',
'undeleteextrahelp'            => "Para restaurar o histórico de edições completo desta página, deixe todas as caixas de selecção desseleccionadas e clique em '''''Restaurar'''''.
Para efectuar uma restauração selectiva, seleccione as caixas correspondentes às edições a serem restauradas e clique em '''''Restaurar'''''.
Clicar em '''''Limpar''''' irá limpar o campo de comentário e todas as caixas de selecção.",
'undeleterevisions'            => '$1 {{PLURAL:$1|edição disponível|edições disponíveis}}',
'undeletehistory'              => 'Se restaurar uma página, todas as edições serão restauradas para o histórico.
Se uma nova página foi criada com o mesmo nome desde a eliminação, as edições restauradas aparecerão no histórico anterior.',
'undeleterevdel'               => 'O restauro não será executado se resultar na remoção parcial da versão mais recente da página ou ficheiro.
Em tais casos, deverá desseleccionar ou reverter a ocultação da versão apagada mais recente.',
'undeletehistorynoadmin'       => 'Esta página foi eliminada. O motivo de eliminação é apresentado no súmario abaixo, junto dos detalhes do utilizador que editou esta página antes de eliminar. O texto actual destas edições eliminadas encontra-se agora apenas disponível para administradores.',
'undelete-revision'            => 'Edição eliminada da página $1 (das $5 de $4), por $3:',
'undeleterevision-missing'     => 'Edição inválida ou não encontrada. Talvez você esteja com um link incorrecto ou talvez a edição foi restaurada ou removida dos arquivos.',
'undelete-nodiff'              => 'Não foram encontradas edições anteriores.',
'undeletebtn'                  => 'Restaurar',
'undeletelink'                 => 'ver/restaurar',
'undeleteviewlink'             => 'visualizar',
'undeletereset'                => 'Limpar',
'undeleteinvert'               => 'Inverter selecção',
'undeletecomment'              => 'Comentário:',
'undeletedarticle'             => 'restaurado "[[$1]]"',
'undeletedrevisions'           => '$1 {{PLURAL:$1|edição restaurada|edições restauradas}}',
'undeletedrevisions-files'     => '$1 {{PLURAL:$2|edição restaurada|edições restauradas}} e $2 {{PLURAL:$2|ficheiro restaurado|ficheiros restaurados}}',
'undeletedfiles'               => '{{PLURAL:$1|ficheiro restaurado|$1 ficheiros restaurados}}',
'cannotundelete'               => 'Restauração falhada; alguém talvez já restaurou a página.',
'undeletedpage'                => "<big>'''$1 foi restaurada'''</big>

Consulte o [[Special:Log/delete|registo de eliminações]] para um registo das eliminações e restaurações mais recentes.",
'undelete-header'              => 'Veja o [[Special:Log/delete|registo de deleções]] para as páginas recentemente eliminadas.',
'undelete-search-box'          => 'Pesquisar páginas eliminadas',
'undelete-search-prefix'       => 'Exibir páginas que iniciem com:',
'undelete-search-submit'       => 'Pesquisar',
'undelete-no-results'          => 'Não foram encontradas edições relacionadas com o que foi buscado no arquivo de edições eliminadas.',
'undelete-filename-mismatch'   => 'Não foi possível restaurar a versão do ficheiro de $1: nome de ficheiro não combina',
'undelete-bad-store-key'       => 'Não foi possível restaurar a versão do ficheiro de $1: já não existia antes da eliminação.',
'undelete-cleanup-error'       => 'Erro ao eliminar o ficheiro não utilizado "$1".',
'undelete-missing-filearchive' => 'Não é possível restaurar o ficheiro de ID $1, uma vez que ele não se encontra na base de dados. Isso pode significar que já tenha sido restaurado.',
'undelete-error-short'         => 'Erro ao restaurar ficheiro: $1',
'undelete-error-long'          => 'Foram encontrados erros ao tentar restaurar o ficheiro:

$1',
'undelete-show-file-confirm'   => 'Você tem certeza de que deseja visualizar a versão eliminada de "<nowiki>$1</nowiki>" das $3 de $2?',
'undelete-show-file-submit'    => 'Sim',

# Namespace form on various pages
'namespace'      => 'Espaço nominal:',
'invert'         => 'Inverter selecção',
'blanknamespace' => '(Principal)',

# Contributions
'contributions'       => 'Contribuições do utilizador',
'contributions-title' => 'Contribuições {{GENDER:$1|do utilizador|da utilizadora}} $1',
'mycontris'           => 'Minhas contribuições',
'contribsub2'         => 'Para $1 ($2)',
'nocontribs'          => 'Não foram encontradas mudanças com este critério.',
'uctop'               => ' (edição actual)',
'month'               => 'Mês (inclusive anteriores):',
'year'                => 'Ano (inclusive anteriores):',

'sp-contributions-newbies'        => 'Pesquisar apenas nas contribuições de contas recentes',
'sp-contributions-newbies-sub'    => 'Para contas novas',
'sp-contributions-newbies-title'  => 'Contribuições de contas novas',
'sp-contributions-blocklog'       => 'Registo de bloqueios',
'sp-contributions-deleted'        => 'Contribuições de utilizador eliminadas',
'sp-contributions-logs'           => 'registos',
'sp-contributions-talk'           => 'disc',
'sp-contributions-userrights'     => 'Gestão de privilégios de utilizadores',
'sp-contributions-blocked-notice' => 'Este utilizador encontra-se atualmente bloqueado. O último registo de bloqueio é exibido abaixo:',
'sp-contributions-search'         => 'Pesquisar contribuições',
'sp-contributions-username'       => 'Endereço de IP ou utilizador:',
'sp-contributions-submit'         => 'Pesquisar',

# What links here
'whatlinkshere'            => 'Páginas afluentes',
'whatlinkshere-title'      => 'Páginas que apontam para "$1"',
'whatlinkshere-page'       => 'Página:',
'linkshere'                => "As seguintes páginas possuem ligações para '''[[:$1]]''':",
'nolinkshere'              => "Não existem ligações para '''[[:$1]]'''.",
'nolinkshere-ns'           => "Não há links para '''[[:$1]]''' no espaço nominal selecionado.",
'isredirect'               => 'página de redireccionamento',
'istemplate'               => 'inclusão',
'isimage'                  => 'link de imagem',
'whatlinkshere-prev'       => '{{PLURAL:$1|anterior|$1 anteriores}}',
'whatlinkshere-next'       => '{{PLURAL:$1|próximo|próximos $1}}',
'whatlinkshere-links'      => '← links',
'whatlinkshere-hideredirs' => '$1 redireccionamentos',
'whatlinkshere-hidetrans'  => '$1 transclusões',
'whatlinkshere-hidelinks'  => '$1 ligações',
'whatlinkshere-hideimages' => '$1 links de imagens',
'whatlinkshere-filters'    => 'Filtros',

# Block/unblock
'blockip'                         => 'Bloquear utilizador',
'blockip-legend'                  => 'Bloquear utilizador',
'blockiptext'                     => 'Utilize o formulário abaixo para bloquear o acesso à escrita de um endereço específico de IP ou nome de utilizador.
Isto só deve ser feito para prevenir vandalismo, e de acordo com a [[{{MediaWiki:Policy-url}}|política]]. Preencha com um motivo específico a seguir (por exemplo, citando páginas que sofreram vandalismo).',
'ipaddress'                       => 'Endereço de IP:',
'ipadressorusername'              => 'Endereço de IP ou nome de utilizador:',
'ipbexpiry'                       => 'Expiração:',
'ipbreason'                       => 'Motivo:',
'ipbreasonotherlist'              => 'Outro motivo',
'ipbreason-dropdown'              => '*Razões comuns para um bloqueio
** Inserindo informações falsas
** Removendo o conteúdo de páginas
** Fazendo "spam" de sítios externos
** Inserindo conteúdo sem sentido/incompreensível nas páginas
** Comportamento intimidador/inoportuno
** Uso abusivo de contas múltiplas
** Nome de utilizador inaceitável',
'ipbanononly'                     => 'Bloquear apenas utilizadores anónimos',
'ipbcreateaccount'                => 'Prevenir criação de conta de utilizador',
'ipbemailban'                     => 'Impedir utilizador de enviar e-mail',
'ipbenableautoblock'              => 'Bloquear automaticamente o endereço de IP mais recente usado por este utilizador e todos os IPs subseqüentes dos quais ele tentar editar',
'ipbsubmit'                       => 'Bloquear este utilizador',
'ipbother'                        => 'Outro período:',
'ipboptions'                      => '2 horas:2 hours,1 dia:1 day,3 dias:3 days,1 semana:1 week,2 semanas:2 weeks,1 mês:1 month,3 meses:3 months,6 meses:6 months,1 ano:1 year,indefinido:infinite',
'ipbotheroption'                  => 'outro',
'ipbotherreason'                  => 'Outro motivo/motivo adicional:',
'ipbhidename'                     => 'Ocultar nome de utilizador em edições e listas',
'ipbwatchuser'                    => 'Vigiar as páginas de utilizador e de discussão deste utilizador',
'ipballowusertalk'                => 'Permitir que este utilizador edite sua própria página de discussão mesmo estando bloqueado',
'ipb-change-block'                => 'Re-bloquear o utilizador com estes parâmetros',
'badipaddress'                    => 'Endereço de IP inválido',
'blockipsuccesssub'               => 'Bloqueio bem sucedido',
'blockipsuccesstext'              => '[[Special:Contributions/$1|$1]] foi bloqueado.<br />
Consulte a [[Special:IPBlockList|lista de IPs bloqueados]] para rever os bloqueios.',
'ipb-edit-dropdown'               => 'Editar motivos de bloqueio',
'ipb-unblock-addr'                => 'Desbloquear $1',
'ipb-unblock'                     => 'Desbloquear um utilizador ou endereço de IP',
'ipb-blocklist-addr'              => 'Bloqueios em vigência para $1',
'ipb-blocklist'                   => 'Ver bloqueios em vigência',
'ipb-blocklist-contribs'          => 'Contribuições de $1',
'unblockip'                       => 'Desbloquear utilizador',
'unblockiptext'                   => 'Utilize o formulário a seguir para restaurar o acesso à escrita para um endereço de IP ou utilizador previamente bloqueado.',
'ipusubmit'                       => 'Remover este bloqueio',
'unblocked'                       => '[[User:$1|$1]] foi desbloqueado',
'unblocked-id'                    => 'O bloqueio de $1 foi removido com sucesso',
'ipblocklist'                     => 'Utilizadores e endereços de IP bloqueados',
'ipblocklist-legend'              => 'Procurar por um utilizador bloqueado',
'ipblocklist-username'            => 'Utilizador ou endereço de IP:',
'ipblocklist-sh-userblocks'       => '$1 bloqueios de contas',
'ipblocklist-sh-tempblocks'       => '$1 bloqueios temporários',
'ipblocklist-sh-addressblocks'    => '$1 bloqueios de IP único',
'ipblocklist-submit'              => 'Pesquisar',
'blocklistline'                   => '$1, $2 bloqueou $3 ($4)',
'infiniteblock'                   => 'infinito',
'expiringblock'                   => 'expira em $1 às $2',
'anononlyblock'                   => 'anón. apenas',
'noautoblockblock'                => 'bloqueio automático desabilitado',
'createaccountblock'              => 'criação de conta de utilizador bloqueada',
'emailblock'                      => 'impedido de enviar e-mail',
'blocklist-nousertalk'            => 'impossibilitado de editar a própria página de discussão',
'ipblocklist-empty'               => 'A lista de bloqueios encontra-se vazia.',
'ipblocklist-no-results'          => 'O endereço de IP ou nome de utilizador procurado não se encontra bloqueado.',
'blocklink'                       => 'bloquear',
'unblocklink'                     => 'desbloquear',
'change-blocklink'                => 'alterar bloqueio',
'contribslink'                    => 'contribs',
'autoblocker'                     => 'Você foi automaticamente bloqueado, pois partilha um endereço de IP com "[[User:$1|$1]]". O motivo apresentado foi: "$2".',
'blocklogpage'                    => 'Registo de bloqueio',
'blocklog-showlog'                => 'Este usuário foi já bloqueado anteriormente.
O registo de bloqueio é mostrado abaixo para referência:',
'blocklog-showsuppresslog'        => 'Este utilizador foi bloqueado e ocultado anteriomente.
O registo de supressão é fornecido abaixo para referência:',
'blocklogentry'                   => '"[[$1]]" foi bloqueado com um tempo de expiração de $2 $3',
'reblock-logentry'                => 'modificou parâmetros de bloqueio de [[$1]] com expiração em $2 $3',
'blocklogtext'                    => 'Este é um registo de acções de bloqueio e desbloqueio.
Endereços IP sujeitos a bloqueio automático não são listados.
Consulte a [[Special:IPBlockList|lista de IPs bloqueados]] para obter a lista de bloqueios e banimentos actualmente válidos.',
'unblocklogentry'                 => 'desbloqueou $1',
'block-log-flags-anononly'        => 'apenas utilizadores anónimos',
'block-log-flags-nocreate'        => 'criação de contas desabilitada',
'block-log-flags-noautoblock'     => 'bloqueio automático desabilitado',
'block-log-flags-noemail'         => 'impedido de enviar e-mail',
'block-log-flags-nousertalk'      => 'impossibilitado de editar a própria página de discussão',
'block-log-flags-angry-autoblock' => 'autobloqueio melhorado activado',
'block-log-flags-hiddenname'      => 'nome de utilizador ocultado',
'range_block_disabled'            => 'A funcionalidade de bloquear gamas de IPs encontra-se desactivada.',
'ipb_expiry_invalid'              => 'Tempo de expiração inválido.',
'ipb_expiry_temp'                 => 'Bloqueios com nome de utilizador ocultado devem ser permanentes.',
'ipb_hide_invalid'                => 'Não foi possível suprimir esta conta; ela poderá ter demasiadas edições.',
'ipb_already_blocked'             => '"$1" já se encontra bloqueado',
'ipb-needreblock'                 => '== Já se encontra bloqueado ==
$1 já se encontra bloqueado. Deseja alterar as configurações?',
'ipb_cant_unblock'                => 'Erro: Bloqueio com ID $1 não encontrado. Poderá já ter sido desbloqueado.',
'ipb_blocked_as_range'            => 'Erro: O IP $1 não se encontra bloqueado de forma direta, não podendo ser desbloqueado deste modo. Se encontra bloqueado como parte do "range" $2, o qual pode ser desbloqueado.',
'ip_range_invalid'                => 'Gama de IPs inválida.',
'blockme'                         => 'Bloquear-me',
'proxyblocker'                    => 'Bloqueador de proxy',
'proxyblocker-disabled'           => 'Esta função está desabilitada.',
'proxyblockreason'                => 'O seu endereço de IP foi bloqueado por ser um proxy público. Por favor contacte o seu fornecedor do serviço de Internet ou o apoio técnico e informe-os deste problema de segurança grave.',
'proxyblocksuccess'               => 'Concluído.',
'sorbsreason'                     => 'O seu endereço IP encontra-se listado como proxy aberto pela DNSBL utilizada por {{SITENAME}}.',
'sorbs_create_account_reason'     => 'O seu endereço de IP encontra-se listado como proxy aberto na DNSBL utilizada por {{SITENAME}}. Você não pode criar uma conta',
'cant-block-while-blocked'        => 'Você não pode bloquear outros utilizadores enquanto estiver bloqueado.',
'cant-see-hidden-user'            => 'O utilizador que você está tentando bloquear já está bloqueado ou oculto. Como você não possui privilégio de ocultar utilizadores, você não pode ver ou editar o bloqueio desse utilizador.',

# Developer tools
'lockdb'              => 'Trancar base de dados',
'unlockdb'            => 'Destrancar base de dados',
'lockdbtext'          => 'Trancar a base de dados suspenderá a habilidade de todos os utilizadores de editarem páginas, mudarem suas preferências, lista de vigiados e outras coisas que requerem mudanças na base de dados.
Por favor, confirme que você realmente pretende fazer isso e que vai destrancar a base de dados quando a manutenção estiver concluída.',
'unlockdbtext'        => 'Desbloquear a base de dados vai restaurar a habilidade de todos os utilizadores de editarem páginas,  mudarem suas preferências, alterarem suas listas de vigiados e outras coisas que requerem mudanças na base de dados.
Por favor, confirme que realmente pretende fazer isso.',
'lockconfirm'         => 'Sim, eu realmente desejo bloquear a base de dados.',
'unlockconfirm'       => 'Sim, eu realmente desejo desbloquear a base de dados.',
'lockbtn'             => 'Bloquear base de dados',
'unlockbtn'           => 'Desbloquear base de dados',
'locknoconfirm'       => 'Você não seleccionou a caixa de confirmação.',
'lockdbsuccesssub'    => 'Bloqueio bem sucedido',
'unlockdbsuccesssub'  => 'Desbloqueio bem sucedido',
'lockdbsuccesstext'   => 'A base de dados da {{SITENAME}} foi bloqueada.<br />
Lembre-se de [[Special:UnlockDB|remover o bloqueio]] após a manutenção.',
'unlockdbsuccesstext' => 'A base de dados foi desbloqueada.',
'lockfilenotwritable' => 'O ficheiro de bloqueio da base de dados não pode ser escrito. Para bloquear ou desbloquear a base de dados, este precisa de poder ser escrito pelo servidor Web.',
'databasenotlocked'   => 'A base de dados não encontra-se bloqueada.',

# Move page
'move-page'                    => 'Mover $1',
'move-page-legend'             => 'Mover página',
'movepagetext'                 => "Utilizando o seguinte formulário você poderá renomear uma página, movendo todo o histórico de edições para o novo título.
É possível corrigir de forma automática redirecionamentos que apontem para o título original.
Caso escolha para que isso não seja feito, certifique-se de verificar redirecionamentos [[Special:DoubleRedirects|duplos]] ou [[Special:BrokenRedirects|quebrados]].
É de sua responsabilidade ter certeza de que os links continuem apontando para onde se é suposto apontar.

Note que a página '''não''' será movida se já existir uma página com o novo título, a não ser que ele esteja vazio ou seja um redirecionamento e não tenha histórico de edições.
Isto significa que pode renomear uma página de volta para o nome que tinha anteriormente se cometer algum engano, e que não pode sobrescrever uma página.

'''CUIDADO!'''
Isto pode ser uma mudança drástica e inesperada para uma página popular;
por favor, tenha certeza de que compreende as conseqüências da mudança antes de prosseguir.",
'movepagetalktext'             => "A página de \"discussão\" associada, se existir, será automaticamente movida, '''a não ser que:'''
*Uma página de discussão com conteúdo já exista sob o novo título, ou
*Você não marque a caixa abaixo.

Nestes casos, você terá que mover ou mesclar a página manualmente, se assim desejar.",
'movearticle'                  => 'Mover página',
'movenologin'                  => 'Não autenticado',
'movenologintext'              => 'Você precisa ser um utilizador registado e [[Special:UserLogin|autenticado]] para poder mover uma página.',
'movenotallowed'               => 'Você não possui permissão de mover páginas.',
'movenotallowedfile'           => 'Você não possui permissão de mover ficheiros.',
'cant-move-user-page'          => 'Você não possui permissão de mover páginas principais de usuários.',
'cant-move-to-user-page'       => 'Você não tem permissão para mover uma página para uma página de utilizador (excepto para uma subpágina de utilizador).',
'newtitle'                     => 'Para novo título',
'move-watch'                   => 'Vigiar esta página',
'movepagebtn'                  => 'Mover página',
'pagemovedsub'                 => 'Página movida com sucesso',
'movepage-moved'               => '<big>\'\'\'"$1" foi movida para "$2"\'\'\'</big>',
'movepage-moved-redirect'      => 'Um redireccionamento foi criado.',
'movepage-moved-noredirect'    => 'A criação de um redirecionamento foi suprimida.',
'articleexists'                => 'Uma página com este título já existe, ou o título que escolheu é inválido.
Por favor, escolha outro nome.',
'cantmove-titleprotected'      => 'Você não pode mover uma página para tal denominação uma vez que o novo título se encontra protegido contra criação',
'talkexists'                   => "'''A página em si foi movida com sucesso. No entanto, a página de discussão não foi movida, uma vez que já existia uma com este título. Por favor, mescle-as manualmente.'''",
'movedto'                      => 'movido para',
'movetalk'                     => 'Mover também a página de discussão associada.',
'move-subpages'                => 'Mover subpáginas (até $1)',
'move-talk-subpages'           => 'Mover subpáginas da página de discussão (até $1)',
'movepage-page-exists'         => 'A página $1 já existe e não pode ser substituída.',
'movepage-page-moved'          => 'A página $1 foi movida para $2',
'movepage-page-unmoved'        => 'A página $1 não pôde ser movida para $2.',
'movepage-max-pages'           => 'O limite de $1 {{PLURAL:$1|página movida|páginas movidas}} foi atingido; não será possível mover mais páginas de forma automática.',
'1movedto2'                    => 'moveu [[$1]] para [[$2]]',
'1movedto2_redir'              => 'moveu [[$1]] para [[$2]] sobre redirecionamento',
'move-redirect-suppressed'     => 'redirecionamento suprimido',
'movelogpage'                  => 'Registo de movimento',
'movelogpagetext'              => 'Abaixo encontra-se uma lista de páginas movidas.',
'movesubpage'                  => '{{PLURAL:$1|Sub-página|Sub-páginas}}',
'movesubpagetext'              => 'Esta página tem $1 {{PLURAL:$1|sub-página mostrada|sub-páginas mostradas}} abaixo.',
'movenosubpage'                => 'Esta página não tem subpáginas.',
'movereason'                   => 'Motivo:',
'revertmove'                   => 'reverter',
'delete_and_move'              => 'Eliminar e mover',
'delete_and_move_text'         => '==Eliminação necessária==
A página de destino ("[[:$1]]") já existe. Deseja eliminá-la de modo a poder mover?',
'delete_and_move_confirm'      => 'Sim, eliminar a página',
'delete_and_move_reason'       => 'Eliminada para poder mover outra página para este título',
'selfmove'                     => 'O título de origem e de destinato são os mesmos; 
não é possível mover uma página para ela mesma.',
'immobile-source-namespace'    => 'Não é possível mover páginas no espaço nominal "$1"',
'immobile-target-namespace'    => 'Não é possível mover páginas para o espaço nominal "$1"',
'immobile-target-namespace-iw' => 'Uma ligação interwiki não é um destino válido para uma movimentação de página.',
'immobile-source-page'         => 'Esta página não pode ser movida.',
'immobile-target-page'         => 'Não é possível mover para esse título de destino.',
'imagenocrossnamespace'        => 'Não é possível mover imagem para espaço nominal que não de imagens',
'imagetypemismatch'            => 'A extensão do novo ficheiro não corresponde ao seu tipo',
'imageinvalidfilename'         => 'O nome do ficheiro alvo é inválido',
'fix-double-redirects'         => 'Atualizar todos os redirecionamentos que apontem para o título original',
'move-leave-redirect'          => 'Criar um redireccionamento',
'protectedpagemovewarning'     => "'''Aviso:''' Esta página foi protegida de maneira a que apenas utilizadores com privilégio de administrador possam movê-la.",
'semiprotectedpagemovewarning' => "''Nota:''' Esta página protegida de maneira a que apenas utilizadores registados possam movê-la.",
'move-over-sharedrepo'         => '== O ficheiro existe ==
[[:$1]] já existe num repositório partilhado. Mover um ficheiro para o título [[:$1]] irá sobrepô-lo ao ficheiro partilhado.',
'file-exists-sharedrepo'       => 'O nome de ficheiro que escolheu já é utilizado num repositório partilhado.
Por favor, escolha outro nome.',

# Export
'export'            => 'Exportação de páginas',
'exporttext'        => 'Você pode exportar o texto e o histórico de edições de uma página em particular para um ficheiro XML. Poderá então importar esse conteúdo noutra wiki que utilize o software MediaWiki através da [[Special:Import|página de importações]].

Para exportar páginas, introduza os títulos na caixa de texto abaixo (um título por linha) e seleccione se deseja todas as versões, com as linhas de histórico de edições, ou apenas a edição atual e informações apenas sobre a mais recente das edições.

Se desejar, pode utilizar uma ligação (por exemplo, [[{{#Special:Export}}/{{MediaWiki:Mainpage}}]] para a [[{{MediaWiki:Mainpage}}]]).',
'exportcuronly'     => 'Incluir apenas a edição actual, não o histórico inteiro',
'exportnohistory'   => "----
'''Nota:''' a exportação do histórico completo das páginas através deste formulário foi desactivada devido a motivos de performance.",
'export-submit'     => 'Exportar',
'export-addcattext' => 'Adicionar à listagem páginas da categoria:',
'export-addcat'     => 'Adicionar',
'export-addnstext'  => 'Adicionar páginas do espaço nominal:',
'export-addns'      => 'Adicionar',
'export-download'   => 'Oferecer para salvar como um ficheiro',
'export-templates'  => 'Incluir predefinições',
'export-pagelinks'  => 'Includir páginas ligadas até uma profundidade de:',

# Namespace 8 related
'allmessages'                   => 'Todas as mensagens de sistema',
'allmessagesname'               => 'Nome',
'allmessagesdefault'            => 'Texto padrão',
'allmessagescurrent'            => 'Texto actual',
'allmessagestext'               => 'Esta é uma lista de todas as mensagens de sistema disponíveis no espaço nominal {{ns:mediawiki}}.
Acesse [http://www.mediawiki.org/wiki/Localisation MediaWiki Localisation] e [http://translatewiki.net translatewiki.net] caso deseje contribuir para traduções do MediaWiki feitas para uso geral.',
'allmessagesnotsupportedDB'     => "Esta página não pode ser utilizada, uma vez que '''\$wgUseDatabaseMessages''' foi desativado.",
'allmessages-filter-legend'     => 'Filtro',
'allmessages-filter'            => 'Filtrar por estado de personalização:',
'allmessages-filter-unmodified' => 'Não modificado',
'allmessages-filter-all'        => 'Todas',
'allmessages-filter-modified'   => 'Modificado',
'allmessages-prefix'            => 'Filtrar por prefixo:',
'allmessages-language'          => 'Língua:',
'allmessages-filter-submit'     => 'Ir',

# Thumbnails
'thumbnail-more'           => 'Ampliar',
'filemissing'              => 'Ficheiro não encontrado',
'thumbnail_error'          => 'Erro ao criar miniatura: $1',
'djvu_page_error'          => 'página DjVu inacessível',
'djvu_no_xml'              => 'Não foi possível acessar o XML do ficheiro DjVU',
'thumbnail_invalid_params' => 'Parâmetros de miniatura inválidos',
'thumbnail_dest_directory' => 'Não foi possível criar o diretório de destino',
'thumbnail_image-type'     => 'Tipo de imagem não suportado',
'thumbnail_gd-library'     => 'Configuração de biblioteca GD incompleta: função $1 em falta',
'thumbnail_image-missing'  => 'Ficheiro em falta: $1',

# Special:Import
'import'                     => 'Importar páginas',
'importinterwiki'            => 'Importação transwiki',
'import-interwiki-text'      => 'Seleccione uma wiki e um título de página a importar.
As datas das edições e os seus editores serão mantidos.
Todas as acções de importação transwiki são registadas no [[Special:Log/import|Registo de importações]].',
'import-interwiki-source'    => 'Wiki/página fonte:',
'import-interwiki-history'   => 'Copiar todas as edições desta página',
'import-interwiki-templates' => 'Incluir todas as predefinições',
'import-interwiki-submit'    => 'Importar',
'import-interwiki-namespace' => 'Domínio de destino:',
'import-upload-filename'     => 'Nome do ficheiro:',
'import-comment'             => 'Comentário:',
'importtext'                 => 'Por favor, exporte o ficheiro da wiki de origem utilizando a ferramenta [[Special:Export|de exportar edições]] (Special:Export).
Salve o ficheiro para o seu disco e importe-o aqui.',
'importstart'                => 'Importando páginas...',
'import-revision-count'      => '{{PLURAL:$1|uma edição|$1 edições}}',
'importnopages'              => 'Não existem páginas a importar.',
'importfailed'               => 'A importação falhou: $1',
'importunknownsource'        => 'Tipo de fonte de importação desconhecida',
'importcantopen'             => 'Não foi possível abrir o ficheiro de importação',
'importbadinterwiki'         => 'Ligação de interwiki incorrecta',
'importnotext'               => 'Vazio ou sem texto',
'importsuccess'              => 'Importação completa!',
'importhistoryconflict'      => 'Existem conflitos de edições no histórico (talvez esta página já foi importada antes)',
'importnosources'            => 'Não foram definidas fontes de importação transwiki e o carregamento directo de históricos encontra-se desactivado.',
'importnofile'               => 'Nenhum ficheiro de importação foi carregado.',
'importuploaderrorsize'      => 'O envio do ficheiro a ser importado falhou. O ficheiro é maior do que o tamanho máximo permitido para upload.',
'importuploaderrorpartial'   => 'O envio do ficheiro a ser importado falhou. O ficheiro foi recebido parcialmente.',
'importuploaderrortemp'      => 'O envio do ficheiro a ser importado falhou. Não há um diretório temporário.',
'import-parse-failure'       => 'Falha ao importar dados XML',
'import-noarticle'           => 'Sem páginas para importar!',
'import-nonewrevisions'      => 'Todas as edições já haviam sido importadas.',
'xml-error-string'           => '$1 na linha $2, coluna $3 (byte $4): $5',
'import-upload'              => 'Enviar dados em XML',
'import-token-mismatch'      => 'Perda dos dados da sessão. Por favor tente novamente.',
'import-invalid-interwiki'   => 'Não é possível importar da wiki especificada.',

# Import log
'importlogpage'                    => 'Registo de importações',
'importlogpagetext'                => 'Importações administrativas de páginas com a preservação do histórico de edição de outras wikis.',
'import-logentry-upload'           => 'importou [[$1]] através de ficheiro de importação',
'import-logentry-upload-detail'    => '{{PLURAL:$1|uma edição|$1 edições}}',
'import-logentry-interwiki'        => 'transwiki $1',
'import-logentry-interwiki-detail' => '{{PLURAL:$1|$1 edição|$1 edições}} de $2',

# Tooltip help for the actions
'tooltip-pt-userpage'             => 'Sua página de utilizador',
'tooltip-pt-anonuserpage'         => 'A página de utilizador para o ip que está a utilizar para editar',
'tooltip-pt-mytalk'               => 'Sua página de discussão',
'tooltip-pt-anontalk'             => 'Discussão sobre edições deste endereço de IP',
'tooltip-pt-preferences'          => 'Minhas preferências',
'tooltip-pt-watchlist'            => 'A lista de páginas às quais você está monitorando alterações',
'tooltip-pt-mycontris'            => 'Lista das suas contribuições',
'tooltip-pt-login'                => 'Você é encorajado a autenticar-se, apesar disso não ser obrigatório.',
'tooltip-pt-anonlogin'            => 'Você é encorajado a autenticar-se, apesar disso não ser obrigatório.',
'tooltip-pt-logout'               => 'Sair',
'tooltip-ca-talk'                 => 'Discussão sobre o conteúdo da página',
'tooltip-ca-edit'                 => 'Você pode editar esta página. Por favor, utilize o botão Mostrar Previsão antes de salvar.',
'tooltip-ca-addsection'           => 'Iniciar uma nova secção',
'tooltip-ca-viewsource'           => 'Esta página está protegida; você pode exibir seu código, no entanto.',
'tooltip-ca-history'              => 'Edições anteriores desta página.',
'tooltip-ca-protect'              => 'Proteger esta página',
'tooltip-ca-unprotect'            => 'Desproteger esta página',
'tooltip-ca-delete'               => 'Apagar esta página',
'tooltip-ca-undelete'             => 'Restaurar edições feitas a esta página antes da eliminação',
'tooltip-ca-move'                 => 'Mover esta página',
'tooltip-ca-watch'                => 'Adicionar esta página aos vigiados',
'tooltip-ca-unwatch'              => 'Remover esta página dos vigiados',
'tooltip-search'                  => 'Pesquisar nesta wiki',
'tooltip-search-go'               => 'Ir a uma página com este exato nome, caso exista',
'tooltip-search-fulltext'         => 'Procurar por páginas contendo este texto',
'tooltip-p-logo'                  => 'Visite a página principal',
'tooltip-n-mainpage'              => 'Visitar a página principal',
'tooltip-n-mainpage-description'  => 'Visitar a página principal',
'tooltip-n-portal'                => 'Sobre o projecto',
'tooltip-n-currentevents'         => 'Informação temática sobre eventos actuais',
'tooltip-n-recentchanges'         => 'A lista de mudanças recentes nesta wiki.',
'tooltip-n-randompage'            => 'Carregar página aleatória',
'tooltip-n-help'                  => 'Um local reservado para auxílio.',
'tooltip-t-whatlinkshere'         => 'Lista de todas as páginas que se ligam a esta',
'tooltip-t-recentchangeslinked'   => 'Mudanças recentes em páginas relacionadas a esta',
'tooltip-feed-rss'                => 'Feed RSS desta página',
'tooltip-feed-atom'               => 'Feed Atom desta página',
'tooltip-t-contributions'         => 'Ver as contribuições deste utilizador',
'tooltip-t-emailuser'             => 'Enviar um e-mail a este utilizador',
'tooltip-t-upload'                => 'Carregar ficheiros',
'tooltip-t-specialpages'          => 'Lista de páginas especiais',
'tooltip-t-print'                 => 'Versão para impressão desta página',
'tooltip-t-permalink'             => 'Link permanente para esta versão desta página',
'tooltip-ca-nstab-main'           => 'Ver a página de conteúdo',
'tooltip-ca-nstab-user'           => 'Ver a página de utilizador',
'tooltip-ca-nstab-media'          => 'Ver a página de media',
'tooltip-ca-nstab-special'        => 'Esta é uma página especial, não pode ser editada.',
'tooltip-ca-nstab-project'        => 'Ver a página de projecto',
'tooltip-ca-nstab-image'          => 'Ver a página de ficheiro',
'tooltip-ca-nstab-mediawiki'      => 'Ver a mensagem de sistema',
'tooltip-ca-nstab-template'       => 'Ver a predefinição',
'tooltip-ca-nstab-help'           => 'Ver a página de ajuda',
'tooltip-ca-nstab-category'       => 'Ver a página da categoria',
'tooltip-minoredit'               => 'Marcar como edição menor',
'tooltip-save'                    => 'Salvar as alterações',
'tooltip-preview'                 => 'Prever as alterações, por favor utilizar antes de salvar!',
'tooltip-diff'                    => 'Mostrar alterações que fez a este texto.',
'tooltip-compareselectedversions' => 'Ver as diferenças entre as duas versões seleccionadas desta página.',
'tooltip-watch'                   => 'Adicionar esta página à sua lista de vigiados',
'tooltip-recreate'                => 'Recriar a página apesar de ter sido eliminada',
'tooltip-upload'                  => 'Iniciar o upload',
'tooltip-rollback'                => '"{{int:rollbacklink}}" reverte, com um só clique, as edições do último editor desta página.',
'tooltip-undo'                    => '"{{int:editundo}}" reverte esta edição exibindo o box de edição no modo de previsão, permitindo alterações adicionais e o uso do sumário de edição para justificativas.',

# Stylesheets
'common.css'      => '/* Código CSS colocado aqui será aplicado a todos os temas */',
'standard.css'    => '/* Código CSS colocado aqui afectará os utilizadores do tema {{MediaWiki:skinname-standard/pt}} */',
'nostalgia.css'   => '/* Código CSS colocado aqui afectará os utilizadores do tema {{MediaWiki:skinname-nostalgia/pt}} */',
'cologneblue.css' => '/* Código CSS colocado aqui afectará os utilizadores do tema {{MediaWiki:skinname-cologneblue/pt}} */',
'monobook.css'    => '/* Código CSS colocado aqui afectará os utilizadores do tema {{MediaWiki:skinname-monobook/pt}} */',
'myskin.css'      => '/* Código CSS colocado aqui afectará os utilizadores do tema {{MediaWiki:skinname-myskin/pt}} */',
'chick.css'       => '/* Código CSS colocado aqui afectará os utilizadores do tema {{MediaWiki:skinname-chick/pt}} */',
'simple.css'      => '/* Código CSS colocado aqui afectará os utilizadores do tema {{MediaWiki:skinname-simple/pt}} */',
'modern.css'      => '/* Código CSS colocado aqui afectará os utilizadores do tema {{MediaWiki:skinname-modern/pt}} */',
'vector.css'      => '/* Código CSS colocado aqui afectará os utilizadores do tema {{MediaWiki:skinname-vector/pt}} */',
'print.css'       => '/* Código CSS colocado aqui afectará as impressões */',
'handheld.css'    => '/* Código CSS colocado aqui afectará dispositivos móveis baseados no tema configurado em $wgHandheldStyle */',

# Scripts
'common.js'      => '/* Código Javascript colocado aqui será carregado para todos os utilizadores em cada carregamento de página */',
'standard.js'    => '/* Código Javascript colocado aqui será carregado para utilizadores do tema {{MediaWiki:skinname-standard/pt}} */',
'nostalgia.js'   => '/* Código Javascript colocado aqui será carregado para utilizadores do tema {{MediaWiki:skinname-nostalgia/pt}} */',
'cologneblue.js' => '/* Código Javascript colocado aqui será carregado para utilizadores do tema {{MediaWiki:skinname-cologneblue/pt}} */',
'monobook.js'    => '/* Código Javascript colocado aqui será carregado para utilizadores do tema {{MediaWiki:skinname-monobook/pt}} */',
'myskin.js'      => '/* Código Javascript colocado aqui será carregado para utilizadores do tema {{MediaWiki:skinname-myskin/pt}} */',
'chick.js'       => '/* Código Javascript colocado aqui será carregado para utilizadores do tema {{MediaWiki:skinname-chick/pt}} */',
'simple.js'      => '/* Código Javascript colocado aqui será carregado para utilizadores do tema {{MediaWiki:skinname-simple/pt}} */',
'modern.js'      => '/* Código Javascript colocado aqui será carregado para utilizadores do tema {{MediaWiki:skinname-modern/pt}} */',
'vector.js'      => '/* Código Javascript colocado aqui será carregado para utilizadores do tema {{MediaWiki:skinname-vector/pt}} */',

# Metadata
'nodublincore'      => 'Os metadados RDF para Dublin Core estão desabilitados neste servidor.',
'nocreativecommons' => 'Os metadados RDF para Creative Commons estão desabilitados neste servidor.',
'notacceptable'     => 'O servidor não pode fornecer os dados num formato que o seu cliente possa ler.',

# Attribution
'anonymous'        => '{{PLURAL:$1|Utilizador anónimo|Utilizadores anónimos}} da {{SITENAME}}',
'siteuser'         => 'um utilizador da {{SITENAME}}: $1',
'anonuser'         => 'utilizador anónimo $1 de {{SITENAME}}',
'lastmodifiedatby' => 'Esta página foi modificada pela última vez às $2 de $1 por $3.',
'othercontribs'    => 'Baseado no trabalho de $1.',
'others'           => 'outros',
'siteusers'        => '{{PLURAL:$2|um utilizador|$2 utilizadores}} de {{SITENAME}}: $1',
'anonusers'        => '{{PLURAL:$2|utilizador anónimo|utilizadores anónimos}} de {{SITENAME}}: $1',
'creditspage'      => 'Créditos da página',
'nocredits'        => 'Não há informação disponível sobre os créditos desta página.',

# Spam protection
'spamprotectiontitle' => 'Filtro de protecção contra spam',
'spamprotectiontext'  => 'A página que deseja salvar foi bloqueada pelo filtro de spam.
Tal bloqueio foi provavelmente causado por uma ligação para um website externo que conste na lista negra.',
'spamprotectionmatch' => 'O seguinte texto activou o filtro de spam: $1',
'spambot_username'    => 'MediaWiki limpeza de spam',
'spam_reverting'      => 'Revertendo para a última versão não contendo hiperligações para $1',
'spam_blanking'       => 'Limpando todas as edições contendo hiperligações para $1',

# Info page
'infosubtitle'   => 'Informação para página',
'numedits'       => 'Número de edições (página): $1',
'numtalkedits'   => 'Número de edições (página de discussão): $1',
'numwatchers'    => 'Número de pessoas vigiando: $1',
'numauthors'     => 'Número de autores distintos (página): $1',
'numtalkauthors' => 'Número de autores distintos (página de discussão): $1',

# Skin names
'skinname-standard'    => 'Clássico',
'skinname-nostalgia'   => 'Nostalgia',
'skinname-cologneblue' => 'Azul colonial',
'skinname-monobook'    => 'MonoBook',
'skinname-myskin'      => 'MySkin',
'skinname-chick'       => 'Chique',
'skinname-simple'      => 'Simples',
'skinname-modern'      => 'Moderno',
'skinname-vector'      => 'Vector',

# Math options
'mw_math_png'    => 'Gerar sempre como PNG',
'mw_math_simple' => 'HTML caso seja simples, caso contrário, PNG',
'mw_math_html'   => 'HTML se possível, caso contrário, PNG',
'mw_math_source' => 'Deixar como TeX (para navegadores de texto)',
'mw_math_modern' => 'Recomendado para navegadores modernos',
'mw_math_mathml' => 'MathML se possível (experimental)',

# Math errors
'math_failure'          => 'Falhou ao verificar gramática',
'math_unknown_error'    => 'Erro desconhecido',
'math_unknown_function' => 'Função desconhecida',
'math_lexing_error'     => 'Erro léxico',
'math_syntax_error'     => 'Erro de sintaxe',
'math_image_error'      => 'Falha na conversão para PNG. Verifique a instalação do latex, dvips, gs e convert',
'math_bad_tmpdir'       => 'Ocorreram problemas na criação ou escrita no directorio temporário math',
'math_bad_output'       => 'Ocorreram problemas na criação ou escrita no directorio de resultados math',
'math_notexvc'          => 'O executável texvc não foi encontrado. Consulte math/README para instruções da configuração.',

# Patrolling
'markaspatrolleddiff'                 => 'Marcar como patrulhada',
'markaspatrolledtext'                 => 'Marcar esta página como patrulhada',
'markedaspatrolled'                   => 'Marcado como patrulhado',
'markedaspatrolledtext'               => 'A edição seleccionada foi marcada como patrulhada.',
'rcpatroldisabled'                    => 'Edições patrulhadas nas Mudanças Recentes desactivadas',
'rcpatroldisabledtext'                => 'A funcionalidade de edições patrulhadas nas Mudanças Recentes está actualmente desactivada.',
'markedaspatrollederror'              => 'Não é possível marcar como patrulhada',
'markedaspatrollederrortext'          => 'É necessário especificar uma edição a ser marcada como patrulhada.',
'markedaspatrollederror-noautopatrol' => 'Você não está autorizado a marcar suas próprias edições como edições patrulhadas.',

# Patrol log
'patrol-log-page'      => 'Registo de edições patrulhadas',
'patrol-log-header'    => 'Este é um registo de edições patrulhadas.',
'patrol-log-line'      => 'marcou a $1 de $2 como uma edição patrulhada $3',
'patrol-log-auto'      => 'automaticamente',
'patrol-log-diff'      => 'edição $1',
'log-show-hide-patrol' => '$1 registo de edições patrulhadas',

# Image deletion
'deletedrevision'                 => 'Apagada a versão antiga $1',
'filedeleteerror-short'           => 'Erro ao eliminar ficheiro: $1',
'filedeleteerror-long'            => 'Foram encontrados erros ao tentar eliminar o ficheiro:

$1',
'filedelete-missing'              => 'Não é possível eliminar "$1" já que o ficheiro não existe.',
'filedelete-old-unregistered'     => 'A edição de ficheiro especificada para "$1" não se encontra na base de dados.',
'filedelete-current-unregistered' => 'O ficheiro "$1" não se encontra na base de dados.',
'filedelete-archive-read-only'    => 'O servidor web não é capaz de fazer alterações no diretório "$1".',

# Browsing diffs
'previousdiff' => '← Edição anterior',
'nextdiff'     => 'Edição posterior →',

# Media information
'mediawarning'         => "'''Aviso''': Este ficheiro pode conter código malicioso. Ao executar, o seu sistema poderá estar comprometido.<hr />",
'imagemaxsize'         => "Limite de tamanho de imagens:<br />''(para páginas de descrição)''",
'thumbsize'            => 'Tamanho de miniaturas:',
'widthheightpage'      => '$1×$2, $3 {{PLURAL:$3|página|páginas}}',
'file-info'            => '(tamanho: $1, tipo MIME: $2)',
'file-info-size'       => '($1 × $2 pixels, tamanho: $3, tipo MIME: $4)',
'file-nohires'         => '<small>Sem resolução maior disponível.</small>',
'svg-long-desc'        => '(ficheiro SVG, de $1 × $2 pixels, tamanho: $3)',
'show-big-image'       => 'Resolução completa',
'show-big-image-thumb' => '<small>Tamanho desta previsão: $1 × $2 pixels</small>',
'file-info-gif-looped' => 'cíclico',
'file-info-gif-frames' => '$1 {{PLURAL:$1|quadro|quadros}}',

# Special:NewFiles
'newimages'             => 'Galeria de novos ficheiros',
'imagelisttext'         => "É exibida a seguir uma listagem {{PLURAL:$1|de '''um''' ficheiro organizado|de '''$1''' ficheiros organizados}} por $2.",
'newimages-summary'     => 'Esta página especial mostra os ficheiros mais recentemente enviados.',
'newimages-legend'      => 'Filtrar',
'newimages-label'       => 'Nome de ficheiro (ou parte dele):',
'showhidebots'          => '($1 robôs)',
'noimages'              => 'Nada para ver.',
'ilsubmit'              => 'Procurar',
'bydate'                => 'por data',
'sp-newimages-showfrom' => 'Mostrar novos ficheiros a partir de $2, $1',

# Bad image list
'bad_image_list' => 'The format is as follows:

Only list items (lines starting with *) are considered. The first link on a line must be a link to a bad image.
Any subsequent links on the same line are considered to be exceptions, i.e. articles where the image may occur inline.',

# Metadata
'metadata'          => 'Metadados',
'metadata-help'     => 'Este ficheiro contém informação adicional, provavelmente adicionada a partir da câmara digital ou scanner utilizada para criar ou digitalizá-lo.
Caso o ficheiro tenha sido modificado a partir do seu estado original, alguns detalhes poderão não reflectir completamente as mudanças efectuadas.',
'metadata-expand'   => 'Mostrar detalhes adicionais',
'metadata-collapse' => 'Esconder detalhes restantes',
'metadata-fields'   => 'Os campos de metadados EXIF listados nesta mensagem poderão estar presente na exibição da página de imagem quando a tabela de metadados estiver no modo "expandida". Outros poderão estar escondidos por defeito.
* make
* model
* datetimeoriginal
* exposuretime
* fnumber
* isospeedratings
* focallength',

# EXIF tags
'exif-imagewidth'                  => 'Largura',
'exif-imagelength'                 => 'Altura',
'exif-bitspersample'               => 'Bits por componente',
'exif-compression'                 => 'Esquema de compressão',
'exif-photometricinterpretation'   => 'Composição pixel',
'exif-orientation'                 => 'Orientação',
'exif-samplesperpixel'             => 'Número de componentes',
'exif-planarconfiguration'         => 'Arranjo de dados',
'exif-ycbcrsubsampling'            => 'Porcentagem de submistura do canal amarelo para o ciano',
'exif-ycbcrpositioning'            => 'Posicionamento Y e C',
'exif-xresolution'                 => 'Resolução horizontal',
'exif-yresolution'                 => 'Resolução vertical',
'exif-resolutionunit'              => 'Unidade de resolução X e Y',
'exif-stripoffsets'                => 'Localização de dados da imagem',
'exif-rowsperstrip'                => 'Número de linhas por tira',
'exif-stripbytecounts'             => 'Bytes por tira comprimida',
'exif-jpeginterchangeformat'       => 'Desvio para SOI de JPEG',
'exif-jpeginterchangeformatlength' => 'Bytes de dados JPEG',
'exif-transferfunction'            => 'Função de transferência',
'exif-whitepoint'                  => 'Cromaticidade do ponto branco',
'exif-primarychromaticities'       => 'Cromaticidades primárias',
'exif-ycbcrcoefficients'           => 'Coeficientes da matriz de transformação do espaço de cores',
'exif-referenceblackwhite'         => 'Par de valores de referência de preto e branco',
'exif-datetime'                    => 'Data e hora de modificação do ficheiro',
'exif-imagedescription'            => 'Título',
'exif-make'                        => 'Fabricante da câmara',
'exif-model'                       => 'Modelo da câmara',
'exif-software'                    => 'Software utilizado',
'exif-artist'                      => 'Autor',
'exif-copyright'                   => 'Licença',
'exif-exifversion'                 => 'Versão Exif',
'exif-flashpixversion'             => 'Versão de Flashpix suportada',
'exif-colorspace'                  => 'Espaço de cor',
'exif-componentsconfiguration'     => 'Significado de cada componente',
'exif-compressedbitsperpixel'      => 'Modo de compressão de imagem',
'exif-pixelydimension'             => 'Largura de imagem válida',
'exif-pixelxdimension'             => 'Altura de imagem válida',
'exif-makernote'                   => 'Anotações do fabricante',
'exif-usercomment'                 => 'Comentários de utilizadores',
'exif-relatedsoundfile'            => 'Ficheiro áudio relacionado',
'exif-datetimeoriginal'            => 'Data e hora de geração de dados',
'exif-datetimedigitized'           => 'Data e hora de digitalização',
'exif-subsectime'                  => 'Subsegundos DataHora',
'exif-subsectimeoriginal'          => 'Subsegundos DataHoraOriginal',
'exif-subsectimedigitized'         => 'Subsegundos DataHoraDigitalizado',
'exif-exposuretime'                => 'Tempo de exposição',
'exif-exposuretime-format'         => '$1 seg ($2)',
'exif-fnumber'                     => 'Número F',
'exif-exposureprogram'             => 'Programa de exposição',
'exif-spectralsensitivity'         => 'Sensibilidade espectral',
'exif-isospeedratings'             => 'Taxa de velocidade ISO',
'exif-oecf'                        => 'Factor optoelectrónico de conversão.',
'exif-shutterspeedvalue'           => 'Velocidade do obturador',
'exif-aperturevalue'               => 'Abertura',
'exif-brightnessvalue'             => 'Brilho',
'exif-exposurebiasvalue'           => 'Polarização de exposição',
'exif-maxaperturevalue'            => 'Abertura máxima',
'exif-subjectdistance'             => 'Distância do sujeito',
'exif-meteringmode'                => 'Modo de medição',
'exif-lightsource'                 => 'Fonte de luz',
'exif-flash'                       => 'Flash',
'exif-focallength'                 => 'Comprimento de foco da lente',
'exif-subjectarea'                 => 'Área de sujeito',
'exif-flashenergy'                 => 'Energia do flash',
'exif-spatialfrequencyresponse'    => 'Resposta em frequência espacial',
'exif-focalplanexresolution'       => 'Resolução do plano focal X',
'exif-focalplaneyresolution'       => 'Resolução do plano focal Y',
'exif-focalplaneresolutionunit'    => 'Unidade de resolução do plano focal',
'exif-subjectlocation'             => 'Localização de sujeito',
'exif-exposureindex'               => 'Índice de exposição',
'exif-sensingmethod'               => 'Método de sensação',
'exif-filesource'                  => 'Fonte do ficheiro',
'exif-scenetype'                   => 'Tipo de cena',
'exif-cfapattern'                  => 'padrão CFA',
'exif-customrendered'              => 'Processamento de imagem personalizado',
'exif-exposuremode'                => 'Modo de exposição',
'exif-whitebalance'                => 'Balanço do branco',
'exif-digitalzoomratio'            => 'Proporção de zoom digital',
'exif-focallengthin35mmfilm'       => 'Distância focal em filme de 35 mm',
'exif-scenecapturetype'            => 'Tipo de captura de cena',
'exif-gaincontrol'                 => 'Controlo de cena',
'exif-contrast'                    => 'Contraste',
'exif-saturation'                  => 'Saturação',
'exif-sharpness'                   => 'Nitidez',
'exif-devicesettingdescription'    => 'Descrição das configurações do dispositivo',
'exif-subjectdistancerange'        => 'Distância de alcance do sujeito',
'exif-imageuniqueid'               => 'Identificação única da imagem',
'exif-gpsversionid'                => 'Versão de GPS',
'exif-gpslatituderef'              => 'Latitude Norte ou Sul',
'exif-gpslatitude'                 => 'Latitude',
'exif-gpslongituderef'             => 'Longitude Leste ou Oeste',
'exif-gpslongitude'                => 'Longitude',
'exif-gpsaltituderef'              => 'Referência de altitude',
'exif-gpsaltitude'                 => 'Altitude',
'exif-gpstimestamp'                => 'Tempo GPS (relógio atómico)',
'exif-gpssatellites'               => 'Satélites utilizados para a medição',
'exif-gpsstatus'                   => 'Estado do receptor',
'exif-gpsmeasuremode'              => 'Modo da medição',
'exif-gpsdop'                      => 'Precisão da medição',
'exif-gpsspeedref'                 => 'Unidade da velocidade',
'exif-gpsspeed'                    => 'Velocidade do receptor GPS',
'exif-gpstrackref'                 => 'Referência para a direcção do movimento',
'exif-gpstrack'                    => 'Direcção do movimento',
'exif-gpsimgdirectionref'          => 'Referência para a direcção da imagem',
'exif-gpsimgdirection'             => 'Direcção da imagem',
'exif-gpsmapdatum'                 => 'Utilizados dados do estudo Geodetic',
'exif-gpsdestlatituderef'          => 'Referência para a latitude do destino',
'exif-gpsdestlatitude'             => 'Latitude do destino',
'exif-gpsdestlongituderef'         => 'Referência para a longitude do destino',
'exif-gpsdestlongitude'            => 'Longitude do destino',
'exif-gpsdestbearingref'           => 'Referência para o azimute do destino',
'exif-gpsdestbearing'              => 'Azimute do destino',
'exif-gpsdestdistanceref'          => 'Referência de distância para o destino',
'exif-gpsdestdistance'             => 'Distância para o destino',
'exif-gpsprocessingmethod'         => 'Nome do método de processamento do GPS',
'exif-gpsareainformation'          => 'Nome da área do GPS',
'exif-gpsdatestamp'                => 'Data do GPS',
'exif-gpsdifferential'             => 'Correcção do diferencial do GPS',

# EXIF attributes
'exif-compression-1' => 'Descomprimido',

'exif-unknowndate' => 'Data desconhecida',

'exif-orientation-1' => 'Normal',
'exif-orientation-2' => 'Espelhamento horizontal',
'exif-orientation-3' => 'Rotacionado em 180°',
'exif-orientation-4' => 'Espelhamento vertical',
'exif-orientation-5' => 'Rotacionado em 90º em sentido anti-horário e espelhado verticalmente',
'exif-orientation-6' => 'Rotacionado em 90° no sentido horário',
'exif-orientation-7' => 'Rotacionado em 90° no sentido horário e espelhado verticalmente',
'exif-orientation-8' => 'Rotacionado 90° no sentido anti-horário',

'exif-planarconfiguration-1' => 'formato irregular',
'exif-planarconfiguration-2' => 'formato plano',

'exif-componentsconfiguration-0' => 'não existe',

'exif-exposureprogram-0' => 'Não definido',
'exif-exposureprogram-1' => 'Manual',
'exif-exposureprogram-2' => 'Programa normal',
'exif-exposureprogram-3' => 'Prioridade de abertura',
'exif-exposureprogram-4' => 'Prioridade de obturador',
'exif-exposureprogram-5' => 'Programa criativo (com tendência para profundidade de campo)',
'exif-exposureprogram-6' => 'Programa de movimento (tende a velocidade de disparo mais rápida)',
'exif-exposureprogram-7' => 'Modo de retrato (para fotos em <i>closeup</i> com o fundo fora de foco)',
'exif-exposureprogram-8' => 'Modo de paisagem (para fotos de paisagem com o fundo em foco)',

'exif-subjectdistance-value' => '$1 metros',

'exif-meteringmode-0'   => 'Desconhecido',
'exif-meteringmode-1'   => 'Média',
'exif-meteringmode-2'   => 'MédiaPonderadaAoCentro',
'exif-meteringmode-3'   => 'Ponto',
'exif-meteringmode-4'   => 'MultiPonto',
'exif-meteringmode-5'   => 'Padrão',
'exif-meteringmode-6'   => 'Parcial',
'exif-meteringmode-255' => 'Outro',

'exif-lightsource-0'   => 'Desconhecida',
'exif-lightsource-1'   => 'Luz do dia',
'exif-lightsource-2'   => 'Fluorescente',
'exif-lightsource-3'   => 'Tungsténio (luz incandescente)',
'exif-lightsource-4'   => 'Flash',
'exif-lightsource-9'   => 'Tempo bom',
'exif-lightsource-10'  => 'Tempo nublado',
'exif-lightsource-11'  => 'Sombra',
'exif-lightsource-12'  => 'Iluminação fluorecente (D 5700 – 7100K)',
'exif-lightsource-13'  => 'Iluminação fluorecente branca (N 4600 – 5400K)',
'exif-lightsource-14'  => 'Iluminação fluorecente esbranquiçada (W 3900 – 4500K)',
'exif-lightsource-15'  => 'Iluminação fluorecente branca (WW 3200 – 3700K)',
'exif-lightsource-17'  => 'Padrão de lâmpada A',
'exif-lightsource-18'  => 'Padrão de lâmpada B',
'exif-lightsource-19'  => 'Padrão de lâmpada C',
'exif-lightsource-24'  => 'Tungsténio de estúdio ISO',
'exif-lightsource-255' => 'Outra fonte de luz',

# Flash modes
'exif-flash-fired-0'    => 'Flash não disparou',
'exif-flash-fired-1'    => 'Flash disparado',
'exif-flash-return-0'   => 'strobe não encontrou ou detectou nenhuma função',
'exif-flash-return-2'   => 'strobe não retornou a função detectada',
'exif-flash-return-3'   => 'strobe retornou a função detectada',
'exif-flash-mode-1'     => 'disparo de flash forçado',
'exif-flash-mode-2'     => 'disparo de flash suprimido',
'exif-flash-mode-3'     => 'modo auto',
'exif-flash-function-1' => 'Sem função de flash',
'exif-flash-redeye-1'   => 'modo de redução de olhos vermelhos',

'exif-focalplaneresolutionunit-2' => 'polegadas',

'exif-sensingmethod-1' => 'Indefinido',
'exif-sensingmethod-2' => 'Sensor de áreas de cores de um chip',
'exif-sensingmethod-3' => 'Sensor de áreas de cores de dois chips',
'exif-sensingmethod-4' => 'Sensor de áreas de cores de três chips',
'exif-sensingmethod-5' => 'Sensor de área sequencial de cores',
'exif-sensingmethod-7' => 'Sensor trilinear',
'exif-sensingmethod-8' => 'Sensor linear sequencial de cores',

'exif-scenetype-1' => 'Imagem fotografada directamente',

'exif-customrendered-0' => 'Processo normal',
'exif-customrendered-1' => 'Processo personalizado',

'exif-exposuremode-0' => 'Exposição automática',
'exif-exposuremode-1' => 'Exposição manual',
'exif-exposuremode-2' => 'Bracket automático',

'exif-whitebalance-0' => 'Balanço de brancos automático',
'exif-whitebalance-1' => 'Balanço de brancos manual',

'exif-scenecapturetype-0' => 'Padrão',
'exif-scenecapturetype-1' => 'Paisagem',
'exif-scenecapturetype-2' => 'Retrato',
'exif-scenecapturetype-3' => 'Cena noturna',

'exif-gaincontrol-0' => 'Nenhum',
'exif-gaincontrol-1' => 'Baixo ganho positivo',
'exif-gaincontrol-2' => 'Alto ganho positivo',
'exif-gaincontrol-3' => 'Baixo ganho negativo',
'exif-gaincontrol-4' => 'Alto ganho negativo',

'exif-contrast-0' => 'Normal',
'exif-contrast-1' => 'Suave',
'exif-contrast-2' => 'Alto',

'exif-saturation-0' => 'Normal',
'exif-saturation-1' => 'Baixa saturação',
'exif-saturation-2' => 'Alta saturação',

'exif-sharpness-0' => 'Normal',
'exif-sharpness-1' => 'Fraco',
'exif-sharpness-2' => 'Forte',

'exif-subjectdistancerange-0' => 'Desconhecida',
'exif-subjectdistancerange-1' => 'Macro',
'exif-subjectdistancerange-2' => 'Vista próxima',
'exif-subjectdistancerange-3' => 'Vista distante',

# Pseudotags used for GPSLatitudeRef and GPSDestLatitudeRef
'exif-gpslatitude-n' => 'Latitude Norte',
'exif-gpslatitude-s' => 'Latitude Sul',

# Pseudotags used for GPSLongitudeRef and GPSDestLongitudeRef
'exif-gpslongitude-e' => 'Longitude Leste',
'exif-gpslongitude-w' => 'Longitude Oeste',

'exif-gpsstatus-a' => 'Medição em progresso',
'exif-gpsstatus-v' => 'Interoperabilidade de medição',

'exif-gpsmeasuremode-2' => 'Medição bidimensional',
'exif-gpsmeasuremode-3' => 'Medição tridimensional',

# Pseudotags used for GPSSpeedRef
'exif-gpsspeed-k' => 'Quilómetros por hora',
'exif-gpsspeed-m' => 'Milhas por hora',
'exif-gpsspeed-n' => 'Nós',

# Pseudotags used for GPSTrackRef, GPSImgDirectionRef and GPSDestBearingRef
'exif-gpsdirection-t' => 'Direcção real',
'exif-gpsdirection-m' => 'Direcção magnética',

# External editor support
'edit-externally'      => 'Editar este ficheiro utilizando uma aplicação externa',
'edit-externally-help' => '(Consulte as [http://www.mediawiki.org/wiki/Manual:External_editors instruções de instalação] para maiores informações)',

# 'all' in various places, this might be different for inflected languages
'recentchangesall' => 'todas',
'imagelistall'     => 'todas',
'watchlistall2'    => 'todas',
'namespacesall'    => 'todos',
'monthsall'        => 'todos',
'limitall'         => 'todas',

# E-mail address confirmation
'confirmemail'             => 'Confirmar endereço de E-mail',
'confirmemail_noemail'     => 'Não possui um endereço de e-mail válido indicado nas suas [[Special:Preferences|preferências de utilizador]].',
'confirmemail_text'        => 'Esta wiki requer que valide o seu endereço de e-mail antes de utilizar as funcionalidades que requerem um endereço de e-mail. Active o botão abaixo para enviar uma confirmação para o seu endereço de e-mail. A mensagem incluíra um endereço que contém um código; carregue o endereço no seu navegador para confirmar que o seu endereço de e-mail encontra-se válido.',
'confirmemail_pending'     => 'Um código de confirmação já lhe foi enviado;
caso tenha criado sua conta recentemente, é recomendável aguardar alguns minutos para o receber antes de tentar pedir um novo código.',
'confirmemail_send'        => 'Enviar código de confirmação',
'confirmemail_sent'        => 'E-mail de confirmação enviado.',
'confirmemail_oncreate'    => 'Foi enviado um código de confirmação para o seu endereço de e-mail.
Tal código não é exigido para que possa se autenticar no sistema, mas será necessário que você o forneça antes de habilitar qualquer ferramenta baseada no uso de e-mail deste wiki.',
'confirmemail_sendfailed'  => 'Não foi possível enviar o email de confirmação.
Verifique se o seu endereço de e-mail possui caracteres inválidos.

O mailer retornou: $1',
'confirmemail_invalid'     => 'Código de confirmação inválido. O código poderá ter expirado.',
'confirmemail_needlogin'   => 'Precisa de $1 para confirmar o seu endereço de correio electrónico.',
'confirmemail_success'     => 'O seu endereço de e-mail foi confirmado. Pode agora se ligar.',
'confirmemail_loggedin'    => 'O seu endereço de e-mail foi agora confirmado.',
'confirmemail_error'       => 'Alguma coisa correu mal ao guardar a sua confirmação.',
'confirmemail_subject'     => '{{SITENAME}} confirmação de endereço de e-mail',
'confirmemail_body'        => 'Alguém, provavelmente você com o endereço de IP $1,
registou uma conta "$2" com este endereço de e-mail em {{SITENAME}}.

Para confirmar que esta conta realmente é sua, e para activar
as funcionalidades de e-mail em {{SITENAME}},
abra o seguinte endereço no seu navegador:

$3

Caso este *não* seja você, siga o seguinte endereço
para cancelar a confirmação do endereço de e-mail:

$5

Este código de confirmação irá expirar a $4.',
'confirmemail_invalidated' => 'Confirmação de endereço de e-mail cancelada',
'invalidateemail'          => 'Cancelar confirmação de e-mail',

# Scary transclusion
'scarytranscludedisabled' => '[A transclusão de páginas de outros wikis encontra-se desabilitada]',
'scarytranscludefailed'   => '[Não foi possível obter a predefinição a partir de $1]',
'scarytranscludetoolong'  => '[URL longa demais]',

# Trackbacks
'trackbackbox'      => 'Trackbacks para esta página:<br />
$1',
'trackbackremove'   => '([$1 Eliminar])',
'trackbacklink'     => 'Trackback',
'trackbackdeleteok' => 'O trackback foi eliminado com sucesso.',

# Delete conflict
'deletedwhileediting' => "'''Aviso''': Esta página foi eliminada após você ter começado a editar!",
'confirmrecreate'     => "O utilizador [[User:$1|$1]] ([[User talk:$1|Discussão]]) eliminou esta página após você ter começado a editar, pelo seguinte motivo:
: ''$2''
Por favor, confirme que realmente deseja recriar esta página.",
'recreate'            => 'Recriar',

# action=purge
'confirm_purge_button' => 'OK',
'confirm-purge-top'    => 'Limpar a memória cache desta página?',
'confirm-purge-bottom' => 'Purgar uma página limpa a cache e força a sua versão mais recente a aparecer.',

# Multipage image navigation
'imgmultipageprev' => '← página anterior',
'imgmultipagenext' => 'próxima página →',
'imgmultigo'       => 'Ir!',
'imgmultigoto'     => 'Ir para a página $1',

# Table pager
'ascending_abbrev'         => 'asc',
'descending_abbrev'        => 'desc',
'table_pager_next'         => 'Próxima página',
'table_pager_prev'         => 'Página anterior',
'table_pager_first'        => 'Primeira página',
'table_pager_last'         => 'Última página',
'table_pager_limit'        => 'Mostrar $1 items por página',
'table_pager_limit_submit' => 'Ir',
'table_pager_empty'        => 'Sem resultados',

# Auto-summaries
'autosumm-blank'   => 'Limpou toda a página',
'autosumm-replace' => "Página substituída por '$1'",
'autoredircomment' => 'Redireccionando para [[$1]]',
'autosumm-new'     => "Criou nova página com '$1'",

# Live preview
'livepreview-loading' => 'Carregando…',
'livepreview-ready'   => 'Carregando… Pronto!',
'livepreview-failed'  => 'A previsão instantânea falhou!
Tente a previsão comum.',
'livepreview-error'   => 'Falha ao conectar: $1 "$2"
Tente a previsão comum.',

# Friendlier slave lag warnings
'lag-warn-normal' => 'Possivelmente as alterações que sejam mais recentes do que $1 {{PLURAL:$1|segundo|segundos}} não serão exibidas nesta lista.',
'lag-warn-high'   => 'Devido a sérios problemas de latência no servidor da base de dados, as alterações mais recentes que $1 {{PLURAL:$1|segundo|segundos}} poderão não ser exibidas nesta lista.',

# Watchlist editor
'watchlistedit-numitems'       => 'A sua lista de vigiados possui {{PLURAL:$1|um título|$1 títulos}}, além das respectivas páginas de discussão.',
'watchlistedit-noitems'        => 'A sua lista de vigiados não contém títulos.',
'watchlistedit-normal-title'   => 'Editar lista de vigiados',
'watchlistedit-normal-legend'  => 'Remover títulos da lista de vigiados',
'watchlistedit-normal-explain' => 'Os títulos na sua lista de vigiados são mostrados a seguir.
Para remover um título, marque a caixa ao lado do mesmo e clique o botão "{{MediaWiki:watchlistedit-normal-submit/pt}}".
Também pode [[Special:Watchlist/raw|editar a lista crua]].',
'watchlistedit-normal-submit'  => 'Remover títulos',
'watchlistedit-normal-done'    => '{{PLURAL:$1|um título foi removido|$1 títulos foram removidos}} de sua lista de vigiados:',
'watchlistedit-raw-title'      => 'Edição crua dos vigiados',
'watchlistedit-raw-legend'     => 'Edição crua dos vigiados',
'watchlistedit-raw-explain'    => 'Os títulos na sua lista de vigiados são mostrados a seguir.
Pode adicionar ou remover títulos editando a lista, desde que mantenha um único título por linha.
Quando terminar, clique no botão "{{MediaWiki:watchlistedit-raw-submit/pt}}".
Também pode [[Special:Watchlist/edit|editar a lista da maneira convencional]].',
'watchlistedit-raw-titles'     => 'Títulos:',
'watchlistedit-raw-submit'     => 'Actualizar a lista de vigiados',
'watchlistedit-raw-done'       => 'A sua lista de vigiados foi actualizada.',
'watchlistedit-raw-added'      => '{{PLURAL:$1|Foi adicionado um título|Foram adicionados $1 títulos}}:',
'watchlistedit-raw-removed'    => '{{PLURAL:$1|Foi removido um título|Foram removidos $1 títulos}}:',

# Watchlist editing tools
'watchlisttools-view' => 'Ver alterações relevantes',
'watchlisttools-edit' => 'Ver e editar a lista de vigiados',
'watchlisttools-raw'  => 'Edição crua da lista de vigiados',

# Core parser functions
'unknown_extension_tag' => '"$1" é uma tag de extensão desconhecida',
'duplicate-defaultsort' => 'Aviso: A chave de ordenação padrão "$2" sobrepõe-se à anterior chave de ordenação padrão "$1".',

# Special:Version
'version'                          => 'Versão',
'version-extensions'               => 'Extensões instaladas',
'version-specialpages'             => 'Páginas especiais',
'version-parserhooks'              => 'Hooks do analisador (parser)',
'version-variables'                => 'Variáveis',
'version-other'                    => 'Diversos',
'version-mediahandlers'            => 'Executores de média',
'version-hooks'                    => 'Hooks',
'version-extension-functions'      => 'Funções de extensão',
'version-parser-extensiontags'     => 'Etiquetas de extensões de tipo "parser"',
'version-parser-function-hooks'    => 'Funções "hooks" de "parser"',
'version-skin-extension-functions' => 'Funções de extensão de skins',
'version-hook-name'                => 'Nome do hook',
'version-hook-subscribedby'        => 'Subscrito por',
'version-version'                  => '(Versão $1)',
'version-license'                  => 'Licença',
'version-software'                 => 'Software instalado',
'version-software-product'         => 'Produto',
'version-software-version'         => 'Versão',

# Special:FilePath
'filepath'         => 'Caminho do ficheiro',
'filepath-page'    => 'Ficheiro:',
'filepath-submit'  => 'Diretório',
'filepath-summary' => 'Através desta página especial, é possível descobrir o endereço completo de um determinado ficheiro.
As imagens serão exibidas em sua resolução máxima, outros tipos de ficheiros serão iniciados automaticamente em seus programas correspondentes.

Introduza o nome do ficheiro sem utilizar o prefixo "{{ns:file}}:".',

# Special:FileDuplicateSearch
'fileduplicatesearch'          => 'Procurar por ficheiros duplicados',
'fileduplicatesearch-summary'  => 'Procure por ficheiros duplicados tendo por base seu valor "hash".

Entre com o nome de ficheiro sem fornecer o prefixo "{{ns:file}}:".',
'fileduplicatesearch-legend'   => 'Procurar por duplicatas',
'fileduplicatesearch-filename' => 'Nome do ficheiro:',
'fileduplicatesearch-submit'   => 'Pesquisa',
'fileduplicatesearch-info'     => '$1 × $2 pixels<br />Tamanho: $3<br />tipo MIME: $4',
'fileduplicatesearch-result-1' => 'O ficheiro "$1" não possui cópias idênticas.',
'fileduplicatesearch-result-n' => 'O ficheiro "$1" possui {{PLURAL:$2|uma cópia idêntica|$2 cópias idênticas}}.',

# Special:SpecialPages
'specialpages'                   => 'Páginas especiais',
'specialpages-note'              => '----
* Páginas especiais normais.
* <strong class="mw-specialpagerestricted">Páginas especiais restritas.</strong>',
'specialpages-group-maintenance' => 'Relatórios de manutenção',
'specialpages-group-other'       => 'Outras páginas especiais',
'specialpages-group-login'       => 'Entrar / registar-se',
'specialpages-group-changes'     => 'Mudanças e registos recentes',
'specialpages-group-media'       => 'Relatórios de media e carregamentos',
'specialpages-group-users'       => 'Utilizadores e privilégios',
'specialpages-group-highuse'     => 'Páginas muito usadas',
'specialpages-group-pages'       => 'Listas de páginas',
'specialpages-group-pagetools'   => 'Ferramentas de páginas',
'specialpages-group-wiki'        => 'Dados e ferramentas sobre este wiki',
'specialpages-group-redirects'   => 'Páginas especias redirecionadoras',
'specialpages-group-spam'        => 'Ferramentas anti-spam',

# Special:BlankPage
'blankpage'              => 'Página em branco',
'intentionallyblankpage' => 'Esta página foi intencionalmente deixada em branco',

# External image whitelist
'external_image_whitelist' => '  # Deixe esta linha exatamente como ela está<pre> 
# Coloque uma expressão regular (apenas a parte que vai entre os //) abaixo
# Estas serão comparadas com as URLs de imagens externas (com links diretos)
# Aquelas que corresponderem serão exibidas como imagens, caso contrário, apenas um link para a imagem será mostrado
# As linhas que começam com # são tratadas como comentários
# Esta lista ignora maiúsculas e minúsculas

# Coloque todos os fragmentos de regex acima desta linha. Deixe esta linha exatamente como ela está</ pre>',

# Special:Tags
'tags'                    => 'Etiquetas de modificação válidas',
'tag-filter'              => 'Filtro de [[Special:Tags|etiquetas]]:',
'tag-filter-submit'       => 'Filtrar',
'tags-title'              => 'Etiquetas',
'tags-intro'              => 'Esta página lista as etiquetas com que o software poderá marcar uma edição, e o seu significado.',
'tags-tag'                => 'Nome da etiqueta',
'tags-display-header'     => 'Aparência nas listas de modificações',
'tags-description-header' => 'Descrição completa do significado',
'tags-hitcount-header'    => 'Modificações etiquetadas',
'tags-edit'               => 'editar',
'tags-hitcount'           => '$1 {{PLURAL:$1|modificação|modificações}}',

# Database error messages
'dberr-header'      => 'Este wiki tem um problema',
'dberr-problems'    => 'Desculpe! Este sítio está a experienciar dificuldades técnicas.',
'dberr-again'       => 'Experimente esperar uns minutos e atualizar.',
'dberr-info'        => '(Não foi possível contactar o servidor de base de dados: $1)',
'dberr-usegoogle'   => 'Pode tentar pesquisar no Google entretanto.',
'dberr-outofdate'   => 'Note que os seus índices relativos ao nosso conteúdo podem estar desatualizados.',
'dberr-cachederror' => 'A seguinte página é uma cópia em cache da página pedida e pode não estar atualizada.',

# HTML forms
'htmlform-invalid-input'       => 'Existem problemas com alguns dos dados introduzidos',
'htmlform-select-badoption'    => 'O valor que você especificou não é uma opção válida.',
'htmlform-int-invalid'         => 'O valor que você especificou não é um inteiro.',
'htmlform-float-invalid'       => 'O valor que especificou não é um número.',
'htmlform-int-toolow'          => 'O valor que você especificou está abaixo do mínimo de $1',
'htmlform-int-toohigh'         => 'O valor que você especificou está acima do máximo de $1',
'htmlform-submit'              => 'Enviar',
'htmlform-reset'               => 'Desfazer alterações',
'htmlform-selectorother-other' => 'Outros',

# Add categories per AJAX
'ajax-add-category'            => 'Adicionar categoria',
'ajax-add-category-submit'     => 'Adicionar',
'ajax-confirm-title'           => 'Confirme a acção',
'ajax-confirm-prompt'          => 'Você pode providenciar um sumário de edição abaixo.
Carregue em "Gravar página" para gravar a sua edição.',
'ajax-confirm-save'            => 'Gravar',
'ajax-add-category-summary'    => 'Adicionado categoria "$1"',
'ajax-remove-category-summary' => 'Remover categoria "$1"',
'ajax-confirm-actionsummary'   => 'Acção a tomar:',
'ajax-error-title'             => 'Erro',
'ajax-error-dismiss'           => 'OK',
'ajax-remove-category-error'   => 'Não foi possível remover esta categoria.
Isto normalmente ocorre quando a categoria foi adicionada à página através de uma predefinição.',

);
