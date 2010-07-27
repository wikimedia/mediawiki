<?php
/** Brazilian Portuguese (Português do Brasil)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Bani
 * @author BrunaaAa
 * @author Brunoy Anastasiya Seryozhenko
 * @author Capmo
 * @author Carla404
 * @author Crazymadlover
 * @author Daemorris
 * @author Danielsouzat
 * @author Diego Queiroz
 * @author Eduardo.mps
 * @author GKnedo
 * @author Giro720
 * @author Hamilton Abreu
 * @author Heldergeovane
 * @author Jesielt
 * @author Jorge Morais
 * @author Leonardo.stabile
 * @author LeonardoG
 * @author Lijealso
 * @author Luckas Blade
 * @author Malafaya
 * @author McDutchie
 * @author Rodrigo Calanca Nishino
 * @author Urhixidur
 * @author Vuln
 * @author Waldir
 * @author Yves Marques Junqueira
 * @author לערי ריינהארט
 * @author 555
 */

$fallback = 'pt'; 

$namespaceNames = array(
	NS_MEDIA            => 'Media',
	NS_SPECIAL          => 'Especial',
	NS_TALK             => 'Discussão',
	NS_USER             => 'Usuário',
	NS_USER_TALK        => 'Usuário_Discussão',
	NS_PROJECT_TALK     => '$1_Discussão',
	NS_FILE             => 'Arquivo',
	NS_FILE_TALK        => 'Arquivo_Discussão',
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
	'Ficheiro' => NS_FILE,
	'Ficheiro_Discussão' => NS_FILE_TALK,
);


$defaultDateFormat = 'dmy';

$dateFormats = array(

	'dmy time' => 'H"h"i"min"',
	'dmy date' => 'j "de" F "de" Y',
	'dmy both' => 'H"h"i"min" "de" j "de" F "de" Y',

);

$separatorTransformTable = array(',' => ' ', '.' => ',' );

$specialPageAliases = array(
	'DoubleRedirects'           => array( 'Redirecionamentos duplos' ),
	'BrokenRedirects'           => array( 'Redirecionamentos quebrados' ),
	'Disambiguations'           => array( 'Páginas de desambiguação', 'Desambiguar', 'Desambiguações' ),
	'Userlogin'                 => array( 'Autenticar-se', 'Entrar', 'Login' ),
	'Userlogout'                => array( 'Sair', 'Logout' ),
	'CreateAccount'             => array( 'Criar conta' ),
	'Preferences'               => array( 'Preferências' ),
	'Watchlist'                 => array( 'Páginas vigiadas', 'Artigos vigiados', 'Vigiados' ),
	'Recentchanges'             => array( 'Mudanças recentes', 'Recentes' ),
	'Upload'                    => array( 'Carregar arquivo', 'Carregar imagem', 'Carregar ficheiro', 'Enviar' ),
	'Listfiles'                 => array( 'Lista de arquivos', 'Lista de imagens', 'Lista de ficheiros' ),
	'Newimages'                 => array( 'Arquivos novos', 'Imagens novas', 'Ficheiros novos' ),
	'Listusers'                 => array( 'Lista de usuários', 'Lista de utilizadores' ),
	'Listgrouprights'           => array( 'Listar privilégios de grupos' ),
	'Statistics'                => array( 'Estatísticas' ),
	'Randompage'                => array( 'Aleatória', 'Aleatório', 'Página aleatória', 'Artigo aleatório' ),
	'Lonelypages'               => array( 'Páginas órfãs', 'Artigos órfãos', 'Páginas sem afluentes', 'Artigos sem afluentes' ),
	'Uncategorizedpages'        => array( 'Páginas sem categorias', 'Artigos sem categorias' ),
	'Uncategorizedcategories'   => array( 'Categorias sem categorias' ),
	'Uncategorizedimages'       => array( 'Arquivos sem categorias', 'Imagens sem categorias', 'Ficheiros sem categorias' ),
	'Uncategorizedtemplates'    => array( 'Predefinições não categorizadas', 'Predefinições sem categorias' ),
	'Unusedcategories'          => array( 'Categorias não utilizadas', 'Categorias sem uso' ),
	'Unusedimages'              => array( 'Arquivos sem uso', 'Arquivos não utilizados', 'Imagens sem uso', 'Imagens não utilizadas', 'Ficheiros sem uso', 'Ficheiros não utilizados' ),
	'Wantedpages'               => array( 'Páginas pedidas', 'Páginas em falta', 'Artigos em falta', 'Artigos pedidos' ),
	'Wantedcategories'          => array( 'Categorias pedidas', 'Categorias em falta', 'Categorias inexistentes' ),
	'Wantedfiles'               => array( 'Arquivos pedidos', 'Arquivos em falta', 'Ficheiros em falta', 'Imagens em falta' ),
	'Wantedtemplates'           => array( 'Predefinições pedidas', 'Predefinições em falta' ),
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
	'Ipblocklist'               => array( 'Registro de bloqueios', 'IPs bloqueados', 'Utilizadores bloqueados', 'Usuários bloqueados', 'Registo de bloqueios' ),
	'Specialpages'              => array( 'Páginas especiais' ),
	'Contributions'             => array( 'Contribuições' ),
	'Emailuser'                 => array( 'Contatar usuário', 'Contactar usuário', 'Contactar utilizador' ),
	'Confirmemail'              => array( 'Confirmar e-mail', 'Confirmar email' ),
	'Whatlinkshere'             => array( 'Páginas afluentes', 'Artigos afluentes' ),
	'Recentchangeslinked'       => array( 'Mudanças relacionadas', 'Novidades relacionadas' ),
	'Movepage'                  => array( 'Mover página', 'Mover', 'Mover artigo' ),
	'Blockme'                   => array( 'Bloquear-me', 'Auto-bloqueio' ),
	'Booksources'               => array( 'Fontes de livros' ),
	'Categories'                => array( 'Categorias' ),
	'Export'                    => array( 'Exportar' ),
	'Version'                   => array( 'Versão', 'Sobre' ),
	'Allmessages'               => array( 'Todas as mensagens', 'Todas mensagens' ),
	'Log'                       => array( 'Registro', 'Registos', 'Registros', 'Registo' ),
	'Blockip'                   => array( 'Bloquear', 'Bloquear IP', 'Bloquear utilizador', 'Bloquear usuário' ),
	'Undelete'                  => array( 'Restaurar', 'Restaurar páginas eliminadas', 'Restaurar artigos eliminados' ),
	'Import'                    => array( 'Importar' ),
	'Lockdb'                    => array( 'Bloquear banco de dados', 'Bloquear a base de dados' ),
	'Unlockdb'                  => array( 'Desbloquear banco de dados', 'Desbloquear a base de dados' ),
	'Userrights'                => array( 'Privilégios', 'Direitos', 'Estatutos' ),
	'MIMEsearch'                => array( 'Busca MIME' ),
	'FileDuplicateSearch'       => array( 'Busca de arquivos duplicados', 'Busca de ficheiros duplicados' ),
	'Unwatchedpages'            => array( 'Páginas não-vigiadas', 'Páginas não vigiadas', 'Artigos não-vigiados', 'Artigos não vigiados' ),
	'Listredirects'             => array( 'Lista de redirecionamentos', 'Redirecionamentos' ),
	'Revisiondelete'            => array( 'Eliminar edição', 'Eliminar revisão', 'Apagar edição', 'Apagar revisão' ),
	'Unusedtemplates'           => array( 'Predefinições sem uso', 'Predefinições não utilizadas' ),
	'Randomredirect'            => array( 'Redirecionamento aleatório' ),
	'Mypage'                    => array( 'Minha página' ),
	'Mytalk'                    => array( 'Minha discussão' ),
	'Mycontributions'           => array( 'Minhas contribuições', 'Minhas edições' ),
	'Listadmins'                => array( 'Lista de administradores', 'Administradores', 'Admins', 'Lista de admins' ),
	'Listbots'                  => array( 'Lista de robôs', 'Bots', 'Lista de bots' ),
	'Popularpages'              => array( 'Páginas populares', 'Artigos populares' ),
	'Search'                    => array( 'Busca', 'Buscar', 'Procurar', 'Pesquisar', 'Pesquisa' ),
	'Resetpass'                 => array( 'Zerar senha', 'Repor senha' ),
	'Withoutinterwiki'          => array( 'Páginas sem interwikis', 'Artigos sem interwikis' ),
	'MergeHistory'              => array( 'Fundir históricos', 'Fundir edições' ),
	'Filepath'                  => array( 'Diretório de arquivo', 'Diretório de ficheiro' ),
	'Invalidateemail'           => array( 'Invalidar e-mail' ),
	'Blankpage'                 => array( 'Página em branco' ),
	'LinkSearch'                => array( 'Pesquisar links' ),
	'DeletedContributions'      => array( 'Contribuições eliminadas', 'Edições eliminadas' ),
	'Activeusers'               => array( 'Usuários ativos' ),
);

$magicWords = array(
	'redirect'              => array( '0', '#REDIRECIONAMENTO', '#REDIRECT' ),
	'notoc'                 => array( '0', '__SEMTDC__', '__SEMSUMÁRIO__', '__NOTOC__' ),
	'nogallery'             => array( '0', '__SEMGALERIA__', '__NOGALLERY__' ),
	'forcetoc'              => array( '0', '__FORCARTDC__', '__FORCARSUMARIO__', '__FORÇARTDC__', '__FORÇARSUMÁRIO__', '__FORCETOC__' ),
	'toc'                   => array( '0', '__TDC__', '__SUMARIO__', '__SUMÁRIO__', '__TOC__' ),
	'noeditsection'         => array( '0', '__NAOEDITARSECAO__', '__NÃOEDITARSEÇÃO__', '__SEMEDITARSEÇÃO__', '__SEMEDITARSECAO__', '__NOEDITSECTION__' ),
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
'tog-highlightbroken'         => 'Formatar links quebrados <a href="" class="new">como isto</a> (alternativa: como isto<a href="" class="internal">?</a>).',
'tog-justify'                 => 'Justificar parágrafos',
'tog-hideminor'               => 'Esconder edições secundárias nas mudanças recentes',
'tog-hidepatrolled'           => 'Esconder edições patrulhadas nas mudanças recentes',
'tog-newpageshidepatrolled'   => 'Esconder páginas patrulhadas da lista de páginas novas',
'tog-extendwatchlist'         => 'Expandir a lista de páginas vigiadas para mostrar todas as alterações aplicáveis, não apenas as mais recentes',
'tog-usenewrc'                => 'Utilizar mudanças recentes melhoradas (requer JavaScript)',
'tog-numberheadings'          => 'Auto-numerar cabeçalhos',
'tog-showtoolbar'             => 'Mostrar barra de edição (JavaScript)',
'tog-editondblclick'          => 'Editar páginas quando houver clique duplo (JavaScript)',
'tog-editsection'             => 'Habilitar edição de seção via links [editar]',
'tog-editsectiononrightclick' => 'Habilitar edição de seção por clique com o botão direito no título da seção (JavaScript)',
'tog-showtoc'                 => 'Mostrar Tabela de Conteúdos (para páginas com mais de três cabeçalhos)',
'tog-rememberpassword'        => 'Lembrar senha entre sessões',
'tog-editwidth'               => 'Alargar a caixa de edição para preecher a tela inteira',
'tog-watchcreations'          => 'Adicionar páginas criadas por mim à minha lista de páginas vigiadas',
'tog-watchdefault'            => 'Adicionar páginas editadas por mim à minha lista de páginas vigiadas',
'tog-watchmoves'              => 'Adicionar páginas movidas por mim à minha lista de páginas vigiadas',
'tog-watchdeletion'           => 'Adicionar páginas eliminadas por mim à minha lista de páginas vigiadas',
'tog-minordefault'            => 'Marcar todas as edições como secundárias, por padrão',
'tog-previewontop'            => 'Mostrar previsão antes da caixa de edição',
'tog-previewonfirst'          => 'Mostrar previsão na primeira edição',
'tog-nocache'                 => "Desativar ''caching'' de páginas",
'tog-enotifwatchlistpages'    => 'Enviar-me um email quando uma página da minha lista de páginas vigiadas for alterada',
'tog-enotifusertalkpages'     => 'Enviar-me um email quando a minha página de discussão for editada',
'tog-enotifminoredits'        => 'Enviar-me um email também quando forem edições menores',
'tog-enotifrevealaddr'        => 'Revelar o meu endereço de email nas notificações',
'tog-shownumberswatching'     => 'Mostrar o número de usuários que estão vigiando',
'tog-oldsig'                  => 'Previsão da assinatura existente:',
'tog-fancysig'                => 'Tratar assinatura como wikitexto (sem link automático)',
'tog-externaleditor'          => 'Utilizar editor externo por padrão (apenas para usuários avançados, já que serão necessárias configurações adicionais em seus computadores)',
'tog-externaldiff'            => 'Utilizar diferenças externas por padrão (apenas para usuários avançados, já que serão necessárias configurações adicionais em seus computadores)',
'tog-showjumplinks'           => 'Ativar hiperligações de acessibilidade "ir para"',
'tog-uselivepreview'          => 'Utilizar pré-visualização em tempo real (JavaScript) (Experimental)',
'tog-forceeditsummary'        => 'Avisar-me ao introduzir um sumário vazio',
'tog-watchlisthideown'        => 'Esconder as minhas edições da lista de páginas vigiadas',
'tog-watchlisthidebots'       => 'Esconder edições efetuadas por robôs da lista de páginas vigiadas',
'tog-watchlisthideminor'      => 'Esconder edições menores da lista de páginas vigiadas',
'tog-watchlisthideliu'        => 'Ocultar edições de usuários autenticados da lista de páginas vigiadas',
'tog-watchlisthideanons'      => 'Ocultar edições de usuários anônimos da lista de páginas vigiadas',
'tog-watchlisthidepatrolled'  => 'Esconder edições patrulhadas na lista de páginas vigiadas',
'tog-nolangconversion'        => 'Desabilitar conversão de variantes de idioma',
'tog-ccmeonemails'            => 'Enviar para mim cópias de e-mails que eu enviar a outros usuários',
'tog-diffonly'                => 'Não mostrar o conteúdo da página ao comparar duas edições',
'tog-showhiddencats'          => 'Exibir categorias ocultas',
'tog-noconvertlink'           => 'Desabilitar conversão de títulos de links',
'tog-norollbackdiff'          => 'Omitir diferenças depois de desfazer edições em bloco',

'underline-always'  => 'Sempre',
'underline-never'   => 'Nunca',
'underline-default' => 'Padrão do navegador',

# Font style option in Special:Preferences
'editfont-style'     => 'Estilo da fonte para a região de edição:',
'editfont-default'   => 'Padrão do navegador',
'editfont-monospace' => 'Fonte mono-espaçada',
'editfont-sansserif' => 'Fonte sem serifa',
'editfont-serif'     => 'Serifada',

# Dates
'sunday'        => 'domingo',
'monday'        => 'segunda-feira',
'tuesday'       => 'terça-feira',
'wednesday'     => 'quarta-feira',
'thursday'      => 'quinta-feira',
'friday'        => 'sexta-feira',
'saturday'      => 'sábado',
'sun'           => 'Dom',
'mon'           => 'Seg',
'tue'           => 'Ter',
'wed'           => 'Qua',
'thu'           => 'Qui',
'fri'           => 'Sex',
'sat'           => 'Sáb',
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
'category-media-header'          => 'Multimídia na categoria "$1"',
'category-empty'                 => "''No momento esta categoria não possui nenhuma página ou arquivo multimídia.''",
'hidden-categories'              => '{{PLURAL:$1|Categoria oculta|Categorias ocultas}}',
'hidden-category-category'       => 'Categorias ocultas',
'category-subcat-count'          => '{{PLURAL:$2|Esta categoria possui apenas a subcategoria a seguir.|Há, nesta categoria {{PLURAL:$1|uma subcategoria|$1 subcategorias}} (dentre um total de $2).}}',
'category-subcat-count-limited'  => 'Esta categoria possui {{PLURAL:$1|a seguinte sub-categoria|as $1 sub-categorias a seguir}}.',
'category-article-count'         => '{{PLURAL:$2|Esta categoria possui apenas a página a seguir.|Há, nesta categoria, {{PLURAL:$1|a página a seguir|as $1 páginas a seguir}} (dentre um total de $2).}}',
'category-article-count-limited' => 'Há, nesta categoria, {{PLURAL:$1|a página a seguir|as $1 páginas a seguir}}.',
'category-file-count'            => '{{PLURAL:$2|Esta categoria possui apenas o arquivo a seguir.|Há, nesta categoria, {{PLURAL:$1|o arquivo a seguir|os $1 seguintes arquivos}} (dentre um total de $2.)}}',
'category-file-count-limited'    => 'Nesta categoria há {{PLURAL:$1|um arquivo|$1 arquivos}}.',
'listingcontinuesabbrev'         => 'cont.',
'index-category'                 => 'Páginas indexadas',
'noindex-category'               => 'Páginas não indexadas',

'mainpagetext'      => "'''MediaWiki instalado com sucesso.'''",
'mainpagedocfooter' => 'Consulte o [http://meta.wikimedia.org/wiki/Help:Contents Manual de Usuário] para informações de como usar o software wiki.

== Começando ==

* [http://www.mediawiki.org/wiki/Manual:Configuration_settings Lista de opções de configuração]
* [http://www.mediawiki.org/wiki/Manual:FAQ FAQ do MediaWiki]
* [https://lists.wikimedia.org/mailman/listinfo/mediawiki-announce Lista de discussão com avisos de novas versões do MediaWiki]',

'about'         => 'Sobre',
'article'       => 'Página de conteúdo',
'newwindow'     => '(abre em uma nova janela)',
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
'vector-action-addsection'   => 'Adicionar tópico',
'vector-action-delete'       => 'Eliminar',
'vector-action-move'         => 'Mover',
'vector-action-protect'      => 'Proteger',
'vector-action-undelete'     => 'Restaurar',
'vector-action-unprotect'    => 'Desproteger',
'vector-namespace-category'  => 'Categoria',
'vector-namespace-help'      => 'Página de ajuda',
'vector-namespace-image'     => 'Arquivo',
'vector-namespace-main'      => 'Página',
'vector-namespace-media'     => 'Página de mídia',
'vector-namespace-mediawiki' => 'Mensagem',
'vector-namespace-project'   => 'Página de projeto',
'vector-namespace-special'   => 'Página especial',
'vector-namespace-talk'      => 'Discussão',
'vector-namespace-template'  => 'Predefinição',
'vector-namespace-user'      => 'Página de usuário',
'vector-view-create'         => 'Criar',
'vector-view-edit'           => 'Editar',
'vector-view-history'        => 'Ver histórico',
'vector-view-view'           => 'Ler',
'vector-view-viewsource'     => 'Ver código-fonte',
'actions'                    => 'Ações',
'namespaces'                 => 'Espaços nominais',
'variants'                   => 'Variantes',

'errorpagetitle'    => 'Erro',
'returnto'          => 'Retornar para $1.',
'tagline'           => 'De {{SITENAME}}',
'help'              => 'Ajuda',
'search'            => 'Pesquisa',
'searchbutton'      => 'Pesquisa',
'go'                => 'Ir',
'searcharticle'     => 'Ir',
'history'           => 'Histórico da página',
'history_short'     => 'Histórico',
'updatedmarker'     => 'atualizado desde a minha última visita',
'info_short'        => 'Informação',
'printableversion'  => 'Versão para impressão',
'permalink'         => 'Link permanente',
'print'             => 'Imprimir',
'edit'              => 'Editar',
'create'            => 'Criar',
'editthispage'      => 'Editar esta página',
'create-this-page'  => 'Iniciar esta página',
'delete'            => 'Eliminar',
'deletethispage'    => 'Eliminar esta página',
'undelete_short'    => 'Restaurar {{PLURAL:$1|uma edição|$1 edições}}',
'protect'           => 'Proteger',
'protect_change'    => 'alterar',
'protectthispage'   => 'Proteger esta página',
'unprotect'         => 'Desproteger',
'unprotectthispage' => 'Desproteger esta página',
'newpage'           => 'Nova página',
'talkpage'          => 'Dialogar sobre esta página',
'talkpagelinktext'  => 'disc',
'specialpage'       => 'Página especial',
'personaltools'     => 'Ferramentas pessoais',
'postcomment'       => 'Nova seção',
'articlepage'       => 'Ver página de conteúdo',
'talk'              => 'Discussão',
'views'             => 'Visualizações',
'toolbox'           => 'Ferramentas',
'userpage'          => 'Ver página de usuário',
'projectpage'       => 'Ver página de projeto',
'imagepage'         => 'Ver página do arquivo',
'mediawikipage'     => 'Ver página de mensagens',
'templatepage'      => 'Ver página de predefinições',
'viewhelppage'      => 'Ver página de ajuda',
'categorypage'      => 'Ver página de categorias',
'viewtalkpage'      => 'Ver discussão',
'otherlanguages'    => 'Outras línguas',
'redirectedfrom'    => '(Redirecionado de <b>$1</b>)',
'redirectpagesub'   => 'Página de redirecionamento',
'lastmodifiedat'    => 'Esta página foi modificada pela última vez às $2 de $1.',
'viewcount'         => 'Esta página foi acessada {{PLURAL:$1|uma vez|$1 vezes}}.',
'protectedpage'     => 'Página protegida',
'jumpto'            => 'Ir para:',
'jumptonavigation'  => 'navegação',
'jumptosearch'      => 'pesquisa',
'view-pool-error'   => 'Desculpe-nos, os servidores estão sobrecarregados neste momento.
Muitos usuários estão tentando ver esta página.
Aguarde um instante antes de tentar acessar esta página novamente.

$1',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'            => 'Sobre {{SITENAME}}',
'aboutpage'            => 'Project:Sobre',
'copyright'            => 'Conteúdo disponível sob $1.',
'copyrightpage'        => '{{ns:project}}:Direitos_de_autor',
'currentevents'        => 'Eventos atuais',
'currentevents-url'    => 'Project:Eventos atuais',
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
'badaccess-group0' => 'Você não está autorizado a executar a ação requisitada.',
'badaccess-groups' => 'A ação que você tentou executar está limitada a usuários {{PLURAL:$2|do grupo|de um dos seguintes grupos}}: $1.',

'versionrequired'     => 'É necessária a versão $1 do MediaWiki',
'versionrequiredtext' => 'Esta página requer a versão $1 do MediaWiki para ser utilizada.
Veja a [[Special:Version|página sobre a versão do sistema]].',

'ok'                      => 'OK',
'retrievedfrom'           => 'Obtida de "$1"',
'youhavenewmessages'      => 'Você tem $1 ($2).',
'newmessageslink'         => 'novas mensagens',
'newmessagesdifflink'     => 'comparar com a penúltima revisão',
'youhavenewmessagesmulti' => 'Você tem novas mensagens em $1',
'editsection'             => 'editar',
'editold'                 => 'editar',
'viewsourceold'           => 'ver código-fonte',
'editlink'                => 'editar',
'viewsourcelink'          => 'ver código-fonte',
'editsectionhint'         => 'Editar seção: $1',
'toc'                     => 'Tabela de conteúdo',
'showtoc'                 => 'mostrar',
'hidetoc'                 => 'esconder',
'thisisdeleted'           => 'Ver ou restaurar $1?',
'viewdeleted'             => 'Ver $1?',
'restorelink'             => '{{PLURAL:$1|uma edição eliminada|$1 edições eliminadas}}',
'feedlinks'               => 'Feed:',
'feed-invalid'            => 'Tipo inválido de inscrição de feeds.',
'feed-unavailable'        => 'Os "feeds" não se encontram disponíveis',
'site-rss-feed'           => 'Feed RSS $1',
'site-atom-feed'          => 'Feed Atom $1',
'page-rss-feed'           => 'Feed RSS de "$1"',
'page-atom-feed'          => 'Feed Atom de "$1"',
'red-link-title'          => '$1 (página inexistente)',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'Página',
'nstab-user'      => 'Página de usuário',
'nstab-media'     => 'Página de mídia',
'nstab-special'   => 'Página especial',
'nstab-project'   => 'Página de projeto',
'nstab-image'     => 'Arquivo',
'nstab-mediawiki' => 'Mensagem',
'nstab-template'  => 'Predefinição',
'nstab-help'      => 'Página de ajuda',
'nstab-category'  => 'Categoria',

# Main script and global functions
'nosuchaction'      => 'Ação inexistente',
'nosuchactiontext'  => 'A ação especificada pela URL é inválida.
Você deve ter se enganado ao digitar a URL, ou seguiu um link incorreto.
Isto também pode indicar um erro no software usado no sítio {{SITENAME}}.',
'nosuchspecialpage' => 'Esta página especial não existe',
'nospecialpagetext' => '<strong>Você requisitou uma página especial inválida.</strong>

Uma lista de páginas especiais válidas poderá ser encontrada em [[Special:SpecialPages|{{int:specialpages}}]].',

# General errors
'error'                => 'Erro',
'databaseerror'        => 'Erro no banco de dados',
'dberrortext'          => 'Ocorreu um erro de sintaxe de busca no banco de dados.
Isto pode indicar um problema com o \'\'software\'\'.
A última tentativa de busca no banco de dados foi:
<blockquote><tt>$1</tt></blockquote>
na função "<tt>$2</tt>".
O banco de dados retornou o erro "<tt>$3: $4</tt>".',
'dberrortextcl'        => 'Ocorreu um erro de sintaxe de busca no banco de dados.
A última tentativa de busca no banco de dados foi:
"$1"
na função "$2".
O banco de dados retornou o erro "$3: $4".',
'laggedslavemode'      => 'Aviso: a página poderá não conter atualizações recentes.',
'readonly'             => 'Banco de dados disponível no modo "somente leitura"',
'enterlockreason'      => 'Entre com um motivo para trancá-lo, incluindo uma estimativa de quando poderá novamente ser destrancado',
'readonlytext'         => 'O banco de dados da {{SITENAME}} está atualmente bloqueado para novas entradas e outras modificações, provavelmente por uma manutenção rotineira; mais tarde voltará ao normal.

Quem fez o bloqueio oferece a seguinte explicação: $1',
'missing-article'      => 'O banco de dados não encontrou o texto de uma página que deveria ter encontrado, com o nome "$1" $2.

Isto geralmente é causado pelo seguimento de uma ligação de diferença desatualizada ou de história de uma página que foi removida.

Se não for este o caso, você pode ter encontrado um defeito no software.
Por favor, reporte este fato a um administrador, fazendo notar a URL.',
'missingarticle-rev'   => '(revisão#: $1)',
'missingarticle-diff'  => '(Dif.: $1, $2)',
'readonly_lag'         => 'O banco de dados foi automaticamente bloqueado enquanto os servidores secundários se sincronizam com o principal',
'internalerror'        => 'Erro interno',
'internalerror_info'   => 'Erro interno: $1',
'fileappenderrorread'  => 'Não foi possível ler "$1" durante a anexação.',
'fileappenderror'      => 'Não foi possível adicionar "$1" a "$2".',
'filecopyerror'        => 'Não foi possível copiar o arquivo "$1" para "$2".',
'filerenameerror'      => 'Não foi possível renomear o arquivo "$1" para "$2".',
'filedeleteerror'      => 'Não foi possível eliminar o arquivo "$1".',
'directorycreateerror' => 'Não foi possível criar o diretório "$1".',
'filenotfound'         => 'Não foi possível encontrar o arquivo "$1".',
'fileexistserror'      => 'Não foi possível gravar no arquivo "$1": ele já existe',
'unexpected'           => 'Valor não esperado: "$1"="$2".',
'formerror'            => 'Erro: Não foi possível enviar o formulário',
'badarticleerror'      => 'Esta ação não pode ser realizada nesta página.',
'cannotdelete'         => 'Não foi possível eliminar a página ou arquivo $1.
É possível que ele já tenha sido eliminado por outra pessoa.',
'badtitle'             => 'Título inválido',
'badtitletext'         => 'O título de página solicitado era inválido, vazio, ou um link interlínguas ou interwikis incorreto.
Talvez contenha um ou mais caracteres que não podem ser usados em títulos.',
'perfcached'           => 'Os dados seguintes encontram-se na cache e podem não estar atualizados.',
'perfcachedts'         => 'Os seguintes dados encontram-se armazenados na cache e foram atualizados pela última vez às $1.',
'querypage-no-updates' => 'Momentaneamente as atualizações para esta página estão desativadas. Por enquanto, os dados aqui presentes não poderão ser atualizados.',
'wrong_wfQuery_params' => 'Parâmetros incorretos para wfQuery()<br />
Função: $1<br />
Consulta: $2',
'viewsource'           => 'Ver código-fonte',
'viewsourcefor'        => 'para $1',
'actionthrottled'      => 'Ação controlada',
'actionthrottledtext'  => 'Como medida "anti-spam", você se encontra impedido de realizar esta operação muitas vezes em um curto espaço de tempo; você já excedeu esse limite. 
Tente novamente em alguns minutos.',
'protectedpagetext'    => 'Esta página foi protegida contra novas edições.',
'viewsourcetext'       => 'Você pode ver e copiar o código desta página:',
'protectedinterface'   => 'Esta página fornece texto de interface ao software e encontra-se trancada para prevenir abusos.',
'editinginterface'     => "'''Aviso:''' Você se encontra prestes a editar uma página que é utilizada para fornecer texto de interface ao software. Alterações nesta página irão afetar a aparência da interface de usuário para outros usuários. Para traduções, considere utilizar a [http://translatewiki.net/wiki/Main_Page?setlang=pt-br translatewiki.net], um projeto destinado para a tradução do MediaWiki.",
'sqlhidden'            => '(Consulta SQL em segundo-plano)',
'cascadeprotected'     => 'Esta página foi protegida contra edições por estar incluída {{PLURAL:$1|na página listada|nas páginas listadas}} a seguir, ({{PLURAL:$1|página essa que está protegida|páginas essas que estão protegidas}} com a opção de "proteção progressiva" ativada):
$2',
'namespaceprotected'   => "Você não possui permissão para editar páginas no espaço nominal '''$1'''.",
'customcssjsprotected' => 'Você não possui permissão de editar esta página, já que ela contém configurações pessoais de outro usuário.',
'ns-specialprotected'  => 'Não é possível editar páginas especiais',
'titleprotected'       => "Este título foi protegido, para que não seja criado.
Quem o protegeu foi [[User:$1|$1]], com a justificativa: ''$2''.",

# Virus scanner
'virus-badscanner'     => "Má configuração: antivírus desconhecido: ''$1''",
'virus-scanfailed'     => 'a verificação falhou (código $1)',
'virus-unknownscanner' => 'antivírus desconhecido:',

# Login and logout pages
'logouttext'                 => "'''Agora você encontra-se desautenticado.'''

É possível continuar usando {{SITENAME}} anonimamente ou [[Special:UserLogin|autenticar-se novamente]] com o mesmo nome de usuário ou com um nome diferente.
Note que algumas páginas podem continuar sendo exibidas como se você ainda estivesse autenticado até que você limpe a ''cache'' do seu navegador.",
'welcomecreation'            => '== Bem-vindo, $1! ==
A sua conta foi criada.
Não se esqueça de personalizar as suas [[Special:Preferences|preferências na {{SITENAME}}]].',
'yourname'                   => 'Nome de usuário:',
'yourpassword'               => 'Senha:',
'yourpasswordagain'          => 'Redigite sua senha',
'remembermypassword'         => 'Lembrar da minha senha em outras sessões.',
'yourdomainname'             => 'Seu domínio:',
'externaldberror'            => 'Ocorreu ou um erro no banco de dados durante a autenticação ou não lhe é permitido atualizar a sua conta externa.',
'login'                      => 'Autenticar-se',
'nav-login-createaccount'    => 'Criar uma conta ou entrar',
'loginprompt'                => 'É necessário estar com os <i>cookies</i> ativados para poder autenticar-se na {{SITENAME}}.',
'userlogin'                  => 'Criar uma conta ou entrar',
'userloginnocreate'          => 'Autenticar-se',
'logout'                     => 'Sair',
'userlogout'                 => 'Sair',
'notloggedin'                => 'Não autenticado',
'nologin'                    => 'Não possui uma conta? $1.',
'nologinlink'                => 'Criar uma conta',
'createaccount'              => 'Criar nova conta',
'gotaccount'                 => "Já possui uma conta? '''$1'''.",
'gotaccountlink'             => 'Autenticar-se',
'createaccountmail'          => 'por e-mail',
'badretype'                  => 'As senhas que você digitou não são iguais.',
'userexists'                 => 'O nome de usuário que você digitou já existe.
Escolha um nome diferente.',
'loginerror'                 => 'Erro de autenticação',
'createaccounterror'         => 'Não foi possível criar a conta: $1',
'nocookiesnew'               => "A conta do usuário foi criada, mas você não foi autenticado.
{{SITENAME}} utiliza ''cookies'' para autenticar os usuários.
Você tem os ''cookies'' desativados no seu navegador.
Por favor ative-os, depois autentique-se com o seu novo nome de usuário e a sua senha.",
'nocookieslogin'             => 'Você tem os <i>cookies</i> desativados no seu navegador, e a {{SITENAME}} utiliza <i>cookies</i> para ligar os usuários às suas contas. Por favor os ative e tente novamente.',
'noname'                     => 'Você não colocou um nome de usuário válido.',
'loginsuccesstitle'          => 'Login bem sucedido',
'loginsuccess'               => "'''Agora você está ligado à {{SITENAME}} como \"\$1\"'''.",
'nosuchuser'                 => 'Não existe nenhum usuário com o nome "$1".
Os nomes de usuário são sensíveis à capitalização.
Verifique a ortografia, ou [[Special:UserLogin/signup|crie uma nova conta]].',
'nosuchusershort'            => 'Não existe um usuário com o nome "<nowiki>$1</nowiki>". Verifique o nome que introduziu.',
'nouserspecified'            => 'Você precisa especificar um nome de usuário.',
'login-userblocked'          => 'Este usuário está bloqueado. Entrada proibida.',
'wrongpassword'              => 'A senha que introduziu é inválida. Por favor, tente novamente.',
'wrongpasswordempty'         => 'A senha introduzida está em branco. Por favor, tente novamente.',
'passwordtooshort'           => 'As senhas devem ter no mínimo {{PLURAL:$1|1 caractere|$1 caracteres}}.',
'password-name-match'        => 'A sua senha deve ser diferente do seu nome de usuário.',
'mailmypassword'             => 'Enviar uma nova senha por e-mail',
'passwordremindertitle'      => 'Nova senha temporária em {{SITENAME}}',
'passwordremindertext'       => 'Alguém (provavelmente você, a partir do endereço de IP $1) solicitou uma nova senha para {{SITENAME}} ($4). Foi criada uma senha temporária para o usuário "$2", sendo ela "$3". Se esta era sua intenção, você precisará se autenticar e escolher uma nova senha agora.
A sua senha temporária expirará em {{PLURAL:$5|um dia|$5 dias}}.

Se foi outra pessoa quem fez este pedido, ou se você já lembrou a sua senha, e não quer mais alterá-la, você pode ignorar esta mensagem e continuar utilizando sua senha antiga.',
'noemail'                    => 'Não há um endereço de e-mail associado ao usuário "$1".',
'noemailcreate'              => 'Você precisa fornecer um endereço de e-mail válido',
'passwordsent'               => 'Uma nova senha está sendo enviada para o endereço de e-mail registrado para "$1".
Por favor, reconecte-se ao recebê-lo.',
'blocked-mailpassword'       => 'O seu endereço de IP foi bloqueado de editar e, portanto, não será possível utilizar o lembrete de senha (para serem evitados envios abusivos a outras pessoas).',
'eauthentsent'               => 'Uma mensagem de confirmação foi enviada para o endereço de e-mail fornecido.
Antes de qualquer outro e-mail ser enviado para a sua conta, você precisará seguir as instruções da mensagem, de modo a confirmar que a conta é mesmo sua.',
'throttled-mailpassword'     => 'Um lembrete de senha já foi enviado {{PLURAL:$1|na última hora|nas últimas $1 horas}}.
Para prevenir abusos, apenas um lembrete poderá ser enviado a cada {{PLURAL:$1|hora|$1 horas}}.',
'mailerror'                  => 'Erro a enviar o email: $1',
'acct_creation_throttle_hit' => 'Visitantes deste wiki utilizando o seu endereço IP criaram {{PLURAL:$1|1 conta|$1 contas}} no último dia, o que é o máximo permitido neste período de tempo.
Como resultado, visitantes que usam este endereço IP não podem criar mais nenhuma conta no momento.',
'emailauthenticated'         => 'O seu endereço de e-mail foi autenticado às $3 de $2.',
'emailnotauthenticated'      => 'O seu endereço de e-mail ainda não foi autenticado. Não lhe será enviado nenhum e-mail sobre nenhuma das seguintes funcionalidades.',
'noemailprefs'               => 'Especifique um endereço de e-mail para que os seguintes recursos funcionem.',
'emailconfirmlink'           => 'Confirme o seu endereço de e-mail',
'invalidemailaddress'        => "O endereço de ''e-mail'' não pode ser aceite devido a talvez possuir um formato inválido. Por favor, introduza um endereço bem formatado ou esvazie o campo.",
'accountcreated'             => 'Conta criada',
'accountcreatedtext'         => 'A conta do usuário para $1 foi criada.',
'createaccount-title'        => 'Criação de conta em {{SITENAME}}',
'createaccount-text'         => 'Alguém criou uma conta de nome $2 para o seu endereço de email no wiki {{SITENAME}} ($4), tendo como senha #$3". Você deve se autenticar e alterar sua senha.

Você pode ignorar esta mensagem caso a conta tenha sido criada por engano.',
'usernamehasherror'          => 'Nome de usuário não pode conter o símbolo de cardinal (#).',
'login-throttled'            => 'Você fez tentativas demais de se autenticar com esta conta recentemente.
Por favor aguarde antes de tentar novamente.',
'loginlanguagelabel'         => 'Idioma: $1',
'suspicious-userlogout'      => 'Sua solicitação para sair foi negada porque aparentemente foi enviada por um navegador danificado ou por um servidor proxy com cache.',

# Password reset dialog
'resetpass'                 => 'Alterar senha',
'resetpass_announce'        => 'Você foi autenticado através de uma senha temporária. Para prosseguir, será necessário definir uma nova senha.',
'resetpass_text'            => '<!-- Adicionar texto aqui -->',
'resetpass_header'          => 'Alterar a senha da conta',
'oldpassword'               => 'Senha antiga',
'newpassword'               => 'Nova senha',
'retypenew'                 => 'Reintroduza a nova senha',
'resetpass_submit'          => 'Definir senha e entrar',
'resetpass_success'         => 'Sua senha foi alterada com sucesso! Autenticando-se...',
'resetpass_forbidden'       => 'As senhas não podem ser alteradas',
'resetpass-no-info'         => 'Você precisa estar autenticado para acessar esta página diretamente.',
'resetpass-submit-loggedin' => 'Alterar senha',
'resetpass-submit-cancel'   => 'Cancelar',
'resetpass-wrong-oldpass'   => 'Senha temporária ou atual inválida. 
Você pode já ter alterado com sucesso a sua senha, ou solicitado uma nova senha temporária.',
'resetpass-temp-password'   => 'Senha temporária:',

# Edit page toolbar
'bold_sample'     => 'Texto em negrito',
'bold_tip'        => 'Texto em negrito',
'italic_sample'   => 'Texto em itálico',
'italic_tip'      => 'Texto em itálico',
'link_sample'     => 'Título do link',
'link_tip'        => 'Link interno',
'extlink_sample'  => 'http://www.example.com título do link',
'extlink_tip'     => 'Link externo (lembre-se do prefixo http://)',
'headline_sample' => 'Texto do cabeçalho',
'headline_tip'    => 'Seção de nível 2',
'math_sample'     => 'Inserir fórmula aqui',
'math_tip'        => 'Fórmula matemática (LaTeX)',
'nowiki_sample'   => 'Inserir texto não-formatado aqui',
'nowiki_tip'      => 'Ignorar formato wiki',
'image_sample'    => 'Exemplo.jpg',
'image_tip'       => 'Arquivo embutido',
'media_sample'    => 'Exemplo.ogg',
'media_tip'       => 'Link para arquivo',
'sig_tip'         => 'Sua assinatura, com hora e data',
'hr_tip'          => 'Linha horizontal (use de forma moderada)',

# Edit pages
'summary'                          => 'Sumário:',
'subject'                          => 'Assunto/cabeçalho:',
'minoredit'                        => 'Marcar como edição menor',
'watchthis'                        => 'Vigiar esta página',
'savearticle'                      => 'Salvar página',
'preview'                          => 'Previsualização',
'showpreview'                      => 'Mostrar previsão',
'showlivepreview'                  => 'Pré-visualização em tempo real',
'showdiff'                         => 'Mostrar alterações',
'anoneditwarning'                  => "'''Atenção''': Você não se encontra autenticado. O seu endereço de IP será registrado no histórico de edições desta página.",
'missingsummary'                   => "'''Lembrete:''' Você não introduziu um sumário de edição. Se clicar novamente em Salvar, a sua edição será salva sem um sumário.",
'missingcommenttext'               => 'Por favor, introduzida um comentário abaixo.',
'missingcommentheader'             => "'''Lembrete:''' Você não introduziu um assunto/título para este comentário. Se carregar novamente em Salvar a sua edição será salva sem um título/assunto.",
'summary-preview'                  => 'Previsão de sumário:',
'subject-preview'                  => 'Previsão do assunto/título:',
'blockedtitle'                     => 'O usuário está bloqueado',
'blockedtext'                      => "'''O seu nome de usuário ou endereço de IP foi bloqueado.'''

O bloqueio foi realizado por \$1.
O motivo apresentado foi ''\$2''.

* Início do bloqueio: \$8
* Expiração do bloqueio: \$6
* Destino do bloqueio: \$7

Você pode contatar \$1 ou outro [[{{MediaWiki:Grouppage-sysop}}|administrador]] para discutir sobre o bloqueio.

Você só poderá utilizar a funcionalidade \"Contatar usuário\" se um endereço de ''e-mail'' válido estiver especificado em suas [[Special:Preferences|preferências de usuário]] e você não tiver sido bloqueado de utilizar tal recurso.
O seu endereço de IP atual é \$3 e a ID de bloqueio é #\$5.
Por favor, inclua todos os detalhes acima em quaisquer tentativas de esclarecimento.",
'autoblockedtext'                  => 'O seu endereço de IP foi bloqueado de forma automática, uma vez que foi utilizado recentemente por outro usuário, o qual foi bloqueado por $1.
O motivo apresentado foi:

:\'\'$2\'\'

* Início do bloqueio: $8
* Expiração do bloqueio: $6
* Destino do bloqueio: $7

Você pode contatar $1 ou outro [[{{MediaWiki:Grouppage-sysop}}|administrador]] para discutir sobre o bloqueio.

Note que não poderá utilizar a funcionalidade "Contatar usuário" se não possuir uma conta neste wiki ({{SITENAME}}) com um endereço de \'\'e-mail\'\' válido indicado nas suas [[Special:Preferences|preferências de usuário]] ou se tiver sido bloqueado de utilizar tal recurso.

Seu endereço de IP no momento é $3 e sua ID de bloqueio é #$5.
Por favor, inclua tais dados em qualquer tentativa de esclarecimentos que for realizar.',
'blockednoreason'                  => 'sem motivo especificado',
'blockedoriginalsource'            => "O código de '''$1''' é mostrado abaixo:",
'blockededitsource'                => "O texto das '''suas edições''' em '''$1''' é mostrado abaixo:",
'whitelistedittitle'               => 'É necessário autenticar-se para editar páginas',
'whitelistedittext'                => 'Você precisa $1 para poder editar páginas.',
'confirmedittext'                  => 'Você precisa confirmar o seu endereço de e-mail antes de começar a editar páginas.
Por favor, introduza um e valide-o através das suas [[Special:Preferences|preferências de usuário]].',
'nosuchsectiontitle'               => 'Não foi possível encontrar a seção',
'nosuchsectiontext'                => 'Você tentou editar uma seção que não existe.
Ela pode ter sido movida ou removido enquanto você estava vendo a página.',
'loginreqtitle'                    => 'Autenticação Requerida',
'loginreqlink'                     => 'autenticar-se',
'loginreqpagetext'                 => 'Você precisa de $1 para poder visualizar outras páginas.',
'accmailtitle'                     => 'Senha enviada.',
'accmailtext'                      => "Uma palavra-chave gerada aleatoriamente para [[User talk:$1|$1]] foi enviada para $2.

A palavra-chave para este nova conta pode ser alterada na página para ''[[Special:ChangePassword|alterar palavra-chave]]'' após a autenticação.",
'newarticle'                       => '(Nova)',
'newarticletext'                   => "Você seguiu um link para uma página que não existe.
Para criá-la, comece escrevendo na caixa abaixo
(veja [[{{MediaWiki:Helppage}}|a página de ajuda]] para mais informações).
Se você chegou aqui por engano, apenas clique no botão '''voltar''' do seu navegador.",
'anontalkpagetext'                 => "---- ''Esta é a página de discussão para um usuário anônimo que ainda não criou uma conta ou que não a usa, de forma que temos de utilizar o endereço de IP para identificá-lo(a). Tal endereço de IP pode ser compartilhado por vários usuários. Se você é um usuário anônimo e acha que comentários irrelevantes foram direcionados a você, por gentileza, [[Special:UserLogin/signup|crie uma conta]] ou [[Special:UserLogin|autentique-se]], a fim de evitar futuras confusões com outros usuários anônimos.''",
'noarticletext'                    => 'Atualmente não existe texto nesta página.
Você pode [[Special:Search/{{PAGENAME}}|pesquisar pelo título desta página]] em outras páginas <span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} buscar nos registros relacionados],
ou [{{fullurl:{{FULLPAGENAME}}|action=edit}} criar esta página]</span>.',
'noarticletext-nopermission'       => 'Não há actualmente texto nesta página.
Você pode [[Special:Search/{{PAGENAME}}|procurar este título de página]] em outras páginas,
ou <span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} procurar os registos relacionados] </span>.',
'userpage-userdoesnotexist'        => 'A conta "$1" não se encontra registrada. 
Verifique se deseja mesmo criar/editar esta página.',
'userpage-userdoesnotexist-view'   => 'A conta de usuário "$1" não está registrada.',
'blocked-notice-logextract'        => 'Este usuário está atualmente bloqueado.
O registro de bloqueio mais recente é fornecido abaixo, para referência:',
'clearyourcache'                   => "'''Nota - Depois de salvar, você terá de limpar a ''cache'' do seu navegador para ver as alterações.'''
'''Mozilla / Firefox / Safari:''' pressione ''Shift'' enquanto clica em ''Recarregar'', ou pressione ''Ctrl-F5'' ou ''Ctrl-R'' (''Command-R'' para Macintosh);
'''Konqueror:''' clique no botão ''Recarregar'' ou pressione ''F5'';
'''Opera:''' limpe a ''cache'' em ''Ferramentas → Preferências'' (''Tools → Preferences'');
'''Internet Explorer:''' pressione ''Ctrl'' enquanto clica em ''Recarregar'' ou pressione ''Ctrl-F5'';",
'usercssyoucanpreview'             => "'''Dica:''' Utilize o botão \"{{int:showpreview}}\" para testar seu novo CSS antes de salvar.",
'userjsyoucanpreview'              => "'''Dica:''' Utilize o botão \"{{int:showpreview}}\" para testar seu novo JavaScript antes de salvar.",
'usercsspreview'                   => "'''Lembre-se que está apenas prevendo o seu CSS particular.'''
'''Ele ainda não foi salvo!'''",
'userjspreview'                    => "'''Lembre-se que está apenas testando/prevendo o seu JavaScript particular e que ele ainda não foi salvo!'''",
'userinvalidcssjstitle'            => "'''Aviso:''' Não existe um tema \"\$1\". Lembre-se que as páginas .css e  .js utilizam um título em minúsculas, exemplo: {{ns:user}}:Alguém/monobook.css aposto a {{ns:user}}:Alguém/Monobook.css.",
'updated'                          => '(Atualizado)',
'note'                             => "'''Nota:'''",
'previewnote'                      => "'''Isto é apenas uma previsão.
As modificações ainda não foram salvas!'''",
'previewconflict'                  => 'Esta previsão reflete o texto que está na área de edição acima e como ele aparecerá se você escolher salvar.',
'session_fail_preview'             => "'''Pedimos desculpas, mas não foi possível processar a sua edição devido à perda de dados da sua sessão.
Por favor tente novamente.
Caso continue não funcionando, tente [[Special:UserLogout|sair]] e voltar a entrar na sua conta.'''",
'session_fail_preview_html'        => "'''Desculpe-nos! Não foi possível processar a sua edição devido a uma perda de dados de sessão.'''

''Como o projeto {{SITENAME}} possui HTML bruto ativo, a previsão não será exibida, como uma precaução contra ataques por JavaScript.''

'''Se esta é uma tentativa de edição legítima, por favor tente novamente.
Caso continue não funcionando, tente [[Special:UserLogout|desautenticar-se]] e voltar a entrar na sua conta.'''",
'token_suffix_mismatch'            => "'''A sua edição foi rejeitada uma vez que seu software de navegação mutilou os sinais de pontuação do sinal de edição. A edição foi rejeitada para evitar perdas no texto da página.
Isso acontece ocasionalmente quando se usa um serviço de proxy anonimizador mal configurado.'''",
'editing'                          => 'Editando $1',
'editingsection'                   => 'Editando $1 (seção)',
'editingcomment'                   => 'Editando $1 (nova seção)',
'editconflict'                     => 'Conflito de edição: $1',
'explainconflict'                  => 'Alguém mudou a página enquanto você a estava editando.
A área de texto acima mostra o texto original.
Suas mudanças são mostradas na área abaixo.
Você terá que mesclar suas modificações no texto existente.
<b>SOMENTE</b> o texto na área acima será salvo quando você pressionar "Salvar página".<br />',
'yourtext'                         => 'Seu texto',
'storedversion'                    => 'Versão guardada',
'nonunicodebrowser'                => "'''AVISO: O seu navegador não é compatível com as especificações unicode.'''
Um contorno terá de ser utilizado para permitir que você possa editar as páginas com segurança: os caracteres não-ASCII aparecerão na caixa de edição no formato de códigos hexadecimais.",
'editingold'                       => "'''CUIDADO: Você está editando uma revisão desatualizada desta página.'''
Se você salvá-la, todas as mudanças feitas a partir desta revisão serão perdidas.",
'yourdiff'                         => 'Diferenças',
'copyrightwarning'                 => "Por favor, note que todas as suas contribuições em {{SITENAME}} são consideradas como lançadas nos termos da licença $2 (veja $1 para detalhes). Se não deseja que o seu texto seja inexoravelmente editado e redistribuído de tal forma, não o envie.<br />
Você está, ao mesmo tempo, garantindo-nos que isto é algo escrito por você mesmo ou algo copiado de uma fonte de textos em domínio público ou similarmente de teor livre.
'''NÃO ENVIE TRABALHO PROTEGIDO POR DIREITOS AUTORAIS SEM A DEVIDA PERMISSÃO!'''",
'copyrightwarning2'                => "Por favor, note que todas as suas contribuições em {{SITENAME}} podem ser editadas, alteradas ou removidas por outros contribuidores. Se você não deseja que o seu texto seja inexoravelmente editado, não o envie.<br />
Você está, ao mesmo tempo, a garantir-nos que isto é algo escrito por si, ou algo copiado de alguma fonte de textos em domínio público ou similarmente de teor livre (veja $1 para detalhes).
'''NÃO ENVIE TRABALHO PROTEGIDO POR DIREITOS DE AUTOR SEM A DEVIDA PERMISSÃO!'''",
'longpagewarning'                  => "'''CUIDADO: Esta página tem $1 kilobytes; alguns browsers podem ter problemas ao editar páginas maiores que 32 kb.
Por gentileza, considere quebrar a página em sessões menores.'''",
'longpageerror'                    => "'''ERRO: O texto de página que você submeteu tem mais de $1 quilobytes em tamanho, que é maior que o máximo de $2 quilobytes. A página não pode ser salva.'''",
'readonlywarning'                  => "'''Aviso: A base de dados foi bloqueada para manutenção, por isso você não poderá salvar a sua edição neste momento.'''
Pode, no entanto, copiar o seu texto num editor externo e guardá-lo para posterior envio.

Quem bloqueou o banco de dados forneceu a seguinte justificativa: $1",
'protectedpagewarning'             => "'''Atenção: Esta página foi protegida para que apenas usuários com privilégios de administrado possam editá-la.'''
A última entrada no histórico é fornecida abaixo como referência:",
'semiprotectedpagewarning'         => "'''Nota:''' Esta página foi protegida, sendo que apenas usuários registrados poderão editá-la.
A última entrada no histórico é fornecida abaixo para referência:",
'cascadeprotectedwarning'          => "'''Atenção:''' Esta página se encontra protegida; apenas {{int:group-sysop}} podem editá-la, uma vez que se encontra incluída {{PLURAL:\$1|na seguinte página protegida|nas seguintes páginas protegidas}} com a \"proteção progressiva\":",
'titleprotectedwarning'            => "'''Atenção: esta página foi protegida; [[Special:ListGroupRights|privilégios específicos]] são necessários para criá-la.'''
A última entrada no histórico é fornecida abaixo como referência:",
'templatesused'                    => '{{PLURAL:$1|Predefinição usada|Predefinições usadas}} nesta página:',
'templatesusedpreview'             => '{{PLURAL:$1|Predefinição usada|Predefinições usadas}} nesta previsão:',
'templatesusedsection'             => '{{PLURAL:$1|Predefinição utilizada|Predefinições utilizadas}} nesta seção:',
'template-protected'               => '(protegida)',
'template-semiprotected'           => '(semi-protegida)',
'hiddencategories'                 => 'Esta página integra {{PLURAL:$1|uma categoria oculta|$1 categorias ocultas}}:',
'edittools'                        => '<!-- O texto aqui disponibilizado será exibido abaixo dos formulários de edição e de envio de arquivos. -->',
'nocreatetitle'                    => 'A criação de páginas se encontra limitada',
'nocreatetext'                     => '{{SITENAME}} tem restringida a habilidade de criar novas páginas.
Volte à tela anterior e edite uma página já existente, ou [[Special:UserLogin|autentique-se ou crie uma conta]].',
'nocreate-loggedin'                => 'Você não possui permissão para criar novas páginas.',
'sectioneditnotsupported-title'    => 'Edição por seções não suportada',
'sectioneditnotsupported-text'     => 'Edição por seções não suportada nesta página.',
'permissionserrors'                => 'Erros de permissões',
'permissionserrorstext'            => 'Você não possui permissão de fazer isso, {{PLURAL:$1|pelo seguinte motivo|pelos seguintes motivos}}:',
'permissionserrorstext-withaction' => 'Você não possui permissão para $2, {{PLURAL:$1|pelo seguinte motivo|pelos motivos a seguir}}:',
'recreate-moveddeleted-warn'       => "Atenção: Você está recriando uma página já eliminada em outra ocasião.'''

Você deve considerar se é realmente adequado continuar editando esta página.
Os registros de eliminação e de movimentação desta página são exibidos a seguir, para sua comodidade:",
'moveddeleted-notice'              => 'Esta página foi eliminada. 
Os registros de eliminação e de movimentação para esta página estão disponibilizados abaixo, para referência.',
'log-fulllog'                      => 'Ver registro detalhado',
'edit-hook-aborted'                => "Edição abortada por ''hook''.
Ele não deu nenhuma explicação.",
'edit-gone-missing'                => 'Não foi possível atualizar a página.
Ela parece ter sido eliminada.',
'edit-conflict'                    => 'Conflito de edição.',
'edit-no-change'                   => 'A sua edição foi ignorada, uma vez que o texto não sofreu alterações.',
'edit-already-exists'              => 'Não foi possível criar uma nova página.
Ela já existia.',

# Parser/template warnings
'expensive-parserfunction-warning'        => 'Aviso: Esta página contém muitas chamadas a funções do analisador "parser".

Deveria ter menos de $2 {{PLURAL:$2|chamada|chamadas}}. Neste momento {{PLURAL:$1|há $1 chamada|existem $1 chamadas}}.',
'expensive-parserfunction-category'       => 'Páginas com muitas chamadas a funções do analisador "parser"',
'post-expand-template-inclusion-warning'  => 'Aviso: O tamanho de inclusão de predefinições é muito grande, algumas predefinições não serão incluídas.',
'post-expand-template-inclusion-category' => 'Páginas onde o tamanho de inclusão de predefinições é excedido',
'post-expand-template-argument-warning'   => 'Aviso: Esta página contém pelo menos um argumento de predefinição com um tamanho muito grande.
Estes argumentos foram omitidos.',
'post-expand-template-argument-category'  => 'Páginas com omissões de argumentos em predefinições',
'parser-template-loop-warning'            => 'Ciclo de predefinições detectado: [[$1]]',
'parser-template-recursion-depth-warning' => 'O limite de profundidade de recursividade de predefinição foi ultrapassado ($1)',
'language-converter-depth-warning'        => 'O limite de profundidade do conversor de línguas excedeu a ($1)',

# "Undo" feature
'undo-success' => 'A edição pôde ser desfeita. Por gentileza, verifique o comparativo a seguir para se certificar de que é isto que deseja fazer, salvando as alterações após ter terminado de revisá-las.',
'undo-failure' => 'A edição não pôde ser desfeita devido a alterações intermediárias conflitantes.',
'undo-norev'   => 'A edição não pôde ser desfeita porque não existe ou foi apagada.',
'undo-summary' => 'Desfeita a edição $1 de [[Special:Contributions/$2|$2]] ([[User talk:$2|Discussão]])',

# Account creation failure
'cantcreateaccounttitle' => 'Não é possível criar uma conta',
'cantcreateaccount-text' => "Este IP ('''$1''') foi bloqueado de criar novas contas por [[User:$3|$3]].

A justificativa apresentada por $3 foi ''$2''",

# History pages
'viewpagelogs'           => 'Ver registros para esta página',
'nohistory'              => 'Não há histórico de revisões para esta página.',
'currentrev'             => 'Revisão atual',
'currentrev-asof'        => 'Edição atual tal como $1',
'revisionasof'           => 'Edição de $1',
'revision-info'          => 'Edição feita às $1 por $2',
'previousrevision'       => '← Versão anterior',
'nextrevision'           => 'Versão posterior →',
'currentrevisionlink'    => 'ver versão atual',
'cur'                    => 'atu',
'next'                   => 'prox',
'last'                   => 'ult',
'page_first'             => 'primeira',
'page_last'              => 'última',
'histlegend'             => "Seleção para diferenças: marque as caixas de seleção das versões que deseja comparar e clique no botão na parte inferior.<br />
Legenda: ''({{int:cur}})''' = diferença com relação a versão atual,
'''({{int:last}})''' = diferença com relação a versão anterior, '''{{int:minoreditletter}}''' = edição menor.",
'history-fieldset-title' => 'Navegar pelo histórico',
'history-show-deleted'   => 'Somente eliminados',
'histfirst'              => 'Mais antigas',
'histlast'               => 'Mais recentes',
'historysize'            => '({{PLURAL:$1|1 byte|$1 bytes}})',
'historyempty'           => '(vazio)',

# Revision feed
'history-feed-title'          => 'Histórico de revisão',
'history-feed-description'    => 'Histórico de revisões para esta página nesta wiki',
'history-feed-item-nocomment' => '$1 em $2',
'history-feed-empty'          => 'A página requisitada não existe.
Poderá ter sido eliminada da wiki ou renomeada.
Tente [[Special:Search|pesquisar na wiki]] por páginas relevantes.',

# Revision deletion
'rev-deleted-comment'         => '(comentário removido)',
'rev-deleted-user'            => '(nome de usuário removido)',
'rev-deleted-event'           => '(entrada removida)',
'rev-deleted-user-contribs'   => '[nome de usuário ou endereço de IP eliminado - edição ocultada das contribuições]',
'rev-deleted-text-permission' => "Esta revisão desta página foi '''eliminada'''.
Poderá haver detalhes no [{{fullurl:{{#Especial:Log}}/delete|page={{FULLPAGENAMEE}}}} registro de eliminação].",
'rev-deleted-text-unhide'     => "Esta revisão desta página foi '''removida'''.
Poderá haver detalhes no [{{fullurl:{{#Especial:Log}}/delete|page={{FULLPAGENAMEE}}}} registro de eliminação].
Como administrador, você ainda pode [$1 ver esta revisão] se desejar continuar.",
'rev-suppressed-text-unhide'  => "Esta revisão desta página foi '''removida'''.
Poderá haver detalhes no [{{fullurl:{{#Especial:Log}}/suppress|page={{FULLPAGENAMEE}}}} registro de eliminação].
Como administrador, você ainda pode [$1 ver esta revisão] se desejar continuar.",
'rev-deleted-text-view'       => "A revisão desta página foi '''eliminada'''.
Como administrador, você pode visualizá-la; poderá haver detalhes no [{{fullurl:{{#Especial:Log}}/delete|page={{FULLPAGENAMEE}}}} registro de eliminação].",
'rev-suppressed-text-view'    => "A revisão desta página foi '''eliminada'''.
Como administrador, você pode visualizá-la; poderá haver detalhes no [{{fullurl:{{#Especial:Log}}/suppress|page={{FULLPAGENAMEE}}}} registro de eliminação].",
'rev-deleted-no-diff'         => "Você não pode ver estas diferenças porque uma das revisões foi '''eliminada'''.
Poderá haver detalhes no [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} registro de eliminação].",
'rev-suppressed-no-diff'      => "Você não pode ver esta comparação porque uma das revisões foi '''eliminada'''.",
'rev-deleted-unhide-diff'     => "Uma das revisões destas diferenças foi '''eliminada'''.
Poderá haver detalhes no [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} registo de supressão].
Por ser um administrador, você ainda pode [$1 ver estas diferenças], se desejar prosseguir.",
'rev-suppressed-unhide-diff'  => "Uma das revisões deste diferencial foi '''suprimido'''.
Podem haver detalhes no [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} registo de supressão].
Como administrador você pode ainda [$1 pode ver o diferencial] se desejar prosseguir.",
'rev-deleted-diff-view'       => "Uma das revisões desta comparação foi '''removida'''.
Como administrador você ainda pode ver esta comparação; detalhes podem ser contrados no [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} registro de remoção].",
'rev-suppressed-diff-view'    => "Uma das revisões desta comparação foi '''suprimida''''.
Como administrador você pode ver esta comparação; detalhes podem ser encontradas no [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} registro de supressão].",
'rev-delundel'                => 'mostrar/esconder',
'rev-showdeleted'             => 'exibir',
'revisiondelete'              => 'Eliminar/restaurar edições',
'revdelete-nooldid-title'     => 'Nenhuma revisão selecionada',
'revdelete-nooldid-text'      => 'Você ou não especificou uma(s) edição(ões) de destino, a edição especificada não existe ou, ainda, você está tentando ocultar a edição atual.',
'revdelete-nologtype-title'   => 'Tipo de registro não especificado',
'revdelete-nologtype-text'    => 'Você não especificou um tipo de registro sobre o qual executar esta ação.',
'revdelete-nologid-title'     => 'Entrada de registro inválida',
'revdelete-nologid-text'      => 'Você não especificou um evento de registro alvo para executar esta função ou a entrada especificada não existe.',
'revdelete-no-file'           => 'O arquivo especificado não existe.',
'revdelete-show-file-confirm' => 'Tem a certeza de que quer visualizar uma revisão eliminada do arquivo "<nowiki>$1</nowiki>" de $2 em $3?',
'revdelete-show-file-submit'  => 'Sim',
'revdelete-selected'          => "'''{{PLURAL:$2|Edição selecionada|Edições selecionadas}} de [[:$1]]:'''",
'logdelete-selected'          => "'''{{PLURAL:$1|Evento de registro selecionado|Eventos de registro selecionados}}:'''",
'revdelete-text'              => "'''Revisões eliminadas e eventos continuarão aparecendo no histórico da página e nos registros, apesar de o seu conteúdo textual estar inacessível ao público.'''
Outros administradores no {{SITENAME}} continuarão podendo acessar ao conteúdo escondido e restaurá-lo através desta mesma ''interface'', a menos que uma restrição adicional seja definida.",
'revdelete-confirm'           => 'Por favor confirme que pretende executar esta acção, que compreende as suas consequências e que o faz em concordância com as [[{{MediaWiki:Policy-url}}|políticas e recomendações]].',
'revdelete-suppress-text'     => "A supressão deverá ser usada '''apenas''' para os seguintes casos:
* Informação pessoal inapropriada
*: ''endereços de domicílio e números de telefone, números da segurança social, etc''",
'revdelete-legend'            => 'Definir restrições de visualização',
'revdelete-hide-text'         => 'Ocultar texto da edição',
'revdelete-hide-image'        => 'Ocultar conteúdos do arquivo',
'revdelete-hide-name'         => 'Ocultar ação e alvo',
'revdelete-hide-comment'      => 'Esconder comentário de edição',
'revdelete-hide-user'         => 'Esconder nome de usuário/IP do editor',
'revdelete-hide-restricted'   => 'Suprimir dados de administradores assim como de outros',
'revdelete-radio-same'        => '(não altere)',
'revdelete-radio-set'         => 'Sim',
'revdelete-radio-unset'       => 'Não',
'revdelete-suppress'          => 'Suprimir dados de administradores, bem como de outros',
'revdelete-unsuppress'        => 'Remover restrições das edições restauradas',
'revdelete-log'               => 'Motivo:',
'revdelete-submit'            => 'Aplicar {{PLURAL:$1|à revisão selecionada|à revisões selecionadas}}',
'revdelete-logentry'          => 'alterou a visibilidade das revisões de "[[$1]]"',
'logdelete-logentry'          => 'alterou a visibilidade dos eventos de "[[$1]]"',
'revdelete-success'           => "'''A visibilidade da revisão foi definida com sucesso.'''",
'revdelete-failure'           => "'''A visibilidade da revisão não foi atualizada:'''
$1",
'logdelete-success'           => "'''Visibilidade de evento definida com sucesso.'''",
'logdelete-failure'           => "'''A visibilidade do registro não pôde ser estabelecida:'''
$1",
'revdel-restore'              => 'Alterar visibilidade',
'pagehist'                    => 'Histórico da página',
'deletedhist'                 => 'Histórico de eliminações',
'revdelete-content'           => 'conteúdo',
'revdelete-summary'           => 'sumário de edição',
'revdelete-uname'             => 'nome do usuário',
'revdelete-restricted'        => 'restrições a administradores aplicadas',
'revdelete-unrestricted'      => 'restrições a administradores removidas',
'revdelete-hid'               => 'ocultado $1',
'revdelete-unhid'             => 'desocultado $1',
'revdelete-log-message'       => '$1 para $2 {{PLURAL:$2|revisão|revisões}}',
'logdelete-log-message'       => '$1 para $2 {{PLURAL:$2|evento|eventos}}',
'revdelete-hide-current'      => 'Erro ao ocultar o item datado de $2, $1: esta é a revisão atual.
Não pode ser ocultada.',
'revdelete-show-no-access'    => 'Erro ao mostrar o item datado de $2, $1: este item foi marcado como "restrito".
Você não tem acesso a ele.',
'revdelete-modify-no-access'  => 'Erro ao modificar o item datado de $2, $1: este item foi marcado como "restrito".
Você não tem acesso a ele.',
'revdelete-modify-missing'    => 'Erro ao modificar o item ID $1: está faltando na base de dados!',
'revdelete-no-change'         => "'''Aviso:''' o item datado de $2, $1 já possui as configurações de visualização requeridas.",
'revdelete-concurrent-change' => 'Erro ao modificar o item datado de $2, $1: o seu estado parece ter sido alterado por outra pessoa enquanto você tentava modificá-lo.
Por favor, verifique os registos.',
'revdelete-only-restricted'   => 'Erro ao ocultar o item de $2 às $1: você não pode impedir que itens sejam visualizados por administradores sem também selecionar uma das outras opções de visibilidade.',
'revdelete-reason-dropdown'   => '*Motivos comuns para eliminação
** Violação de direitos autorais
** Informação pessoal inapropriada
** Informação potencialmente difamatória',
'revdelete-otherreason'       => 'Outro motivo/motivo adicional:',
'revdelete-reasonotherlist'   => 'Outro motivo',
'revdelete-edit-reasonlist'   => 'Editar motivos de eliminação',
'revdelete-offender'          => 'Autor da revisão:',

# Suppression log
'suppressionlog'     => 'Registro de supressões',
'suppressionlogtext' => 'Abaixo está uma lista das remoções e bloqueios envolvendo conteúdo ocultado por administradores.
Veja a [[Special:IPBlockList|lista de bloqueios]] para uma lista de banimentos e bloqueios em efeito neste momento.',

# History merging
'mergehistory'                     => 'Fundir histórico de páginas',
'mergehistory-header'              => 'A partir desta página é possível fundir históricos de edições de uma página em outra.
Certifique-se de que tal alteração manterá a continuidade das ações.',
'mergehistory-box'                 => 'Fundir revisões de duas páginas:',
'mergehistory-from'                => 'Página de origem:',
'mergehistory-into'                => 'Página de destino:',
'mergehistory-list'                => 'Histórico de edições habilitadas para fusão',
'mergehistory-merge'               => 'As edições de [[:$1]] a seguir poderão ser fundidas em [[:$2]]. Utilize a coluna de botões de opção para fundir apenas as edições feitas entre o intervalo de tempo especificado. Note que ao utilizar os links de navegação esta coluna será retornada a seus valores padrão.',
'mergehistory-go'                  => 'Exibir edições habilitadas a serem fundidas',
'mergehistory-submit'              => 'Fundir edições',
'mergehistory-empty'               => 'Não existem edições habilitadas a serem fundidas.',
'mergehistory-success'             => '$3 {{PLURAL:$3|revisão|revisões}} de [[:$1]] fundidas em [[:$2]] com sucesso.',
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
'mergelog'           => 'Registro de fusão de históricos',
'pagemerge-logentry' => '[[$1]] foi fundida em [[$2]] (até a edição $3)',
'revertmerge'        => 'Desfazer fusão',
'mergelogpagetext'   => 'Segue-se um registro das mais recentes fusões de históricos de páginas.',

# Diffs
'history-title'            => 'Histórico de edições de "$1"',
'difference'               => '(Diferença entre revisões)',
'lineno'                   => 'Linha $1:',
'compareselectedversions'  => 'Compare as versões selecionadas',
'showhideselectedversions' => 'Mostrar/esconder versões selecionadas',
'editundo'                 => 'desfazer',
'diff-multi'               => '({{PLURAL:$1|uma edição intermediária não está sendo exibida|$1 edições intermediárias não estão sendo exibidas}}.)',

# Search results
'searchresults'                    => 'Resultados de pesquisa',
'searchresults-title'              => 'Resultados da pesquisa por "$1"',
'searchresulttext'                 => 'Para mais informações de como pesquisar na {{SITENAME}}, consulte [[{{MediaWiki:Helppage}}|{{int:help}}]].',
'searchsubtitle'                   => 'Você pesquisou por \'\'\'[[:$1]]\'\'\' ([[Special:Prefixindex/$1|páginas iniciadas por "$1"]]{{int:pipe-separator}}[[Special:WhatLinksHere/$1|páginas que apontam para "$1"]])',
'searchsubtitleinvalid'            => 'Você pesquisou por "$1"',
'toomanymatches'                   => 'Foram retornados muitos resultados. Por favor, tente um filtro de pesquisa diferente',
'titlematches'                     => 'Resultados nos títulos das páginas',
'notitlematches'                   => 'Nenhum título de página coincide',
'textmatches'                      => 'Resultados nos textos das páginas',
'notextmatches'                    => 'Não foi possível localizar, no conteúdo das páginas, o termo pesquisado',
'prevn'                            => 'anteriores {{PLURAL:$1|$1}}',
'nextn'                            => 'próximos {{PLURAL:$1|$1}}',
'prevn-title'                      => '$1 {{PLURAL:$1|resultado anterior|resultados anteriores}}',
'nextn-title'                      => '{{PLURAL:$1|próximo|próximos}} $1 {{PLURAL:$1|resultado|resultados}}',
'shown-title'                      => 'Mostrar $1 {{PLURAL:$1|resultado|resultados}} por página',
'viewprevnext'                     => 'Ver ($1 {{int:pipe-separator}} $2) ($3).',
'searchmenu-legend'                => 'Opções de pesquisa',
'searchmenu-exists'                => "*'''Há uma página chamada \"[[\$1]]\" nesta wiki'''",
'searchmenu-new'                   => "'''Criar a página \"[[:\$1|\$1]]\" nesta wiki!'''",
'searchhelp-url'                   => 'Help:Conteúdos',
'searchmenu-prefix'                => '[[Special:PrefixIndex/$1|Navegue pelas páginas com este prefixo]]',
'searchprofile-articles'           => 'Páginas de conteúdo',
'searchprofile-project'            => 'Ajuda e páginas do Projeto',
'searchprofile-images'             => 'Multimídia',
'searchprofile-everything'         => 'Tudo',
'searchprofile-advanced'           => 'Avançado',
'searchprofile-articles-tooltip'   => 'Pesquisar em $1',
'searchprofile-project-tooltip'    => 'Pesquisar em $1',
'searchprofile-images-tooltip'     => 'Pesquisar arquivos',
'searchprofile-everything-tooltip' => 'Pesquisar em todo o conteúdo (incluindo páginas de discussão)',
'searchprofile-advanced-tooltip'   => 'Pesquisar nos espaços nominais personalizados',
'search-result-size'               => '$1 ({{PLURAL:$2|1 palavra|$2 palavras}})',
'search-result-score'              => 'Relevância: $1%',
'search-redirect'                  => '(redirecionamento de $1)',
'search-section'                   => '(seção $1)',
'search-suggest'                   => 'Será que quis dizer: $1',
'search-interwiki-caption'         => 'Projetos irmãos',
'search-interwiki-default'         => 'Resultados de $1:',
'search-interwiki-more'            => '(mais)',
'search-mwsuggest-enabled'         => 'com sugestões',
'search-mwsuggest-disabled'        => 'sem sugestões',
'search-relatedarticle'            => 'Relacionado',
'mwsuggest-disable'                => 'Desativar sugestões AJAX',
'searcheverything-enable'          => 'Procurar em todos os espaços nominais',
'searchrelated'                    => 'relacionados',
'searchall'                        => 'todos',
'showingresults'                   => "A seguir {{PLURAL:$1|é mostrado '''um''' resultado|são mostrados até '''$1''' resultados}}, iniciando no '''$2'''º.",
'showingresultsnum'                => "A seguir {{PLURAL:$3|é mostrado '''um''' resultado|são mostrados '''$3''' resultados}}, iniciando com o '''$2'''º.",
'showingresultsheader'             => "{{PLURAL:$5|Resulado '''$1''' de '''$3'''|Resultados '''$1 - $2''' de '''$3'''}} para '''$4'''",
'nonefound'                        => "'''Nota''': apenas alguns espaços nominais são pesquisados por padrão. Tente utilizar o prefixo ''all:'' em sua busca, para pesquisar por todos os conteúdos desta wiki (inclusive páginas de discussão, predefinições etc), ou mesmo, utilizando o espaço nominal desejado como prefixo.",
'search-nonefound'                 => 'Não houve resultados para a pesquisa.',
'powersearch'                      => 'Pesquisa avançada',
'powersearch-legend'               => 'Pesquisa avançada',
'powersearch-ns'                   => 'Pesquisar nos espaços nominais:',
'powersearch-redir'                => 'Listar redirecionamentos',
'powersearch-field'                => 'Pesquisar',
'powersearch-togglelabel'          => 'Selecionar:',
'powersearch-toggleall'            => 'Todos',
'powersearch-togglenone'           => 'Nenhum',
'search-external'                  => 'Pesquisa externa',
'searchdisabled'                   => 'A busca em {{SITENAME}} se encontra desativada.
Você poderá pesquisar através do Google enquanto isso.
Note que os índices do sistema de busca externo poderão conter referências desatualizadas a {{SITENAME}}.',

# Quickbar
'qbsettings'               => 'Configurações da Barra Rápida',
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
'prefsnologintext'              => 'É necessário estar <span class="plainlinks">[{{fullurl:{{#Special:UserLogin}}|returnto=$1}} autenticado]</span> para definir as suas preferências.',
'changepassword'                => 'Alterar senha',
'prefs-skin'                    => 'Tema',
'skin-preview'                  => 'Previsão',
'prefs-math'                    => 'Matemática',
'datedefault'                   => 'Sem preferência',
'prefs-datetime'                => 'Data e hora',
'prefs-personal'                => 'Perfil de usuário',
'prefs-rc'                      => 'Mudanças recentes',
'prefs-watchlist'               => 'Lista de páginas vigiadas',
'prefs-watchlist-days'          => 'Dias a mostrar na lista de páginas vigiadas:',
'prefs-watchlist-days-max'      => '(no máximo 7 dias)',
'prefs-watchlist-edits'         => 'Número de edições mostradas na lista de páginas vigiadas expandida:',
'prefs-watchlist-edits-max'     => '(número máximo: 1000)',
'prefs-watchlist-token'         => 'Senha para a lista de páginas vigiadas:',
'prefs-misc'                    => 'Diversos',
'prefs-resetpass'               => 'Alterar senha',
'prefs-email'                   => 'Opções de email',
'prefs-rendering'               => 'Layout',
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
'recentchangescount'            => 'Número de edições a serem exibidas por padrão:',
'prefs-help-recentchangescount' => 'Isto inclui mudanças recentes, histórico de páginas e registos.',
'prefs-help-watchlist-token'    => "O preenchimento deste campo com uma senha secreta irá gerar um ''feed'' RSS para a sua lista de páginas vigiadas.
Qualquer um que conheça a senha deste campo será capaz de ler sua lista de páginas vigiadas, então escolha um valor seguro.
Eis um valor gerado aleatoriamente que você pode usar: $1",
'savedprefs'                    => 'As suas preferências foram salvas.',
'timezonelegend'                => 'Fuso horário:',
'localtime'                     => 'Horário local:',
'timezoneuseserverdefault'      => 'Usa padrão do servidor',
'timezoneuseoffset'             => 'Outro (especifique diferença horária)',
'timezoneoffset'                => 'Diferença horária¹',
'servertime'                    => 'Horário do servidor:',
'guesstimezone'                 => 'Preencher a partir do navegador',
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
'allowemail'                    => 'Permitir email de outros usuários',
'prefs-searchoptions'           => 'Opções de busca',
'prefs-namespaces'              => 'Espaços nominais',
'defaultns'                     => 'Caso contrário pesquisar nestes espaços nominais:',
'default'                       => 'padrão',
'prefs-files'                   => 'Arquivos',
'prefs-custom-css'              => 'CSS personalizada',
'prefs-custom-js'               => 'JS personalizado',
'prefs-reset-intro'             => 'Você pode usar esta página para restaurar as suas preferências para os valores predefinidos do sítio.
Esta ação não pode ser desfeita.',
'prefs-emailconfirm-label'      => 'Confirmação do e-mail:',
'prefs-textboxsize'             => 'Tamanho da janela de edição',
'youremail'                     => 'Seu e-mail:',
'username'                      => 'Nome de usuário:',
'uid'                           => 'Número de identificação:',
'prefs-memberingroups'          => 'Membro {{PLURAL:$1|do grupo|dos grupos}}:',
'prefs-registration'            => 'Hora de registro:',
'yourrealname'                  => 'Nome verdadeiro:',
'yourlanguage'                  => 'Idioma:',
'yourvariant'                   => 'Variante:',
'yournick'                      => 'Assinatura:',
'prefs-help-signature'          => 'Ao inserir comentários em páginas de discussão, assine-os colocando quatro tiles (<nowiki>~~~~</nowiki>) no fim dos comentários. Ao salvar, estes serão convertidos na sua assinatura mais a data e a hora da edição.',
'badsig'                        => 'Assinatura inválida; verifique o código HTML utilizado.',
'badsiglength'                  => 'A sua assinatura é muito longa.
Ela deve ter menos de $1 {{PLURAL:$1|caractere|caracteres}}.',
'yourgender'                    => 'Sexo:',
'gender-unknown'                => 'Não especificado',
'gender-male'                   => 'Masculino',
'gender-female'                 => 'Feminino',
'prefs-help-gender'             => 'Opcional: usado para endereçamento correto pelo software baseado no sexo. Esta informação será pública.',
'email'                         => 'E-mail',
'prefs-help-realname'           => 'O fornecimento de seu Nome verdadeiro é opcional, mas, caso decida o revelar, este será utilizado para lhe dar crédito pelo seu trabalho.',
'prefs-help-email'              => "O fornecimento de um endereço de ''e-mail'' é opcional, mas permite que uma nova senha lhe seja enviada caso você esqueça sua senha. Você pode ainda preferir deixar que os usuários entrem em contato consigo através de sua página de usuário ou discussão sem ter de revelar sua identidade.",
'prefs-help-email-required'     => 'O endereço de e-mail é requerido.',
'prefs-info'                    => 'Informação básica',
'prefs-i18n'                    => 'Internacionalização',
'prefs-signature'               => 'Assinatura',
'prefs-dateformat'              => 'Formato de data',
'prefs-timeoffset'              => 'Desvio horário',
'prefs-advancedediting'         => 'Opções avançadas',
'prefs-advancedrc'              => 'Opções avançadas',
'prefs-advancedrendering'       => 'Opções avançadas',
'prefs-advancedsearchoptions'   => 'Opções avançadas',
'prefs-advancedwatchlist'       => 'Opções avançadas',
'prefs-display'                 => 'Opções de exibição',
'prefs-diffs'                   => 'Diferenças',

# User rights
'userrights'                   => 'Gestão de privilégios de usuários',
'userrights-lookup-user'       => 'Administrar grupos de usuários',
'userrights-user-editname'     => 'Forneça um nome de usuário:',
'editusergroup'                => 'Editar grupos de usuários',
'editinguser'                  => "Modificando privilégios do usuário '''[[User:$1|$1]]''' ([[User talk:$1|{{int:talkpagelinktext}}]]{{int:pipe-separator}}[[Special:Contributions/$1|{{int:contribslink}}]])",
'userrights-editusergroup'     => 'Editar grupos do usuário',
'saveusergroups'               => 'Salvar grupos do usuário',
'userrights-groupsmember'      => 'Membro de:',
'userrights-groupsmember-auto' => 'Membro implícito de:',
'userrights-groups-help'       => 'É possível alterar os grupos em que este usuário se encontra:
* Uma caixa de seleção selecionada significa que o usuário se encontra no grupo.
* Uma caixa de seleção desselecionada significa que o usuário não se encontra no grupo.
* Um * indica que não pode remover o grupo depois de o adicionar, ou vice-versa.',
'userrights-reason'            => 'Motivo:',
'userrights-no-interwiki'      => 'Você não tem permissão de alterar privilégios de usuários em outras wikis.',
'userrights-nodatabase'        => 'O banco de dados $1 não existe ou não é um banco de dados local.',
'userrights-nologin'           => 'Você precisa [[Special:UserLogin|autenticar-se]] como um administrador para especificar os privilégios de usuário.',
'userrights-notallowed'        => 'Sua conta não possui permissão para conceder privilégios a usuários.',
'userrights-changeable-col'    => 'Grupos que pode alterar',
'userrights-unchangeable-col'  => 'Grupos que não pode alterar',

# Groups
'group'               => 'Grupo:',
'group-user'          => 'Usuários',
'group-autoconfirmed' => 'Usuários auto-confirmados',
'group-bot'           => 'Robôs',
'group-sysop'         => 'Administradores',
'group-bureaucrat'    => 'Burocratas',
'group-suppress'      => 'Oversights',
'group-all'           => '(todos)',

'group-user-member'          => 'Usuário',
'group-autoconfirmed-member' => 'Usuário auto-confirmado',
'group-bot-member'           => 'Robô',
'group-sysop-member'         => 'Administrador',
'group-bureaucrat-member'    => 'Burocrata',
'group-suppress-member'      => 'Oversight',

'grouppage-user'          => '{{ns:project}}:Usuários',
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
'right-createaccount'         => 'Criar novas contas de usuário',
'right-minoredit'             => 'Marcar edições como menores',
'right-move'                  => 'Mover páginas',
'right-move-subpages'         => 'Mover páginas com as suas subpáginas',
'right-move-rootuserpages'    => 'Mover páginas raiz de usuários',
'right-movefile'              => 'Mover arquivos',
'right-suppressredirect'      => 'Não criar um redirecionamento do nome antigo quando uma página é movida',
'right-upload'                => 'Carregar arquivos',
'right-reupload'              => 'Sobrescrever um arquivo existente',
'right-reupload-own'          => 'Sobrescrever um arquivo existente carregado pelo mesmo usuário',
'right-reupload-shared'       => 'Sobrescrever localmente arquivos no repositório partilhado de imagens',
'right-upload_by_url'         => 'Carregar um arquivo de um endereço URL',
'right-purge'                 => 'Carregar a cache de uma página no site sem página de confirmação',
'right-autoconfirmed'         => 'Editar páginas semi-protegidas',
'right-bot'                   => 'Ser tratado como um processo automatizado',
'right-nominornewtalk'        => 'Não ter o aviso de novas mensagens despoletado quando são feitas edições menores a páginas de discussão',
'right-apihighlimits'         => 'Usar limites superiores em consultas (queries) via API',
'right-writeapi'              => 'Uso da API de escrita',
'right-delete'                => 'Eliminar páginas',
'right-bigdelete'             => 'Eliminar páginas com histórico grande',
'right-deleterevision'        => 'Eliminar e restaurar revisões específicas de páginas',
'right-deletedhistory'        => 'Ver entradas de histórico eliminadas, sem o texto associado',
'right-deletedtext'           => 'Ver texto removido e alterado entre revisões removidas',
'right-browsearchive'         => 'Buscar páginas eliminadas',
'right-undelete'              => 'Restaurar uma página',
'right-suppressrevision'      => 'Rever e restaurar revisões ocultadas dos Sysops',
'right-suppressionlog'        => 'Ver registros privados',
'right-block'                 => 'Impedir outros usuários de editarem',
'right-blockemail'            => 'Impedir um usuário de enviar email',
'right-hideuser'              => 'Bloquear um nome de usuário, escondendo-o do público',
'right-ipblock-exempt'        => 'Contornar bloqueios de IP, automáticos e de intervalo',
'right-proxyunbannable'       => 'Contornar bloqueios automáticos de proxies',
'right-protect'               => 'Mudar níveis de proteção e editar páginas protegidas',
'right-editprotected'         => 'Editar páginas protegidas (sem proteção em cascata)',
'right-editinterface'         => 'Editar a interface de usuário',
'right-editusercssjs'         => 'Editar os arquivos CSS e JS de outros usuários',
'right-editusercss'           => 'Editar os arquivos CSS de outros usuários',
'right-edituserjs'            => 'Editar os arquivos JS de outros usuários',
'right-rollback'              => 'Reverter rapidamente o último usuário que editou uma página em particular',
'right-markbotedits'          => 'Marcar edições revertidas como edições de bot',
'right-noratelimit'           => 'Não afetado pelos limites de velocidade de operação',
'right-import'                => 'Importar páginas de outros wikis',
'right-importupload'          => 'Importar páginas de um arquivo carregado',
'right-patrol'                => 'Marcar edições como patrulhadas',
'right-autopatrol'            => 'Ter edições automaticamente marcadas como patrulhadas',
'right-patrolmarks'           => 'Usar funcionalidades de patrulhagem das mudanças recentes',
'right-unwatchedpages'        => 'Ver uma lista de páginas não vigiadas',
'right-trackback'             => "Submeter um 'trackback'",
'right-mergehistory'          => 'Fundir o histórico de páginas',
'right-userrights'            => 'Editar todos os direitos de usuário',
'right-userrights-interwiki'  => 'Editar direitos de usuário de usuários outros sites wiki',
'right-siteadmin'             => 'Bloquear e desbloquear o banco de dados',
'right-reset-passwords'       => 'Redefinir a senha de outros usuários',
'right-override-export-depth' => 'Exportar páginas incluindo páginas ligadas até uma profundidade de 5',
'right-versiondetail'         => "Mostrar informação ampliada sobre a versão do ''software''.",
'right-sendemail'             => 'Enviar email a outros usuários',

# User rights log
'rightslog'      => 'Registro de privilégios de usuário',
'rightslogtext'  => 'Este é um registro de mudanças nos privilégios de usuários.',
'rightslogentry' => 'foi alterado o grupo de acesso de $1 (de $2 para $3)',
'rightsnone'     => '(nenhum)',

# Associated actions - in the sentence "You do not have permission to X"
'action-read'                 => 'ler esta página',
'action-edit'                 => 'editar esta página',
'action-createpage'           => 'criar páginas',
'action-createtalk'           => 'criar páginas de discussão',
'action-createaccount'        => 'criar esta conta de usuário',
'action-minoredit'            => 'marcar esta edição como uma edição menor',
'action-move'                 => 'mover esta página',
'action-move-subpages'        => 'mover esta página e suas subpáginas',
'action-move-rootuserpages'   => 'mover páginas raiz de usuários',
'action-movefile'             => 'mover este arquivo',
'action-upload'               => 'enviar este arquivo',
'action-reupload'             => 'sobrescrever o arquivo existente',
'action-reupload-shared'      => 'sobrescrever este arquivo em um repositório compartilhado',
'action-upload_by_url'        => 'enviar este arquivo a partir de um endereço URL',
'action-writeapi'             => 'utilizar o modo de escrita da API',
'action-delete'               => 'excluir esta página',
'action-deleterevision'       => 'eliminar esta revisão',
'action-deletedhistory'       => 'ver o histórico de edições eliminadas desta página',
'action-browsearchive'        => 'pesquisar páginas eliminadas',
'action-undelete'             => 'restaurar esta página',
'action-suppressrevision'     => 'rever e restaurar esta edição oculta',
'action-suppressionlog'       => 'ver este registro privado',
'action-block'                => 'impedir que este usuário edite',
'action-protect'              => 'alterar os níveis de proteção desta página',
'action-import'               => 'importar esta página a partir de outra wiki',
'action-importupload'         => 'importar esta página através do carregamento de um arquivo',
'action-patrol'               => 'marcar as edições de outros usuários como patrulhadas',
'action-autopatrol'           => 'ter suas edições marcadas como patrulhadas',
'action-unwatchedpages'       => 'ver a lista de páginas não-vigiadas',
'action-trackback'            => "enviar um ''trackback''",
'action-mergehistory'         => 'fundir o histórico de edições desta página',
'action-userrights'           => 'editar todos os privilégios de usuário',
'action-userrights-interwiki' => 'editar privilégios de usuários de outras wikis',
'action-siteadmin'            => 'bloquear ou desbloquear o banco de dados',

# Recent changes
'nchanges'                          => '$1 {{PLURAL:$1|alteração|alterações}}',
'recentchanges'                     => 'Mudanças recentes',
'recentchanges-legend'              => 'Opções das mudanças recentes',
'recentchangestext'                 => 'Veja as mais novas mudanças na {{SITENAME}} nesta página.',
'recentchanges-feed-description'    => 'Acompanhe as Mudanças recentes deste wiki por este feed.',
'recentchanges-label-legend'        => 'Legenda: $1.',
'recentchanges-legend-newpage'      => '$1 - nova página',
'recentchanges-label-newpage'       => 'Esta edição criou uma nova página',
'recentchanges-legend-minor'        => '$1 - edição menor',
'recentchanges-label-minor'         => 'Esta é uma edição menor',
'recentchanges-legend-bot'          => '$1 - edição de robô',
'recentchanges-label-bot'           => 'Esta edição foi feita por um robô',
'recentchanges-legend-unpatrolled'  => '$1 - edição não patrulhada',
'recentchanges-label-unpatrolled'   => 'Esta edição ainda não foi patrulhada',
'rcnote'                            => "A seguir {{PLURAL:$1|está listada '''uma''' alteração ocorrida|estão listadas '''$1''' alterações ocorridas}} {{PLURAL:$2|no último dia|nos últimos '''$2''' dias}}, a partir das $5 de $4.",
'rcnotefrom'                        => 'Abaixo estão as mudanças desde <b>$2</b> (mostradas até <b>$1</b>).',
'rclistfrom'                        => 'Mostrar as novas alterações a partir das $1',
'rcshowhideminor'                   => '$1 edições menores',
'rcshowhidebots'                    => '$1 robôs',
'rcshowhideliu'                     => '$1 usuários registrados',
'rcshowhideanons'                   => '$1 usuários anônimos',
'rcshowhidepatr'                    => '$1 edições verificadas',
'rcshowhidemine'                    => '$1 as minhas edições',
'rclinks'                           => 'Mostrar as últimas $1 mudanças nos últimos $2 dias<br />$3',
'diff'                              => 'dif',
'hist'                              => 'hist',
'hide'                              => 'Esconder',
'show'                              => 'Mostrar',
'minoreditletter'                   => 'm',
'newpageletter'                     => 'N',
'boteditletter'                     => 'b',
'number_of_watching_users_pageview' => '[{{PLURAL:$1|$1 usuário|$1 usuários}} a vigiar]',
'rc_categories'                     => 'Limite para categorias (separar com "|")',
'rc_categories_any'                 => 'Qualquer',
'newsectionsummary'                 => '/* $1 */ nova seção',
'rc-enhanced-expand'                => 'Mostrar detalhes (requer JavaScript)',
'rc-enhanced-hide'                  => 'Esconder detalhes',

# Recent changes linked
'recentchangeslinked'          => 'Alterações relacionadas',
'recentchangeslinked-feed'     => 'Alterações relacionadas',
'recentchangeslinked-toolbox'  => 'Alterações relacionadas',
'recentchangeslinked-title'    => 'Alterações relacionadas com "$1"',
'recentchangeslinked-noresult' => 'Não ocorreram alterações em páginas relacionadas no intervalo de tempo fornecido.',
'recentchangeslinked-summary'  => "Esta página especial lista as alterações mais recentes de páginas que possuam um link a outra (ou de membros de uma categoria especificada).
Páginas que estejam em [[Special:Watchlist|sua lista de páginas vigiadas]] são exibidas em '''negrito'''.",
'recentchangeslinked-page'     => 'Nome da página:',
'recentchangeslinked-to'       => 'Mostrar alterações a páginas relacionadas com a página fornecida',

# Upload
'upload'                      => 'Enviar arquivo',
'uploadbtn'                   => 'Enviar arquivo',
'reuploaddesc'                => 'Cancelar o envio e retornar ao formulário de upload',
'upload-tryagain'             => 'Enviar descrição modificada de arquivo',
'uploadnologin'               => 'Não autenticado',
'uploadnologintext'           => 'Você necessita estar [[Special:UserLogin|autenticado]] para enviar arquivos.',
'upload_directory_missing'    => 'O diretório de upload ($1) não existe e não pôde ser criado pelo servidor.',
'upload_directory_read_only'  => 'O diretório de download de arquivos ($1) não tem permissões de escrita para o servidor Web.',
'uploaderror'                 => 'Erro ao fazer upload',
'uploadtext'                  => "Utilize o formulário abaixo para carregar novos arquivos.
Para ver ou pesquisar imagens anteriormente carregadas consulte a [[Special:FileList|lista de arquivos carregados]]. (Re)Envios são também registrados no [[Special:Log/upload|registro de carregamento]], e as eliminações no [[Special:Log/delete|registro de eliminação]]

Para incluir a imagem numa página, utilize uma ligação em um dos seguintes formatos:
* '''<tt><nowiki>[[</nowiki>{{ns:file}}<nowiki>:Arquivo.jpg]]</nowiki></tt>''' para utilizar a versão completa do arquivo;
* '''<tt><nowiki>[[</nowiki>{{ns:file}}<nowiki>:Arquivo.png|200px|thumb|left|texto]]</nowiki></tt>''' para utilizar uma renderização de 200 pixels dentro de uma caixa posicionada à margem esquerda contendo 'texto' como descrição;
* '''<tt><nowiki>[[</nowiki>{{ns:media}}<nowiki>:Arquivo.ogg]]</nowiki></tt>''' para uma ligação direta ao arquivo sem que ele seja exibido.",
'upload-permitted'            => 'Tipos de arquivos permitidos: $1.',
'upload-preferred'            => 'Tipos de arquivos preferidos: $1.',
'upload-prohibited'           => 'Tipos de arquivo proibidos: $1.',
'uploadlog'                   => 'registro de uploads',
'uploadlogpage'               => 'Registro de uploads',
'uploadlogpagetext'           => 'Segue a listagem dos envios de arquivos mais recentes.
A [[Special:NewFiles|galeria de arquivos novos]] oferece uma listagem mais visual.',
'filename'                    => 'Nome do arquivo',
'filedesc'                    => 'Descrição do arquivo',
'fileuploadsummary'           => 'Sumário:',
'filereuploadsummary'         => 'Alterações no arquivo:',
'filestatus'                  => 'Estado dos direitos de autor:',
'filesource'                  => 'Fonte:',
'uploadedfiles'               => 'Arquivos enviados',
'ignorewarning'               => 'Ignorar aviso e salvar de qualquer forma.',
'ignorewarnings'              => 'Ignorar todos os avisos',
'minlength1'                  => 'Os nomes de arquivos devem de ter pelo menos uma letra.',
'illegalfilename'             => 'O arquivo "$1" possui caracteres que não são permitidos no título de uma página. Por favor, altere o nome do arquivo e tente carregar novamente.',
'badfilename'                 => 'O nome do arquivo foi alterado para "$1".',
'filetype-mime-mismatch'      => 'A extensão do arquivo não corresponde ao tipo MIME.',
'filetype-badmime'            => 'Arquivos de tipo MIME "$1" não são permitidos de serem enviados.',
'filetype-bad-ie-mime'        => 'Este arquivo não pode ser carregado porque o Internet Explorer o detectaria como "$1", que é um tipo de arquivo não permitido e potencialmente perigoso.',
'filetype-unwanted-type'      => "'''\".\$1\"''' é um tipo de arquivo não desejado.
{{PLURAL:\$3|O tipo preferível é|Os tipos preferíveis são}} \$2.",
'filetype-banned-type'        => "'''\".\$1\"''' é um tipo proibido de arquivo.
{{PLURAL:\$3|O tipo permitido é|Os tipos permitidos são}} \$2.",
'filetype-missing'            => 'O arquivo não possui uma extensão (como, por exemplo, ".jpg").',
'large-file'                  => 'É recomendável que os arquivos não sejam maiores que $1; este possui $2.',
'largefileserver'             => 'O tamanho deste arquivo é superior ao qual o servidor encontra-se configurado para permitir.',
'emptyfile'                   => 'O arquivo que está tentando carregar parece encontrar-se vazio. Isto poderá ser devido a um erro na escrita do nome do arquivo. Por favor verifique se realmente deseja carregar este arquivo.',
'fileexists'                  => "Já existe um arquivo com este nome.
Por favor, verifique '''<tt>[[:$1]]</tt>''' caso não tenha a certeza se deseja alterar o arquivo atual.
[[$1|thumb]]",
'filepageexists'              => "A página de descrição deste arquivo já foi criada em '''<tt>[[:$1]]</tt>''', mas atualmente não existe nenhum arquivo com este nome.
O sumário que você introduziu não aparecerá na página de descrição.
Para fazer com que ele apareça lá, você precisará que editá-lo manualmente.
[[$1|thumb]]",
'fileexists-extension'        => "Já existe um arquivo de nome similar: [[$2|thumb]]
* Nome do arquivo que está sendo enviado: '''<tt>[[:$1]]</tt>'''
* Nome do arquivo existente: '''<tt>[[:$2]]</tt>'''
Por gentileza, escolha um nome diferente.",
'fileexists-thumbnail-yes'    => "O arquivo aparenta ser uma imagem de tamanho reduzido (''miniatura'', ou ''thumbnail''). [[$1|thumb]]
Por gentileza, verifique o arquivo '''<tt>[[:$1]]</tt>'''.
Se o arquivo enviado é o mesmo do de tamanho original, não é necessário enviar uma versão de miniatura adicional.",
'file-thumbnail-no'           => "O nome do arquivo começa com '''<tt>$1</tt>'''. Isso faz parecer se tratar de uma imagem de tamanho reduzido (''miniatura'', ou ''thumbnail'').
Se você tem esta imagem em sua resolução completa, envie a no lugar desta. Caso contrário, por gentileza, altere o nome de arquivo.",
'fileexists-forbidden'        => 'Já existe um arquivo com este nome, e não pode ser reescrito.
Se ainda pretende carregar o seu arquivo, por favor, volte e use um novo nome. [[File:$1|thumb|center|$1]]',
'fileexists-shared-forbidden' => 'Já existe um arquivo com este nome no repositório de arquivos compartilhados.
Se você ainda quer carregar o seu arquivo, por favor volte e use um novo nome. [[File:$1|thumb|center|$1]]',
'file-exists-duplicate'       => 'Esta imagem é uma duplicata do seguinte {{PLURAL:$1|arquivo|arquivos}}:',
'file-deleted-duplicate'      => 'Um arquivo idêntico a este ([[$1]]) foi eliminado anteriormente. Verifique o motivo da eliminação de tal arquivo antes de prosseguir com o re-envio.',
'successfulupload'            => 'Envio efetuado com sucesso',
'uploadwarning'               => 'Aviso',
'uploadwarning-text'          => 'Por favor modifique a descrição do arquivo abaixo e tente novamente',
'savefile'                    => 'Salvar arquivo',
'uploadedimage'               => 'enviou "[[$1]]"',
'overwroteimage'              => 'foi enviada uma nova versão de "[[$1]]"',
'uploaddisabled'              => 'Envio de arquivos desativado.',
'uploaddisabledtext'          => 'O envio de arquivos encontra-se desativado.',
'php-uploaddisabledtext'      => 'O carregamento de arquivos via PHP está desativado. Por favor, verifique a configuração file_uploads.',
'uploadscripted'              => 'Este arquivo contém HTML ou código que pode ser erradamente interpretado por um navegador web.',
'uploadvirus'                 => 'O arquivo contém vírus! Detalhes: $1',
'upload-source'               => 'Arquivo de origem',
'sourcefilename'              => 'Nome do arquivo de origem:',
'sourceurl'                   => 'URL de origem:',
'destfilename'                => 'Nome do arquivo de destino:',
'upload-maxfilesize'          => 'Tamanho máximo do arquivo: $1',
'upload-description'          => 'Descrição do arquivo',
'upload-options'              => 'Opções de envio',
'watchthisupload'             => 'Vigiar este arquivo',
'filewasdeleted'              => 'Um arquivo com este nome foi carregado anteriormente e subsequentemente eliminado. Você precisa verificar o $1 antes de proceder ao carregamento novamente.',
'upload-wasdeleted'           => "'''Atenção: Você está enviando um arquivo eliminado anteriormente.'''

Verfique se é apropriado prosseguir enviando este arquivo.
O registro de eliminação é exibido a seguir, para sua comodidade:",
'filename-bad-prefix'         => "O nome do arquivo que você está enviando começa com '''\"\$1\"''', um nome pouco esclarecedor, comumente associado de forma automática por câmeras digitais. Por gentileza, escolha um nome de arquivo mais explicativo.",
'filename-prefix-blacklist'   => ' #<!-- deixe esta linha exatamente como está --> <pre>
# A sintaxe é a seguinte:
#   * Tudo a partir do caractere "#" até ao fim da linha é um comentário
#   * Todas as linhas não vazias são um prefixo para nomes de arquivos típicos atribuídos automaticamente por câmaras digitais
CIMG # Casio
DSC_ # Nikon
DSCF # Fuji
DSCN # Nikon
DUW # alguns telefones móveis
IMG # genérico
JD # Jenoptik
MGP # Pentax
PICT # misc.
 #</pre> <!-- deixe esta linha exatamente como está -->',

'upload-proto-error'        => 'Protocolo incorreto',
'upload-proto-error-text'   => 'O envio de arquivos remotos requer endereços (URLs) que iniciem com <code>http://</code> ou <code>ftp://</code>.',
'upload-file-error'         => 'Erro interno',
'upload-file-error-text'    => 'Ocorreu um erro interno ao tentar criar um arquivo temporário no servidor.
Por gentileza, entre em contato com um [[Special:ListUsers/sysop|administrador]].',
'upload-misc-error'         => 'Erro desconhecido de envio',
'upload-misc-error-text'    => 'Ocorreu um erro desconhecido durante o envio. Por gentileza, verifique se o endereço (URL) é válido e acessível e tente novamente. Caso o problema persista, contacte um administrador de sistema.',
'upload-too-many-redirects' => 'A URL contém redirecionamentos demais',
'upload-unknown-size'       => 'Tamanho desconhecido',
'upload-http-error'         => 'Ocorreu um erro HTTP: $1',

# img_auth script messages
'img-auth-accessdenied' => 'Acesso negado',
'img-auth-nopathinfo'   => 'Falta PATH_INFO
Seu servidor não está configurado para passar essa informação.
Pode ser baseado em CGI e não suportar img_auth.
Veja http://www.mediawiki.org/wiki/Manual:Image_Authorization.',
'img-auth-notindir'     => 'O caminho requerido não está no directório de carregamento configurado.',
'img-auth-badtitle'     => 'Não é possível criar um título válido a partir de "$1".',
'img-auth-nologinnWL'   => 'Você não está logado e "$1" não está na lista branca.',
'img-auth-nofile'       => 'Arquivo "$1" não existe.',
'img-auth-isdir'        => 'Você está tentando acessar o diretório "$1".
Somente acesso ao arquivo é permitido.',
'img-auth-streaming'    => "Realizando ''streaming'' de \"\$1\".",
'img-auth-public'       => 'A img_auth.php produz arquivos a partir de uma wiki privada.
Esta wiki está configurada como uma wiki pública.
Para melhor segurança, o img_auth.php está desativado.',
'img-auth-noread'       => 'Usuário não tem acesso para ler "$1".',

# HTTP errors
'http-invalid-url'      => 'URL inválida: $1',
'http-invalid-scheme'   => 'URLs que iniciam com o prefixo "$1" não são aceitas.',
'http-request-error'    => 'A requisição HTTP falhou devido a um erro desconhecido.',
'http-read-error'       => 'Erro de leitura HTTP.',
'http-timed-out'        => 'Esgotado o tempo de espera da requisição HTTP.',
'http-curl-error'       => 'Erro ao requisitar a URL: $1',
'http-host-unreachable' => 'Não foi possível atingir a URL.',
'http-bad-status'       => 'Ocorreu um problema durante a requisição HTTP: $1 $2',

# Some likely curl errors. More could be added from <http://curl.haxx.se/libcurl/c/libcurl-errors.html>
'upload-curl-error6'       => 'Não foi possível acessar a URL',
'upload-curl-error6-text'  => 'Não foi possível acessar o endereço (URL) fornecido. Por gentileza, se certifique que o endereço foi fornecido corretamente e de que o site esteja acessível.',
'upload-curl-error28'      => 'Tempo limite para o envio do arquivo excedido',
'upload-curl-error28-text' => 'O site demorou muito tempo para responder. Por gentileza, verifique se o site está acessível, aguarde alguns momentos e tente novamente. Talvez você deseje fazer nova tentativa em um horário menos congestionado.',

'license'            => 'Licença:',
'license-header'     => 'Licenciamento',
'nolicense'          => 'Nenhuma selecionada',
'license-nopreview'  => '(Previsão não disponível)',
'upload_source_url'  => ' (um URL válido, publicamente acessível)',
'upload_source_file' => ' (um arquivo no seu computador)',

# Special:ListFiles
'listfiles-summary'     => 'Esta página especial mostra todos os arquivos carregados.
Por padrão, os últimos arquivos carregados são mostrados no topo da lista.
Um clique sobre um cabeçalho de coluna altera a ordenação.',
'listfiles_search_for'  => 'Pesquisar por nome de imagem:',
'imgfile'               => 'arquivo',
'listfiles'             => 'Lista de arquivo',
'listfiles_date'        => 'Data',
'listfiles_name'        => 'Nome',
'listfiles_user'        => 'Usuário',
'listfiles_size'        => 'Tamanho',
'listfiles_description' => 'Descrição',
'listfiles_count'       => 'Versões',

# File description page
'file-anchor-link'          => 'Arquivo',
'filehist'                  => 'Histórico do arquivo',
'filehist-help'             => 'Clique em uma data/horário para ver o arquivo tal como ele se encontrava em tal momento.',
'filehist-deleteall'        => 'eliminar todas',
'filehist-deleteone'        => 'eliminar',
'filehist-revert'           => 'restaurar',
'filehist-current'          => 'atual',
'filehist-datetime'         => 'Data/Horário',
'filehist-thumb'            => 'Miniatura',
'filehist-thumbtext'        => 'Miniatura para a versão de $1',
'filehist-nothumb'          => 'Miniatura indisponível',
'filehist-user'             => 'Usuário',
'filehist-dimensions'       => 'Dimensões',
'filehist-filesize'         => 'Tamanho do arquivo',
'filehist-comment'          => 'Comentário',
'filehist-missing'          => 'Arquivo faltando',
'imagelinks'                => 'Links para este arquivo',
'linkstoimage'              => '{{PLURAL:$1|A página|As $1 páginas}} a seguir tem link para este arquivo:',
'linkstoimage-more'         => 'Mais de $1 {{PLURAL:$1|página|páginas}} tem algum link para este arquivo.
A lista a seguir mostra apenas {{PLURAL:$1|o primeiro link|os $1 primeiros links}} para este arquivo.
Uma [[Special:WhatLinksHere/$2|listagem completa]] está disponível.',
'nolinkstoimage'            => 'Nenhuma página aponta para este arquivo.',
'morelinkstoimage'          => 'Ver [[Special:WhatLinksHere/$1|mais links]] para este arquivo.',
'redirectstofile'           => '{{PLURAL:$1|O seguinte arquivo redireciona|Os seguintes arquivos redirecionam}} para este arquivo:',
'duplicatesoffile'          => '{{PLURAL:$1|O seguinte arquivo é duplicado|Os seguintes arquivos são duplicados}} deste arquivo ([[Special:FileDuplicateSearch/$2|mais detalhes]]):',
'sharedupload'              => 'Este arquivo é do $1 e pode ser usado por outros projetos.',
'sharedupload-desc-there'   => 'Este arquivo é do $1 e pode ser utilizado por outros projetos.
Por favor veja a [$2 página de descrição do arquivo] para mais informações.',
'sharedupload-desc-here'    => 'Este arquivo é do $1 e pode ser utilizado por outros projetos.
A descrição na sua [$2 página de descrição de arquivo] é exibida abaixo.',
'filepage-nofile'           => 'Não existe nenhum arquivo com esse nome.',
'filepage-nofile-link'      => 'Não existe nenhum arquivo com este nome, mas você pode [$1 carregá-lo].',
'uploadnewversion-linktext' => 'Enviar uma nova versão deste arquivo',
'shared-repo-from'          => 'de $1',
'shared-repo'               => 'um repositório compartilhado',

# File reversion
'filerevert'                => 'Reverter $1',
'filerevert-legend'         => 'Reverter arquivo',
'filerevert-intro'          => '<span class="plainlinks">Você está revertendo \'\'\'[[Media:$1|$1]]\'\'\' para a [$4 versão de $2 - $3].</span>',
'filerevert-comment'        => 'Motivo:',
'filerevert-defaultcomment' => 'Revertido para a versão de $1 - $2',
'filerevert-submit'         => 'Reverter',
'filerevert-success'        => '<span class="plainlinks">\'\'\'[[Media:$1|$1]]\'\'\' foi revertida para a [$4 versão de $2 - $3].</span>',
'filerevert-badversion'     => 'Não há uma versão local anterior deste arquivo no período de tempo especificado.',

# File deletion
'filedelete'                  => 'Eliminar $1',
'filedelete-legend'           => 'Eliminar arquivo',
'filedelete-intro'            => "Você está prestes a eliminar o arquivo '''[[Media:$1|$1]]''' junto com todo o seu histórico.",
'filedelete-intro-old'        => '<span class="plainlinks">Você se encontra prestes a eliminar a versão de \'\'\'[[Media:$1|$1]]\'\'\' tal como se encontrava em [$4 $3, $2].</span>',
'filedelete-comment'          => 'Motivo:',
'filedelete-submit'           => 'Eliminar',
'filedelete-success'          => "'''$1''' foi eliminado.",
'filedelete-success-old'      => "A versão de '''[[Media:$1|$1]]''' tal como $3, $2 foi eliminada.",
'filedelete-nofile'           => "'''$1''' não existe.",
'filedelete-nofile-old'       => "Não há uma versão de '''$1''' em arquivo com os parâmetros especificados.",
'filedelete-otherreason'      => 'Outro/motivo adicional:',
'filedelete-reason-otherlist' => 'Outro motivo',
'filedelete-reason-dropdown'  => '*Motivos comuns para eliminação
** Violação de direitos de autor
** Arquivo duplicado',
'filedelete-edit-reasonlist'  => 'Editar motivos de eliminação',
'filedelete-maintenance'      => 'Eliminação e restauro de arquivos estão temporariamente desativados durante manutenção.',

# MIME search
'mimesearch'         => 'Pesquisa MIME',
'mimesearch-summary' => 'Esta página possibilita que os arquivos sejam filtrados a partir de seu tipo MIME. Sintaxe de busca: tipo/subtipo (por exemplo, <tt>image/jpeg</tt>).',
'mimetype'           => 'tipo MIME:',
'download'           => 'download',

# Unwatched pages
'unwatchedpages' => 'Páginas não vigiadas',

# List redirects
'listredirects' => 'Listar redirecionamentos',

# Unused templates
'unusedtemplates'     => 'Predefinições não utilizadas',
'unusedtemplatestext' => 'Esta página lista todas as páginas no espaço nominal {{ns:template}} que não estão incluídas numa outra página. Lembre-se de conferir se há outras ligações para as predefinições antes de apaga-las.',
'unusedtemplateswlh'  => 'outras ligações',

# Random page
'randompage'         => 'Página aleatória',
'randompage-nopages' => 'Não há páginas {{PLURAL:$2|no seguinte espaço nominal|nos seguintes espaços nominais}}: $1.',

# Random redirect
'randomredirect'         => 'Redirecionamento aleatório',
'randomredirect-nopages' => 'Não há redirecionamentos no espaço nominal "$1".',

# Statistics
'statistics'                   => 'Estatísticas',
'statistics-header-pages'      => 'Estatísticas de páginas',
'statistics-header-edits'      => 'Estatísticas de edições',
'statistics-header-views'      => 'Ver estatísticas',
'statistics-header-users'      => 'Estatísticas dos usuários',
'statistics-header-hooks'      => 'Outras estatísticas',
'statistics-articles'          => 'Páginas de conteúdo',
'statistics-pages'             => 'Páginas',
'statistics-pages-desc'        => 'Todas as páginas na wiki, incluindo páginas de discussão, redirecionamentos, etc.',
'statistics-files'             => 'Arquivos carregados',
'statistics-edits'             => 'Edições de página desde que {{SITENAME}} foi instalado',
'statistics-edits-average'     => 'Média de edições por página',
'statistics-views-total'       => 'Total de visualizações',
'statistics-views-peredit'     => 'Visualizações por edição',
'statistics-jobqueue'          => 'Tamanho da [http://www.mediawiki.org/wiki/Manual:Job_queue fila de tarefas]',
'statistics-users'             => '[[Special:ListUsers|Usuários]] registrados',
'statistics-users-active'      => 'Usuários ativos',
'statistics-users-active-desc' => 'Usuários que efetuaram uma ação {{PLURAL:$1|no último dia|nos últimos $1 dias}}',
'statistics-mostpopular'       => 'Páginas mais visitadas',

'disambiguations'      => 'Página de desambiguações',
'disambiguationspage'  => 'Template:disambig',
'disambiguations-text' => 'As páginas a seguir ligam a "páginas de desambiguação" ao invés de aos tópicos adequados.<br />
Uma página é considerada como de desambiguação se utilizar uma predefinição que esteja definida em [[MediaWiki:Disambiguationspage]]',

'doubleredirects'            => 'Redirecionamentos duplos',
'doubleredirectstext'        => 'Esta página lista as páginas que redirecionam para outros redirecionamentos.
Cada linha contém ligações para o primeiro e o segundo redirecionamentos, juntamente com o alvo do segundo redirecionamento, que é geralmente a verdadeira página de destino, para a qual o primeiro redirecionamento deveria apontar.
Entradas <s>riscadas</s> foram resolvidas.',
'double-redirect-fixed-move' => '[[$1]] foi movido e agora é um redirecionamento para [[$2]]',
'double-redirect-fixer'      => 'Corretor de redirecionamentos',

'brokenredirects'        => 'Redirecionamentos quebrados',
'brokenredirectstext'    => 'Os seguintes redirecionamentos ligam para páginas inexistentes:',
'brokenredirects-edit'   => 'editar',
'brokenredirects-delete' => 'eliminar',

'withoutinterwiki'         => 'Páginas sem interwikis de idiomas',
'withoutinterwiki-summary' => 'As seguintes páginas não possuem links para versões em outros idiomas:',
'withoutinterwiki-legend'  => 'Prefixo',
'withoutinterwiki-submit'  => 'Exibir',

'fewestrevisions' => 'Páginas de conteúdo com menos edições',

# Miscellaneous special pages
'nbytes'                  => '$1 {{PLURAL:$1|byte|bytes}}',
'ncategories'             => '$1 {{PLURAL:$1|categoria|categorias}}',
'nlinks'                  => '$1 {{PLURAL:$1|link|links}}',
'nmembers'                => '$1 {{PLURAL:$1|membro|membros}}',
'nrevisions'              => '$1 {{PLURAL:$1|revisão|revisões}}',
'nviews'                  => '$1 {{PLURAL:$1|visita|visitas}}',
'specialpage-empty'       => 'Atualmente não há dados a serem exibidos nesta página.',
'lonelypages'             => 'Páginas órfãs',
'lonelypagestext'         => 'As seguintes páginas não recebem ligações nem são incluídas em outras páginas do {{SITENAME}}.',
'uncategorizedpages'      => 'Páginas não categorizadas',
'uncategorizedcategories' => 'Categorias não categorizadas',
'uncategorizedimages'     => 'Imagens não categorizadas',
'uncategorizedtemplates'  => 'Predefinições não categorizadas',
'unusedcategories'        => 'Categorias não utilizadas',
'unusedimages'            => 'Arquivos não utilizados',
'popularpages'            => 'Páginas populares',
'wantedcategories'        => 'Categorias pedidas',
'wantedpages'             => 'Páginas pedidas',
'wantedpages-badtitle'    => 'Título inválido no conjunto de resultados: $1',
'wantedfiles'             => 'Arquivos pedidos',
'wantedtemplates'         => 'Predefinições pedidas',
'mostlinked'              => 'Páginas com mais afluentes',
'mostlinkedcategories'    => 'Categorias com mais membros',
'mostlinkedtemplates'     => 'Predefinições com mais afluentes',
'mostcategories'          => 'Páginas de conteúdo com mais categorias',
'mostimages'              => 'Imagens com mais afluentes',
'mostrevisions'           => 'Páginas de conteúdo com mais revisões',
'prefixindex'             => 'Todas as páginas com prefixo',
'shortpages'              => 'Páginas curtas',
'longpages'               => 'Páginas longas',
'deadendpages'            => 'Páginas sem saída',
'deadendpagestext'        => 'As seguintes páginas não contêm hiperligações para outras páginas nesta wiki.',
'protectedpages'          => 'Páginas protegidas',
'protectedpages-indef'    => 'Proteções infinitas apenas',
'protectedpages-cascade'  => 'Apenas proteções progressivas',
'protectedpagestext'      => 'As seguintes páginas encontram-se protegidas contra edições ou movimentações',
'protectedpagesempty'     => 'Não existem páginas, neste momento, protegidas com tais parâmetros.',
'protectedtitles'         => 'Títulos protegidos',
'protectedtitlestext'     => 'Os títulos a seguir encontram-se protegidos contra criação',
'protectedtitlesempty'    => 'Não há títulos protegidos com os parâmetros fornecidos.',
'listusers'               => 'Lista de usuários',
'listusers-editsonly'     => 'Mostrar apenas usuários com edições',
'listusers-creationsort'  => 'Ordenar por data de criação',
'usereditcount'           => '$1 {{PLURAL:$1|edição|edições}}',
'usercreated'             => 'Registrado em $1 às $2',
'newpages'                => 'Páginas recentes',
'newpages-username'       => 'Nome de usuário:',
'ancientpages'            => 'Páginas mais antigas',
'move'                    => 'Mover',
'movethispage'            => 'Mover esta página',
'unusedimagestext'        => 'Os seguintes arquivos existem mas não são embutidos em nenhuma página.
Por favor note que outros websites podem apontar para um arquivo através de um URL direto e, por isso, podem estar a ser listadas aqui, mesmo estando em uso.',
'unusedcategoriestext'    => 'As seguintes categorias existem, embora nenhuma página ou categoria faça uso delas.',
'notargettitle'           => 'Sem alvo',
'notargettext'            => 'Você não especificou uma página alvo ou um usuário para executar esta função.',
'nopagetitle'             => 'Página alvo não existe',
'nopagetext'              => 'A página alvo especificada não existe.',
'pager-newer-n'           => '{{PLURAL:$1|1 recente|$1 recentes}}',
'pager-older-n'           => '{{PLURAL:$1|1 antiga|$1 antigas}}',
'suppress'                => 'Visão geral',

# Book sources
'booksources'               => 'Fontes de livros',
'booksources-search-legend' => 'Procurar por fontes de livrarias',
'booksources-go'            => 'Ir',
'booksources-text'          => 'É exibida a seguir uma listagem de links para outros sites que vendem livros novos e usados e que possam possuir informações adicionais sobre os livros que você está pesquisando:',
'booksources-invalid-isbn'  => 'O número ISBN fornecido não parece ser válido; verifique se houve erros ao copiar da fonte original.',

# Special:Log
'specialloguserlabel'  => 'Usuário:',
'speciallogtitlelabel' => 'Título:',
'log'                  => 'Registros',
'all-logs-page'        => 'Todos os registros públicos',
'alllogstext'          => 'Exibição combinada de todos registros disponíveis para o {{SITENAME}}.
Você pode diminuir a lista escolhendo um tipo de registro, um nome de usuário (sensível a maiúsculas e minúsculas), ou uma página afetada (também sensível a maiúsculas e minúsculas).',
'logempty'             => 'Nenhum item idêntico no registro.',
'log-title-wildcard'   => 'Procurar por títulos que sejam iniciados com o seguinte texto',

# Special:AllPages
'allpages'          => 'Todas as páginas',
'alphaindexline'    => '$1 até $2',
'nextpage'          => 'Próxima página ($1)',
'prevpage'          => 'Página anterior ($1)',
'allpagesfrom'      => 'Mostrar páginas começando em:',
'allpagesto'        => 'Terminar de exibir páginas em:',
'allarticles'       => 'Todas as páginas',
'allinnamespace'    => 'Todas as páginas (espaço nominal $1)',
'allnotinnamespace' => 'Todas as páginas (excepto as do espaço nominal $1)',
'allpagesprev'      => 'Anterior',
'allpagesnext'      => 'Próximo',
'allpagessubmit'    => 'Ir',
'allpagesprefix'    => 'Exibir páginas com o prefixo:',
'allpagesbadtitle'  => 'O título de página fornecido encontrava-se inválido ou tinha um prefixo interlíngua ou inter-wiki. Ele poderá conter um ou mais caracteres que não podem ser utilizados em títulos.',
'allpages-bad-ns'   => '{{SITENAME}} não possui o espaço nominal "$1".',

# Special:Categories
'categories'                    => 'Categorias',
'categoriespagetext'            => '{{PLURAL:$1|A seguinte categoria contém|As seguintes contém}} páginas ou mídia.
[[Special:UnusedCategories|Categorias não utilizadas]] não são mostradas aqui.
Veja também [[Special:WantedCategories|categorias pedidas]].',
'categoriesfrom'                => 'Listar categorias começando por:',
'special-categories-sort-count' => 'ordenar por contagem',
'special-categories-sort-abc'   => 'ordenar alfabeticamente',

# Special:DeletedContributions
'deletedcontributions'             => 'Contribuições de usuário eliminadas',
'deletedcontributions-title'       => 'Contribuições de usuário eliminadas',
'sp-deletedcontributions-contribs' => 'contribuições',

# Special:LinkSearch
'linksearch'       => 'Ligações externas',
'linksearch-pat'   => 'Procurar padrão:',
'linksearch-ns'    => 'Espaço nominal:',
'linksearch-ok'    => 'Pesquisar',
'linksearch-text'  => 'É possível utilizar "caracteres mágicos" como em "*.wikipedia.org".<br />
Protocolos suportados: <tt>$1</tt>',
'linksearch-line'  => '$1 está lincado a partir de $2',
'linksearch-error' => "\"Caracteres mágicos\" (''wildcards'') só podem ser suados no início do endereço.",

# Special:ListUsers
'listusersfrom'      => 'Mostrar usuários começando em:',
'listusers-submit'   => 'Exibir',
'listusers-noresult' => 'Não foram encontrados usuários para a forma pesquisada.',
'listusers-blocked'  => '({{GENDER:$1|bloqueado|bloqueada}})',

# Special:ActiveUsers
'activeusers'            => 'Lista de usuários ativos',
'activeusers-intro'      => 'Esta é uma lista de usuários com algum tipo de atividade nos últimos $1 {{PLURAL:$1|dia|dias}}.',
'activeusers-count'      => '$1 {{PLURAL:$1|edição|edições}} {{PLURAL:$3|no último dia|nos últimos $3 dias}}',
'activeusers-from'       => 'Mostrar usuários começando em:',
'activeusers-hidebots'   => 'Esconder robôs',
'activeusers-hidesysops' => 'Esconder administradores',
'activeusers-noresult'   => 'Nenhum usuário encontrado.',

# Special:Log/newusers
'newuserlogpage'              => 'Registro de criação de usuários',
'newuserlogpagetext'          => 'Este é um registro de novas contas de usuário',
'newuserlog-byemail'          => 'senha enviada por correio-eletrônico',
'newuserlog-create-entry'     => 'Novo usuário',
'newuserlog-create2-entry'    => 'criou nova conta para $1',
'newuserlog-autocreate-entry' => 'Conta criada automaticamente',

# Special:ListGroupRights
'listgrouprights'                      => 'Privilégios de grupo de usuários',
'listgrouprights-summary'              => 'O que segue é uma lista dos grupos de usuários definidos nesta wiki, com os seus privilégios de acessos associados.
Pode haver [[{{MediaWiki:Listgrouprights-helppage}}|informações adicionais]] sobre privilégios individuais.',
'listgrouprights-key'                  => '* <span class="listgrouprights-granted">Privilégio concedido</span>
* <span class="listgrouprights-revoked">Privilégio revogado</span>',
'listgrouprights-group'                => 'Grupo',
'listgrouprights-rights'               => 'Privilégios',
'listgrouprights-helppage'             => 'Help:Privilégios de grupo',
'listgrouprights-members'              => '(lista de membros)',
'listgrouprights-addgroup'             => 'Podem adicionar {{PLURAL:$2|grupo|grupos}}: $1',
'listgrouprights-removegroup'          => 'Podem remover {{PLURAL:$2|grupo|grupos}}: $1',
'listgrouprights-addgroup-all'         => 'Podem adicionar todos os grupos',
'listgrouprights-removegroup-all'      => 'Podem remover todos os grupos',
'listgrouprights-addgroup-self'        => 'Pode adicionar {{PLURAL:$2|grupo|grupos}} à própria conta: $1',
'listgrouprights-removegroup-self'     => 'Pode remover {{PLURAL:$2|grupo|grupos}} da própria conta: $1',
'listgrouprights-addgroup-self-all'    => 'Pode adicionar todos os grupos à própria conta',
'listgrouprights-removegroup-self-all' => 'Pode remover todos os grupos da própria conta',

# E-mail user
'mailnologin'      => 'Nenhum endereço de envio',
'mailnologintext'  => 'Necessita de estar [[Special:UserLogin|autenticado]] e de possuir um endereço de e-mail válido nas suas [[Special:Preferences|preferências]] para poder enviar um e-mail a outros usuários.',
'emailuser'        => 'Contatar este usuário',
'emailpage'        => 'Contactar usuário',
'emailpagetext'    => 'Você pode usar o formulário abaixo para enviar uma mensagem por correio eletrônico para este usuário.
O endereço eletrônico que você inseriu em [[Special:Preferences|suas preferências de usuário]] irá aparecer como o endereço do remetente da mensagem, então o destinatário poderá responder diretamente para você.',
'usermailererror'  => 'Erro no email:',
'defemailsubject'  => 'E-mail: {{SITENAME}}',
'noemailtitle'     => 'Sem endereço de e-mail',
'noemailtext'      => 'Este usuário não especificou um endereço de e-mail válido.',
'nowikiemailtitle' => 'E-mail não permitido',
'nowikiemailtext'  => 'Este usuário optou por não receber e-mail de outros usuários.',
'email-legend'     => 'Enviar uma mensagem eletrônica para outro usuário da {{SITENAME}}',
'emailfrom'        => 'De:',
'emailto'          => 'Para:',
'emailsubject'     => 'Assunto:',
'emailmessage'     => 'Mensagem:',
'emailsend'        => 'Enviar',
'emailccme'        => 'Enviar ao meu e-mail uma cópia de minha mensagem.',
'emailccsubject'   => 'Cópia de sua mensagem para $1: $2',
'emailsent'        => 'E-mail enviado',
'emailsenttext'    => 'Sua mensagem foi enviada.',
'emailuserfooter'  => 'Este e-mail foi enviado por $1 para $2 através da opção de "contactar usuário" da {{SITENAME}}.',

# Watchlist
'watchlist'            => 'Páginas vigiadas',
'mywatchlist'          => 'Páginas vigiadas',
'watchlistfor'         => "(para '''$1''')",
'nowatchlist'          => 'A sua lista de páginas vigiadas não possui títulos.',
'watchlistanontext'    => 'Por favor $1 para ver ou editar os itens na sua lista de páginas vigiadas.',
'watchnologin'         => 'Não está autenticado',
'watchnologintext'     => 'Você precisa estar [[Special:UserLogin|autenticado]] para modificar a sua lista de páginas vigiadas.',
'addedwatch'           => 'Adicionado à lista',
'addedwatchtext'       => "A página \"[[:\$1]]\" foi adicionada à sua [[Special:Watchlist|lista de páginas vigiadas]].
Modificações futuras em tal página e páginas de discussão a ela associadas serão listadas lá, e a página aparecerá em '''negrito''' na [[Special:RecentChanges|lista de mudanças recentes]], para que você possa encontrá-la com maior facilidade.",
'removedwatch'         => 'Removida da lista de páginas vigiadas',
'removedwatchtext'     => 'A página "[[:$1]]" foi removida de sua [[Special:Watchlist|lista de páginas vigiadas]].',
'watch'                => 'Vigiar',
'watchthispage'        => 'Vigiar esta página',
'unwatch'              => 'Desinteressar-se',
'unwatchthispage'      => 'Parar de vigiar esta página',
'notanarticle'         => 'Não é uma página de conteúdo',
'notvisiblerev'        => 'Edição eliminada',
'watchnochange'        => 'Nenhuma das páginas vigiadas foi editada no período exibido.',
'watchlist-details'    => '{{PLURAL:$1|$1 página|$1 páginas}} na sua lista de páginas vigiadas, excluindo páginas de discussão.',
'wlheader-enotif'      => '* A notificação por email encontra-se ativada.',
'wlheader-showupdated' => "* As páginas modificadas desde a sua última visita são mostradas em '''negrito'''",
'watchmethod-recent'   => 'verificando edições recentes para as páginas vigiadas',
'watchmethod-list'     => 'verificando páginas vigiadas para edições recentes',
'watchlistcontains'    => 'Sua lista de páginas vigiadas contém $1 {{PLURAL:$1|página|páginas}}.',
'iteminvalidname'      => "Problema com item '$1', nome inválido...",
'wlnote'               => "A seguir {{PLURAL:$1|está a última alteração ocorrida|estão as últimas '''$1''' alterações ocorridas}} {{PLURAL:$2|na última hora|nas últimas '''$2''' horas}}.",
'wlshowlast'           => 'Ver últimas $1 horas $2 dias $3',
'watchlist-options'    => 'Opções da lista de páginas vigiadas',

# Displayed when you click the "watch" button and it is in the process of watching
'watching'   => 'Vigiando...',
'unwatching' => 'Deixando de vigiar...',

'enotif_mailer'                => '{{SITENAME}} Email de Notificação',
'enotif_reset'                 => 'Marcar todas páginas como visitadas',
'enotif_newpagetext'           => 'Esta é uma página nova.',
'enotif_impersonal_salutation' => 'Usuário do projeto "{{SITENAME}}"',
'changed'                      => 'alterada',
'created'                      => 'criada',
'enotif_subject'               => '{{SITENAME}}: A página $PAGETITLE foi $CHANGEDORCREATED por $PAGEEDITOR',
'enotif_lastvisited'           => 'Consulte $1 para todas as alterações efetuadas desde a sua última visita.',
'enotif_lastdiff'              => 'Acesse $1 para ver esta alteração.',
'enotif_anon_editor'           => 'usuário anônimo $1',
'enotif_body'                  => 'Caro(a) $WATCHINGUSERNAME,


A página $PAGETITLE na {{SITENAME}} foi $CHANGEDORCREATED a $PAGEEDITDATE por $PAGEEDITOR; consulte $PAGETITLE_URL para a versão atual.

$NEWPAGE

Sumário de edição: $PAGESUMMARY $PAGEMINOREDIT

Contate o editor:
e-mail: $PAGEEDITOR_EMAIL
wiki: $PAGEEDITOR_WIKI

Não haverá mais notificações no caso de futuras alterações a não ser que visite esta página.
Você pode também reativar as notificações para todas as suas páginas vigiadas na sua lista de páginas vigiadas.

             O seu sistema de notificação amigável da {{SITENAME}}

--
Para alterar as configurações de sua lista de páginas vigiadas, visite
{{fullurl:Special:Watchlist/edit}}

Para retirar esta página de sua lista de páginas vigiadas, visite
$UNWATCHURL

Comentários e assistência:
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
'historywarning'         => "'''Atenção:''' A página que você está prestes a eliminar possui um histórico com aproximadamente $1 {{PLURAL:$1|revisão|revisões}}:",
'confirmdeletetext'      => 'Encontra-se prestes a eliminar permanentemente uma página ou uma imagem e todo o seu histórico.
Por favor, confirme que possui a intenção de fazer isto, que compreende as consequências e que encontra-se a fazer isto de acordo com as [[{{MediaWiki:Policy-url}}|políticas]] do projeto.',
'actioncomplete'         => 'Ação completada',
'actionfailed'           => 'A ação falhou',
'deletedtext'            => '"<nowiki>$1</nowiki>" foi eliminada.
Consulte $2 para um registro de eliminações recentes.',
'deletedarticle'         => 'apagou "[[$1]]"',
'suppressedarticle'      => 'suprimiu "[[$1]]"',
'dellogpage'             => 'Registro de eliminação',
'dellogpagetext'         => 'Abaixo uma lista das eliminações mais recentes.',
'deletionlog'            => 'registro de eliminação',
'reverted'               => 'Revertido para versão anterior',
'deletecomment'          => 'Motivo:',
'deleteotherreason'      => 'Justificativa adicional:',
'deletereasonotherlist'  => 'Outro motivo',
'deletereason-dropdown'  => '* Motivos de eliminação comuns
** Pedido do autor
** Violação de direitos de autor
** Vandalismo',
'delete-edit-reasonlist' => 'Editar motivos de eliminação',
'delete-toobig'          => 'Esta página possui um longo histórico de edições, com mais de $1 {{PLURAL:$1|edição|edições}}.
A eliminação de tais páginas foi restrita, a fim de se evitarem problemas acidentais em {{SITENAME}}.',
'delete-warning-toobig'  => 'Esta página possui um longo histórico de edições, com mais de $1 {{PLURAL:$1|edição|edições}}.
Eliminá-la poderá causar problemas na base de dados de {{SITENAME}};
prossiga com cuidado.',

# Rollback
'rollback'          => 'Reverter edições',
'rollback_short'    => 'Reverter',
'rollbacklink'      => 'voltar',
'rollbackfailed'    => 'A reversão falhou',
'cantrollback'      => 'Não foi possível reverter a edição; o último contribuidor é o único autor desta página',
'alreadyrolled'     => 'Não foi possível reverter a última edição de [[:$1]] por [[User:$2|$2]] ([[User talk:$2|discussão]]{{int:pipe-separator}}[[Special:Contributions/$2|{{int:contribslink}}]]);
alguém já editou ou reverteu a página.

A última edição da página foi feita por [[User:$3|$3]] ([[User talk:$3|discussão]]{{int:pipe-separator}}[[Special:Contributions/$3|{{int:contribslink}}]]).',
'editcomment'       => "O sumário de edição era: \"''\$1''\".",
'revertpage'        => 'Foram revertidas as edições de [[Special:Contributions/$2|$2]] ([[User talk:$2|disc]]) para a última versão por [[User:$1|$1]]',
'revertpage-nouser' => 'Revertidas as edições de (nome de usuário removido) para a última revisão por [[User:$1|$1]]',
'rollback-success'  => 'Foram revertidas as edições de $1, com o conteúdo passando a estar como na última edição de $2.',
'sessionfailure'    => 'Foram detetados problemas com a sua sessão;
Esta ação foi cancelada como medida de proteção contra a intercepção de sessões.
Experimente usar o botão "Voltar" e atualizar a página de onde veio e tente novamente.',

# Protect
'protectlogpage'              => 'Registro de proteção',
'protectlogtext'              => 'Abaixo encontra-se o registro de proteção e desproteção de páginas.
Veja a [[Special:ProtectedPages|lista de páginas protegidas]] para uma listagem das páginas que se encontram protegidas no momento.',
'protectedarticle'            => 'protegeu "[[$1]]"',
'modifiedarticleprotection'   => 'alterou o nível de proteção para "[[$1]]"',
'unprotectedarticle'          => 'desprotegeu "[[$1]]"',
'movedarticleprotection'      => 'moveu as configurações de proteção de "[[$2]]" para "[[$1]]"',
'protect-title'               => 'Protegendo "$1"',
'prot_1movedto2'              => '[[$1]] foi movido para [[$2]]',
'protect-legend'              => 'Confirmar proteção',
'protectcomment'              => 'Motivo:',
'protectexpiry'               => 'Expiração',
'protect_expiry_invalid'      => 'O tempo de expiração fornecido é inválido.',
'protect_expiry_old'          => 'O tempo de expiração fornecido se situa no passado.',
'protect-unchain-permissions' => 'Desbloquear opções adicionais de proteção',
'protect-text'                => "Você pode, nesta página, alterar o nível de proteção para '''<nowiki>$1</nowiki>'''.",
'protect-locked-blocked'      => "Você não poderá alterar os níveis de proteção enquanto estiver bloqueado. Esta é a configuração atual para a página '''$1''':",
'protect-locked-dblock'       => "Não é possível alterar os níveis de proteção, uma vez que a base de dados se encontra trancada.
Esta é a configuração atual para a página '''$1''':",
'protect-locked-access'       => "Sua conta não possui permissões para alterar os níveis de proteção de uma página.
Esta é a configuração atual para a página '''$1''':",
'protect-cascadeon'           => 'Esta página encontra-se protegida, uma vez que se encontra incluída {{PLURAL:$1|na página listada a seguir, protegida|nas páginas listadas a seguir, protegidas}} com a "proteção progressiva" ativada. Você poderá alterar o nível de proteção desta página, mas isso não afetará a "proteção progressiva".',
'protect-default'             => 'Permitir todos os usuários',
'protect-fallback'            => 'É necessário o privilégio de "$1"',
'protect-level-autoconfirmed' => 'Bloquear usuários novos e não registrados',
'protect-level-sysop'         => 'Apenas administradores',
'protect-summary-cascade'     => 'p. progressiva',
'protect-expiring'            => 'expira em $1 (UTC)',
'protect-expiry-indefinite'   => 'indefinido',
'protect-cascade'             => '"Proteção progressiva" - proteja quaisquer páginas que estejam incluídas nesta.',
'protect-cantedit'            => 'Você não pode alterar o nível de proteção desta página uma vez que você não se encontra habilitado a editá-la.',
'protect-othertime'           => 'Outra duração:',
'protect-othertime-op'        => 'outra duração',
'protect-existing-expiry'     => 'A proteção atual expirará às $3 de $2',
'protect-otherreason'         => 'Outro motivo/motivo adicional:',
'protect-otherreason-op'      => 'Outro motivo',
'protect-dropdown'            => "*Motivos comuns para proteção
** Vandalismo excessivo
** Inserção excessiva de ''spams''
** Guerra de edições improdutiva
** Página bastante acessada",
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
'undeletepagetext'             => '{{PLURAL:$1|A seguinte página foi eliminada|As $1 páginas seguintes foram eliminadas}}, mas ainda {{PLURAL:$1|permanece|permanecem}} no arquivo e poderem ser restauradas.
O arquivo pode ser limpo periodicamente.',
'undelete-fieldset-title'      => 'Restaurar edições',
'undeleteextrahelp'            => "Para restaurar todo o histórico de edições desta página, deixe todas as caixas de seleção desmarcadas e clique em '''''Restaurar'''''.
Para efetuar uma restauração seletiva, selecione as caixas correspondentes às edições a serem restauradas e clique em '''''Restaurar'''''.
Clicar em '''''Limpar''''' irá limpar o campo de comentário e todas as caixas de seleção.",
'undeleterevisions'            => '$1 {{PLURAL:$1|edição disponível|edições disponíveis}}',
'undeletehistory'              => 'Se restaurar a página, todas as revisões serão restauradas para o histórico.
Se uma nova página foi criada com o mesmo nome desde a eliminação, as edições restauradas aparecerão no histórico anterior.',
'undeleterevdel'               => 'O restauro não será executado se resultar na remoção parcial da versão mais recente da página ou arquivo.
Em tais casos, deverá desselecionar ou reverter a ocultação da versão apagada mais recente.',
'undeletehistorynoadmin'       => 'Esta página foi eliminada. O motivo de eliminação é apresentado no súmario abaixo, junto dos detalhes do usuário que editou esta página antes de eliminar. O texto atual destas edições eliminadas encontra-se agora apenas disponível para administradores.',
'undelete-revision'            => 'Edição eliminada da página $1 (das $5 de $4), por $3:',
'undeleterevision-missing'     => 'Edição inválida ou não encontrada. Talvez você esteja com um link incorreto ou talvez a edição foi restaurada ou removida dos arquivos.',
'undelete-nodiff'              => 'Não foram encontradas edições anteriores.',
'undeletebtn'                  => 'Restaurar',
'undeletelink'                 => 'ver/restaurar',
'undeleteviewlink'             => 'visualizar',
'undeletereset'                => 'Limpar',
'undeleteinvert'               => 'Inverter seleção',
'undeletecomment'              => 'Motivo:',
'undeletedarticle'             => 'restaurou "[[$1]]"',
'undeletedrevisions'           => '$1 {{PLURAL:$1|edição restaurada|edições restauradas}}',
'undeletedrevisions-files'     => '$1 {{PLURAL:$2|edição restaurada|edições restauradas}} e $2 {{PLURAL:$2|arquivo restaurado|arquivos restaurados}}',
'undeletedfiles'               => '{{PLURAL:$1|arquivo restaurado|$1 arquivos restaurados}}',
'cannotundelete'               => 'Restauração falhada; alguém talvez já restaurou a página.',
'undeletedpage'                => "'''$1 foi restaurada'''

Consulte o [[Special:Log/delete|registro de eliminações]] para um registro das eliminações e restaurações mais recentes.",
'undelete-header'              => 'Veja o [[Special:Log/delete|registro de deleções]] para as páginas recentemente eliminadas.',
'undelete-search-box'          => 'Pesquisar páginas eliminadas',
'undelete-search-prefix'       => 'Exibir páginas que iniciem com:',
'undelete-search-submit'       => 'Pesquisar',
'undelete-no-results'          => 'Não foram encontradas edições relacionadas com o que foi buscado no arquivo de edições eliminadas.',
'undelete-filename-mismatch'   => 'Não foi possível restaurar a versão do arquivo de $1: nome de arquivo não combina',
'undelete-bad-store-key'       => 'Não foi possível restaurar a versão do arquivo de $1: já não existia antes da eliminação.',
'undelete-cleanup-error'       => 'Erro ao eliminar o arquivo não utilizado "$1".',
'undelete-missing-filearchive' => 'Não é possível restaurar o arquivo de ID $1, uma vez que ele não se encontra na base de dados. Isso pode significar que já tenha sido restaurado.',
'undelete-error-short'         => 'Erro ao restaurar arquivo: $1',
'undelete-error-long'          => 'Foram encontrados erros ao tentar restaurar o arquivo:

$1',
'undelete-show-file-confirm'   => 'Você tem certeza de que deseja visualizar um versão eliminada do arquivo "<nowiki>$1</nowiki>" das $3 de $2?',
'undelete-show-file-submit'    => 'Sim',

# Namespace form on various pages
'namespace'      => 'Espaço nominal:',
'invert'         => 'Inverter seleção',
'blanknamespace' => '(Principal)',

# Contributions
'contributions'       => 'Contribuições do usuário',
'contributions-title' => 'Contribuições do usuário $1',
'mycontris'           => 'Minhas contribuições',
'contribsub2'         => 'Para $1 ($2)',
'nocontribs'          => 'Não foram encontradas mudanças com este critério.',
'uctop'               => ' (revisão atual)',
'month'               => 'Mês (inclusive anteriores):',
'year'                => 'Ano (inclusive anteriores):',

'sp-contributions-newbies'        => 'Mostrar só as contribuições das contas recentes',
'sp-contributions-newbies-sub'    => 'Para contas novas',
'sp-contributions-newbies-title'  => 'Contribuições de contas novas',
'sp-contributions-blocklog'       => 'Registro de bloqueios',
'sp-contributions-deleted'        => 'contribuições eliminadas',
'sp-contributions-logs'           => 'registros',
'sp-contributions-talk'           => 'disc',
'sp-contributions-userrights'     => 'gerenciamento de privilégios de usuários',
'sp-contributions-blocked-notice' => 'Este usuário atualmente está bloqueado. O registro de bloqueio mais recente é fornecido abaixo para referência:',
'sp-contributions-search'         => 'Pesquisar contribuições',
'sp-contributions-username'       => 'Endereço de IP ou usuário:',
'sp-contributions-submit'         => 'Pesquisar',

# What links here
'whatlinkshere'            => 'Páginas afluentes',
'whatlinkshere-title'      => 'Páginas que apontam para "$1"',
'whatlinkshere-page'       => 'Página:',
'linkshere'                => "As seguintes páginas possuem ligações para '''[[:$1]]''':",
'nolinkshere'              => "Não existem ligações para '''[[:$1]]'''.",
'nolinkshere-ns'           => "Não há links para '''[[:$1]]''' no espaço nominal selecionado.",
'isredirect'               => 'página de redirecionamento',
'istemplate'               => 'inclusão',
'isimage'                  => 'link de imagem',
'whatlinkshere-prev'       => '{{PLURAL:$1|anterior|$1 anteriores}}',
'whatlinkshere-next'       => '{{PLURAL:$1|próximo|próximos $1}}',
'whatlinkshere-links'      => '← links',
'whatlinkshere-hideredirs' => '$1 redirecionamentos',
'whatlinkshere-hidetrans'  => '$1 transclusões',
'whatlinkshere-hidelinks'  => '$1 ligações',
'whatlinkshere-hideimages' => '$1 links de imagens',
'whatlinkshere-filters'    => 'Filtros',

# Block/unblock
'blockip'                         => 'Bloquear usuário',
'blockip-title'                   => 'Bloquear usuário',
'blockip-legend'                  => 'Bloquear usuário',
'blockiptext'                     => 'Utilize o formulário abaixo para bloquear o acesso à escrita de um endereço específico de IP ou nome de usuário.
Isto só deve ser feito para prevenir vandalismo, e de acordo com a [[{{MediaWiki:Policy-url}}|política]]. Preencha com um motivo específico a seguir (por exemplo, citando páginas que sofreram vandalismo).',
'ipaddress'                       => 'Endereço de IP:',
'ipadressorusername'              => 'Endereço de IP ou nome de usuário:',
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
** Nome de usuário inaceitável',
'ipbanononly'                     => 'Bloquear apenas usuário anônimos',
'ipbcreateaccount'                => 'Prevenir criação de conta de usuário',
'ipbemailban'                     => 'Impedir usuário de enviar e-mail',
'ipbenableautoblock'              => 'Bloquear automaticamente o endereço de IP mais recente usado por este usuário e todos os IPs subseqüentes dos quais ele tentar editar',
'ipbsubmit'                       => 'Bloquear este usuário',
'ipbother'                        => 'Outro período:',
'ipboptions'                      => '2 horas:2 hours,1 dia:1 day,3 dias:3 days,1 semana:1 week,2 semanas:2 weeks,1 mês:1 month,3 meses:3 months,6 meses:6 months,1 ano:1 year,indefinido:infinite',
'ipbotheroption'                  => 'outro',
'ipbotherreason'                  => 'Outro motivo/motivo adicional:',
'ipbhidename'                     => 'Ocultar nome de usuário em edições e listas',
'ipbwatchuser'                    => 'Vigiar a página de usuário e a página de discussão deste usuário',
'ipballowusertalk'                => 'Permitir que este usuário edite sua própria página de discussão mesmo estando bloqueado',
'ipb-change-block'                => 'Bloquear o usuário novamente com estes parâmetros',
'badipaddress'                    => 'Endereço de IP inválido',
'blockipsuccesssub'               => 'Bloqueio bem sucedido',
'blockipsuccesstext'              => '[[Special:Contributions/$1|$1]] foi bloqueado.<br />
Consulte a [[Special:IPBlockList|lista de IPs bloqueados]] para rever os bloqueios.',
'ipb-edit-dropdown'               => 'Editar motivos de bloqueio',
'ipb-unblock-addr'                => 'Desbloquear $1',
'ipb-unblock'                     => 'Desbloquear um usuário ou endereço de IP',
'ipb-blocklist-addr'              => 'Bloqueios existentes para $1',
'ipb-blocklist'                   => 'Ver bloqueios em vigência',
'ipb-blocklist-contribs'          => 'Contribuições de $1',
'unblockip'                       => 'Desbloquear usuário',
'unblockiptext'                   => 'Utilize o formulário a seguir para restaurar o acesso à escrita para um endereço de IP ou usuário previamente bloqueado.',
'ipusubmit'                       => 'Remover este bloqueio',
'unblocked'                       => '[[User:$1|$1]] foi desbloqueado',
'unblocked-id'                    => 'O bloqueio de $1 foi removido com sucesso',
'ipblocklist'                     => 'Usuários e endereços de IP bloqueados',
'ipblocklist-legend'              => 'Procurar por um usuário bloqueado',
'ipblocklist-username'            => 'Usuário ou endereço de IP:',
'ipblocklist-sh-userblocks'       => '$1 bloqueios de contas',
'ipblocklist-sh-tempblocks'       => '$1 bloqueios temporários',
'ipblocklist-sh-addressblocks'    => '$1 bloqueios de IP único',
'ipblocklist-submit'              => 'Pesquisar',
'ipblocklist-localblock'          => 'Bloqueio local',
'ipblocklist-otherblocks'         => '{{PLURAL:$1|Outro bloqueio|Outros bloqueios}}',
'blocklistline'                   => '$1, $2 bloqueou $3 ($4)',
'infiniteblock'                   => 'infinito',
'expiringblock'                   => 'expira em $1 às $2',
'anononlyblock'                   => 'anôn. apenas',
'noautoblockblock'                => 'bloqueio automático desabilitado',
'createaccountblock'              => 'criação de conta de usuário bloqueada',
'emailblock'                      => 'impedido de enviar e-mail',
'blocklist-nousertalk'            => 'impossibilitado de editar a própria página de discussão',
'ipblocklist-empty'               => 'A lista de bloqueios encontra-se vazia.',
'ipblocklist-no-results'          => 'O endereço de IP ou nome de usuário procurado não se encontra bloqueado.',
'blocklink'                       => 'bloquear',
'unblocklink'                     => 'desbloquear',
'change-blocklink'                => 'alterar bloqueio',
'contribslink'                    => 'contribs',
'autoblocker'                     => 'Você foi automaticamente bloqueado, pois partilha um endereço de IP com "[[User:$1|$1]]". O motivo apresentado foi: "$2".',
'blocklogpage'                    => 'Registro de bloqueio',
'blocklog-showlog'                => 'Este usuário já foi bloqueado anteriormente.
O registro de bloqueio é fornecido abaixo, para referência:',
'blocklog-showsuppresslog'        => 'O usuário foi bloqueado e ocultado anteriormente.
O registro de supressão é fornecido abaixo para referência:',
'blocklogentry'                   => '"[[$1]]" foi bloqueado com um tempo de expiração de $2 $3',
'reblock-logentry'                => 'modificou parâmetros de bloqueio para [[$1]] com um tempo de expiração de $2 $3',
'blocklogtext'                    => 'Este é um registro de ações de bloqueio e desbloqueio.
Endereços IP sujeitos a bloqueio automático não são listados.
Consulte a [[Special:IPBlockList|lista de IPs bloqueados]] para obter a lista de bloqueios e banimentos atualmente válidos.',
'unblocklogentry'                 => 'desbloqueou $1',
'block-log-flags-anononly'        => 'apenas usuários anônimos',
'block-log-flags-nocreate'        => 'criação de contas desabilitada',
'block-log-flags-noautoblock'     => 'bloqueio automático desabilitado',
'block-log-flags-noemail'         => 'impedido de enviar e-mail',
'block-log-flags-nousertalk'      => 'impossibilitado de editar a própria página de discussão',
'block-log-flags-angry-autoblock' => 'autobloqueio melhorado ativado',
'block-log-flags-hiddenname'      => 'Nome de usuário oculto',
'range_block_disabled'            => 'A funcionalidade de bloquear gamas de IPs encontra-se desativada.',
'ipb_expiry_invalid'              => 'Tempo de expiração inválido.',
'ipb_expiry_temp'                 => 'Bloqueios com nome de usuário ocultado devem ser permanentes.',
'ipb_hide_invalid'                => 'Não foi possível suprimir esta conta; ela poderá ter demasiadas edições.',
'ipb_already_blocked'             => '"$1" já se encontra bloqueado',
'ipb-needreblock'                 => '== Já se encontra bloqueado ==
$1 já se encontra bloqueado. Deseja alterar as configurações?',
'ipb-otherblocks-header'          => '{{PLURAL:$1|Outro bloqueio|Outros bloqueios}}',
'ipb_cant_unblock'                => 'Erro: Bloqueio com ID $1 não encontrado. Poderá já ter sido desbloqueado.',
'ipb_blocked_as_range'            => 'Erro: O IP $1 não se encontra bloqueado de forma direta, não podendo ser desbloqueado deste modo. Se encontra bloqueado como parte do "range" $2, o qual pode ser desbloqueado.',
'ip_range_invalid'                => 'Gama de IPs inválida.',
'ip_range_toolarge'               => 'Intervalos de bloqueio maiores do que /$1 não são permitidos',
'blockme'                         => 'Bloquear-me',
'proxyblocker'                    => 'Bloqueador de proxy',
'proxyblocker-disabled'           => 'Esta função está desabilitada.',
'proxyblockreason'                => 'O seu endereço de IP foi bloqueado por ser um proxy público. Por favor contacte o seu fornecedor do serviço de Internet ou o apoio técnico e informe-os deste problema de segurança grave.',
'proxyblocksuccess'               => 'Concluído.',
'sorbsreason'                     => 'O seu endereço IP encontra-se listado como proxy aberto pela DNSBL utilizada por {{SITENAME}}.',
'sorbs_create_account_reason'     => 'O seu endereço de IP encontra-se listado como proxy aberto na DNSBL utilizada por {{SITENAME}}. Você não pode criar uma conta',
'cant-block-while-blocked'        => 'Você não pode bloquear outros usuários enquanto estiver bloqueado.',
'cant-see-hidden-user'            => 'O usuário que você está tentando bloquear já está bloqueado ou oculto. Como você não possui privilégio de ocultar usuários, você não pode ver ou editar o bloqueio desse usuário.',

# Developer tools
'lockdb'              => 'Trancar banco de dados',
'unlockdb'            => 'Destrancar banco de dados',
'lockdbtext'          => 'Trancar o banco de dados suspenderá a habilidade de todos os usuários de editarem páginas, mudarem suas preferências, lista de páginas vigiadas e outras coisas que requerem mudanças na base de dados.<br />
Por favor, confirme que você realmente pretende fazer isso e que vai destrancar a base de dados quando a manutenção estiver concluída.',
'unlockdbtext'        => 'Desbloquear a base de dados vai restaurar a habilidade de todos os usuários de editarem páginas, mudarem suas preferências, alterarem suas listas de páginas vigiadas e outras coisas que requerem mudanças na base de dados.
Por favor, confirme que realmente pretende fazer isso.',
'lockconfirm'         => 'Sim, eu realmente desejo bloquear a base de dados.',
'unlockconfirm'       => 'Sim, eu realmente desejo desbloquear a base de dados.',
'lockbtn'             => 'Bloquear base de dados',
'unlockbtn'           => 'Desbloquear base de dados',
'locknoconfirm'       => 'Você não selecionou a caixa de confirmação.',
'lockdbsuccesssub'    => 'Bloqueio bem sucedido',
'unlockdbsuccesssub'  => 'Desbloqueio bem sucedido',
'lockdbsuccesstext'   => 'A base de dados da {{SITENAME}} foi bloqueada.
<br />Lembre-se de remover o bloqueio após a manutenção.',
'unlockdbsuccesstext' => 'O banco de dados foi desbloqueado.',
'lockfilenotwritable' => 'O arquivo de bloqueio da base de dados não pode ser escrito. Para bloquear ou desbloquear a base de dados, este precisa de poder ser escrito pelo servidor Web.',
'databasenotlocked'   => 'A base de dados não encontra-se bloqueada.',

# Move page
'move-page'                    => 'Mover $1',
'move-page-legend'             => 'Mover página',
'movepagetext'                 => "Utilizando o seguinte formulário você poderá renomear uma página, movendo todo o histórico para o novo título. O título anterior será transformado em um redirecionamento para o novo.

Links para as páginas antigas não serão mudados; certifique-se de verificar por redirecionamentos quebrados ou duplos. Você é responsável por certificar-se que os links continuam apontando para onde eles deveriam apontar.

Note que a página '''não''' será movida se já existir uma página com o novo título, a não ser que ele esteja vazio ou seja um redirecionamento e não tenha histórico de edições. Isto significa que pode renomear uma página de volta para o nome que tinha anteriormente se cometer algum engano e que não pode sobrescrever uma página.

<b>CUIDADO!</b>
Isto pode ser uma mudança drástica e inesperada para uma página popular; por favor, tenha certeza de que compreende as consequências da mudança antes de prosseguir.",
'movepagetalktext'             => "A página de \"discussão\" associada, se existir, será automaticamente movida, '''a não ser que:'''
*Uma página de discussão com conteúdo já exista sob o novo título, ou
*Você não marque a caixa abaixo.

Nestes casos, você terá que mover ou mesclar a página manualmente, se assim desejar.",
'movearticle'                  => 'Mover página',
'moveuserpage-warning'         => "'''Aviso:''' Você irá mover uma página de usuário. Note que apenas a página será movida, ''sem'' alterar o nome do usuário.",
'movenologin'                  => 'Não autenticado',
'movenologintext'              => 'Você precisa ser um usuário registrado e [[Special:UserLogin|autenticado]] para poder mover uma página.',
'movenotallowed'               => 'Você não possui permissão para mover páginas.',
'movenotallowedfile'           => 'Você não possui permissão para mover arquivos.',
'cant-move-user-page'          => 'Você não possui permissão de mover páginas principais de usuários.',
'cant-move-to-user-page'       => 'Você não tem permissão para mover uma página para uma página de usuários (exceto para uma subpágina de usuário).',
'newtitle'                     => 'Para novo título',
'move-watch'                   => 'Vigiar esta página',
'movepagebtn'                  => 'Mover página',
'pagemovedsub'                 => 'Página movida com sucesso',
'movepage-moved'               => '\'\'\'"$1" foi movida para "$2"\'\'\'',
'movepage-moved-redirect'      => 'Um redirecionamento foi criado.',
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
'movelogpage'                  => 'Registro de movimento',
'movelogpagetext'              => 'Abaixo encontra-se uma lista de páginas movidas.',
'movesubpage'                  => '{{PLURAL:$1|Subpágina|Subpáginas}}',
'movesubpagetext'              => 'Esta página tem $1 {{PLURAL:$1|subpágina mostrada|subpáginas mostradas}} abaixo.',
'movenosubpage'                => 'Esta página não tem subpáginas.',
'movereason'                   => 'Motivo:',
'revertmove'                   => 'reverter',
'delete_and_move'              => 'Eliminar e mover',
'delete_and_move_text'         => '==Eliminação necessária==
A página de destino ("[[:$1]]") já existe. Deseja eliminá-la de modo a poder mover?',
'delete_and_move_confirm'      => 'Sim, eliminar a página',
'delete_and_move_reason'       => 'Eliminada para poder mover outra página para este título',
'selfmove'                     => 'O título fonte e o título destinatário são os mesmos; não é possível mover uma página para ela mesma.',
'immobile-source-namespace'    => 'Não é possível mover páginas no espaço nominal "$1"',
'immobile-target-namespace'    => 'Não é possível mover páginas para o espaço nominal "$1"',
'immobile-target-namespace-iw' => 'Uma ligação interwiki não é um destino válido para uma movimentação de página.',
'immobile-source-page'         => 'Esta página não pode ser movida.',
'immobile-target-page'         => 'Não é possível mover para esse título de destino.',
'imagenocrossnamespace'        => 'Não é possível mover imagem para espaço nominal que não de imagens',
'imagetypemismatch'            => 'A extensão do novo arquivo não corresponde ao seu tipo',
'imageinvalidfilename'         => 'O nome do arquivo alvo é inválido',
'fix-double-redirects'         => 'Atualizar todos os redirecionamentos que apontem para o título original',
'move-leave-redirect'          => 'Criar um redirecionamento',
'protectedpagemovewarning'     => "'''Atenção:''' Esta página foi protegida de modo que apenas usuários com privilégio de administrador possam movê-la.
A última entrada no histórico é fornecida abaixo para referência:",
'semiprotectedpagemovewarning' => "''Nota:''' Esta página foi protegida de modo que apenas usuários registrados possam movê-la.
A última entrada no histórico é fornecida abaixo para referência:",
'move-over-sharedrepo'         => '=== Arquivo existente ===
[[:$1]] existe em um repositório compartilhado. Mover um arquivo para este título irá sobrescrever o arquivo compartilhado.',
'file-exists-sharedrepo'       => 'O nome de arquivo escolhido já está em uso em um repositório compartilhado.
Por favor, escolha outro nome.',

# Export
'export'            => 'Exportação de páginas',
'exporttext'        => 'Você pode exportar o texto e o histórico de edições de uma página em particular para um arquivo XML. Poderá então importar esse conteúdo noutra wiki que utilize o software MediaWiki através da [[Special:Import|página de importações]].

Para exportar páginas, introduza os títulos na caixa de texto abaixo (um título por linha) e selecione se deseja todas as versões, com as linhas de histórico de edições, ou apenas a edição atual e informações apenas sobre a mais recente das edições.

Se desejar, pode utilizar uma ligação (por exemplo, [[{{#Special:Export}}/{{MediaWiki:Mainpage}}]] para a [[{{MediaWiki:Mainpage}}]]).',
'exportcuronly'     => 'Incluir apenas a revisão atual, não o histórico inteiro',
'exportnohistory'   => "----
'''Nota:''' a exportação do histórico completo das páginas através deste formulário foi desativada devido a questões de performance.",
'export-submit'     => 'Exportar',
'export-addcattext' => 'Adicionar à listagem páginas da categoria:',
'export-addcat'     => 'Adicionar',
'export-addnstext'  => 'Adicionar páginas do domínio:',
'export-addns'      => 'Adicionar',
'export-download'   => 'Oferecer para salvar como um arquivo',
'export-templates'  => 'Incluir predefinições',
'export-pagelinks'  => 'Incluir páginas ligadas até uma profundidade de:',

# Namespace 8 related
'allmessages'                   => 'Todas as mensagens de sistema',
'allmessagesname'               => 'Nome',
'allmessagesdefault'            => 'Texto padrão',
'allmessagescurrent'            => 'Texto atual',
'allmessagestext'               => 'Esta é uma lista de todas as mensagens de sistema disponíveis no espaço nominal {{ns:mediawiki}}.
Acesse [http://www.mediawiki.org/wiki/Localisation MediaWiki Localisation] e [http://translatewiki.net translatewiki.net] caso deseje contribuir para traduções do MediaWiki feitas para uso geral.',
'allmessagesnotsupportedDB'     => "Esta página não pode ser utilizada, uma vez que '''\$wgUseDatabaseMessages''' foi desativado.",
'allmessages-filter-legend'     => 'Filtro',
'allmessages-filter'            => 'Filtrar por estado de personalização:',
'allmessages-filter-unmodified' => 'Não modificadas',
'allmessages-filter-all'        => 'Todas',
'allmessages-filter-modified'   => 'Modificadas',
'allmessages-prefix'            => 'Filtrar por prefixo:',
'allmessages-language'          => 'Idioma:',
'allmessages-filter-submit'     => 'Ir',

# Thumbnails
'thumbnail-more'           => 'Ampliar',
'filemissing'              => 'arquivo não encontrado',
'thumbnail_error'          => 'Erro ao criar miniatura: $1',
'djvu_page_error'          => 'página DjVu inacessível',
'djvu_no_xml'              => 'Não foi possível acessar o XML do arquivo DjVU',
'thumbnail_invalid_params' => 'Parâmetros de miniatura inválidos',
'thumbnail_dest_directory' => 'Não foi possível criar o diretório de destino',
'thumbnail_image-type'     => 'Tipo de imagem não suportado',
'thumbnail_gd-library'     => 'Configuração da biblioteca GD incompleta: função $1 não encontrada',
'thumbnail_image-missing'  => 'Arquivo aparentemente inexistente: $1',

# Special:Import
'import'                     => 'Importar páginas',
'importinterwiki'            => 'Importação transwiki',
'import-interwiki-text'      => 'Selecione uma wiki e um título de página a importar.
As datas das edições e os seus editores serão mantidos.
Todas as acções de importação transwiki são registradas no [[Special:Log/import|Registro de importações]].',
'import-interwiki-source'    => 'Wiki/página fonte:',
'import-interwiki-history'   => 'Copiar todas as edições para esta página',
'import-interwiki-templates' => 'Incluir todas as predefinições',
'import-interwiki-submit'    => 'Importar',
'import-interwiki-namespace' => 'Domínio de destino:',
'import-upload-filename'     => 'Nome do arquivo:',
'import-comment'             => 'Comentário:',
'importtext'                 => 'Por favor, exporte o arquivo da fonte wiki utilizando a ferramenta {{ns:special}}:Export, salve o arquivo para o seu disco e importe-o aqui.',
'importstart'                => 'Importando páginas...',
'import-revision-count'      => '{{PLURAL:$1|uma edição|$1 edições}}',
'importnopages'              => 'Não existem páginas a importar.',
'importfailed'               => 'A importação falhou: $1',
'importunknownsource'        => 'Tipo de fonte de importação desconhecida',
'importcantopen'             => 'Não foi possível abrir o arquivo de importação',
'importbadinterwiki'         => 'Ligação de interwiki incorreta',
'importnotext'               => 'Vazio ou sem texto',
'importsuccess'              => 'Importação completa!',
'importhistoryconflict'      => 'Existem conflitos de edições no histórico (talvez esta página já foi importada antes)',
'importnosources'            => 'Não foram definidas fontes de importação transwiki e o carregamento direto de históricos encontra-se desativado.',
'importnofile'               => 'Nenhum arquivo de importação foi carregado.',
'importuploaderrorsize'      => 'O envio do arquivo a ser importado falhou. O arquivo é maior do que o tamanho máximo permitido para upload.',
'importuploaderrorpartial'   => 'O envio do arquivo a ser importado falhou. O arquivo foi recebido parcialmente.',
'importuploaderrortemp'      => 'O envio do arquivo a ser importado falhou. Não há um diretório temporário.',
'import-parse-failure'       => 'Falha ao importar dados XML',
'import-noarticle'           => 'Sem páginas para importar!',
'import-nonewrevisions'      => 'Todas as edições já haviam sido importadas.',
'xml-error-string'           => '$1 na linha $2, coluna $3 (byte $4): $5',
'import-upload'              => 'Enviar dados em XML',
'import-token-mismatch'      => 'Perda dos dados da sessão. Por favor tente novamente.',
'import-invalid-interwiki'   => 'Não é possível importar da wiki especificada.',

# Import log
'importlogpage'                    => 'Registro de importações',
'importlogpagetext'                => 'Importações administrativas de páginas com a preservação do histórico de edição de outras wikis.',
'import-logentry-upload'           => 'importou [[$1]] através de arquivo de importação',
'import-logentry-upload-detail'    => '{{PLURAL:$1|uma edição|$1 edições}}',
'import-logentry-interwiki'        => 'transwiki $1',
'import-logentry-interwiki-detail' => '{{PLURAL:$1|$1 edição|$1 edições}} de $2',

# Tooltip help for the actions
'tooltip-pt-userpage'             => 'Sua página de usuário',
'tooltip-pt-anonuserpage'         => 'A página de usuário para o ip com o qual você está editando',
'tooltip-pt-mytalk'               => 'Sua página de discussão',
'tooltip-pt-anontalk'             => 'Discussão sobre edições deste endereço de ip',
'tooltip-pt-preferences'          => 'Suas preferências',
'tooltip-pt-watchlist'            => 'A lista de páginas cujas alterações você está monitorando',
'tooltip-pt-mycontris'            => 'Lista das suas contribuições',
'tooltip-pt-login'                => 'Você é encorajado a autenticar-se, apesar disso não ser obrigatório.',
'tooltip-pt-anonlogin'            => 'Você é encorajado a autenticar-se, apesar disso não ser obrigatório.',
'tooltip-pt-logout'               => 'Sair',
'tooltip-ca-talk'                 => 'Discussão sobre o conteúdo da página',
'tooltip-ca-edit'                 => 'Você pode editar esta página. Por favor, utilize o botão Mostrar Previsão antes de salvar.',
'tooltip-ca-addsection'           => 'Iniciar uma nova seção',
'tooltip-ca-viewsource'           => 'Esta página está protegida; você pode exibir seu código, no entanto.',
'tooltip-ca-history'              => 'Edições anteriores desta página.',
'tooltip-ca-protect'              => 'Proteger esta página',
'tooltip-ca-unprotect'            => 'Desproteger esta página',
'tooltip-ca-delete'               => 'Apagar esta página',
'tooltip-ca-undelete'             => 'Restaurar edições feitas a esta página antes da eliminação',
'tooltip-ca-move'                 => 'Mover esta página',
'tooltip-ca-watch'                => 'Adicionar esta página às páginas vigiadas',
'tooltip-ca-unwatch'              => 'Remover esta página da lista de páginas vigiadas',
'tooltip-search'                  => 'Pesquisar nesta wiki',
'tooltip-search-go'               => 'Ir a uma página com este exato nome, caso exista',
'tooltip-search-fulltext'         => 'Procurar por páginas contendo este texto',
'tooltip-p-logo'                  => 'Página principal',
'tooltip-n-mainpage'              => 'Visitar a página principal',
'tooltip-n-mainpage-description'  => 'Visitar a página principal',
'tooltip-n-portal'                => 'Sobre o projeto',
'tooltip-n-currentevents'         => 'Informação temática sobre eventos atuais',
'tooltip-n-recentchanges'         => 'A lista de mudanças recentes nesta wiki.',
'tooltip-n-randompage'            => 'Abrir uma página aleatoriamente',
'tooltip-n-help'                  => 'Um local reservado para auxílio.',
'tooltip-t-whatlinkshere'         => 'Lista de todas as páginas que ligam-se a esta',
'tooltip-t-recentchangeslinked'   => 'Mudanças recentes em páginas relacionadas a esta',
'tooltip-feed-rss'                => 'Feed RSS desta página',
'tooltip-feed-atom'               => 'Feed Atom desta página',
'tooltip-t-contributions'         => 'Ver as contribuições deste usuário',
'tooltip-t-emailuser'             => 'Enviar um e-mail a este usuário',
'tooltip-t-upload'                => 'Carregar arquivos',
'tooltip-t-specialpages'          => 'Lista de páginas especiais',
'tooltip-t-print'                 => 'Versão para impressão desta página',
'tooltip-t-permalink'             => 'Link permanente para esta versão desta página',
'tooltip-ca-nstab-main'           => 'Ver a página de conteúdo',
'tooltip-ca-nstab-user'           => 'Ver a página de usuário',
'tooltip-ca-nstab-media'          => 'Ver a página de mídia',
'tooltip-ca-nstab-special'        => 'Esta é uma página especial, não pode ser editada.',
'tooltip-ca-nstab-project'        => 'Ver a página de projeto',
'tooltip-ca-nstab-image'          => 'Ver a página de arquivo',
'tooltip-ca-nstab-mediawiki'      => 'Ver a mensagem de sistema',
'tooltip-ca-nstab-template'       => 'Ver a predefinição',
'tooltip-ca-nstab-help'           => 'Ver a página de ajuda',
'tooltip-ca-nstab-category'       => 'Ver a página da categoria',
'tooltip-minoredit'               => 'Marcar como edição menor',
'tooltip-save'                    => 'Salvar as alterações',
'tooltip-preview'                 => 'Prever as alterações, por favor utilizar antes de salvar!',
'tooltip-diff'                    => 'Mostrar alterações que fez a este texto.',
'tooltip-compareselectedversions' => 'Ver as diferenças entre as duas versões selecionadas desta página.',
'tooltip-watch'                   => 'Adicionar esta página à sua lista de páginas vigiadas',
'tooltip-recreate'                => 'Recriar a página apesar de ter sido eliminada',
'tooltip-upload'                  => 'Iniciar o carregamento',
'tooltip-rollback'                => '"{{int:rollbacklink}}" reverte, com um só clique, as edições do último editor desta página.',
'tooltip-undo'                    => '"{{int:editundo}}" reverte esta edição exibindo a caixa de edição no modo de previsão, permitindo alterações adicionais e o uso do sumário de edição para justificativas.',

# Stylesheets
'common.css'   => '/** o código CSS colocado aqui será aplicado a todos os temas */',
'monobook.css' => '/* o código CSS colocado aqui terá efeito nos usuários do tema Monobook */',

# Scripts
'common.js'      => '/* Códigos JavaScript aqui colocados serão carregados por todos aqueles que acessarem alguma página deste wiki */',
'standard.js'    => '/* Qualquer JavaScript aqui colocado afetará os usuários do skin Clássico */',
'nostalgia.js'   => '/* Qualquer JavaScript aqui colocado afetará os usuários do skin Nostalgia */',
'cologneblue.js' => '/* Qualquer JavaScript aqui colocado afetará os usuários do skin Azul colonial */',
'monobook.js'    => '/* Qualquer JavaScript aqui colocado afetará os usuários do skin MonoBook */',
'myskin.js'      => '/* Qualquer JavaScript aqui colocado afetará os usuários do skin MySkin */',
'chick.js'       => '/* Qualquer JavaScript aqui colocado afetará os usuários do skin Chique */',
'simple.js'      => '/* Qualquer JavaScript aqui colocado afetará os usuários do skin Simples */',
'modern.js'      => '/* Qualquer JavaScript aqui colocado afetará os usuários do skin Moderno */',
'vector.js'      => '/* Qualquer JavaScript aqui colocado afetará os usuários do skin Vector */',

# Metadata
'nodublincore'      => 'Os metadados RDF para Dublin Core estão desabilitados neste servidor.',
'nocreativecommons' => 'Os metadados RDF para Creative Commons estão desabilitados neste servidor.',
'notacceptable'     => 'O servidor não pode fornecer os dados em um formato que o seu cliente possa ler.',

# Attribution
'anonymous'        => '{{PLURAL:$1|Usuário anônimo|Usuários anônimos}} da {{SITENAME}}',
'siteuser'         => '{{GENDER:$2|um utilizador|uma utilizadora|um utilizador}} da {{SITENAME}} ($1)',
'anonuser'         => 'usuário anônimo $1 da {{SITENAME}}',
'lastmodifiedatby' => 'Esta página foi modificada pela última vez às $2 de $1 por $3.',
'othercontribs'    => 'Baseado no trabalho de $1.',
'others'           => 'outros',
'siteusers'        => '{{PLURAL:$2|um usuário|$2 usuários}} da {{SITENAME}} ($1)',
'anonusers'        => '{{PLURAL:$2|usuário anônimo|usuários anônimos}} da {{SITENAME}} ($1)',
'creditspage'      => 'Créditos da página',
'nocredits'        => 'Não há informações disponíveis sobre os créditos desta página.',

# Spam protection
'spamprotectiontitle' => 'Filtro de proteção contra spam',
'spamprotectiontext'  => "A página que deseja salvar foi bloqueada pelo filtro de spam.
Tal bloqueio foi provavelmente causado por uma ligação para um ''website'' externo que conste na lista negra.",
'spamprotectionmatch' => 'O seguinte texto ativou o filtro de spam: $1',
'spambot_username'    => 'MediaWiki limpeza de spam',
'spam_reverting'      => 'Revertendo para a última versão que não contém links para $1',
'spam_blanking'       => 'Todas revisões contendo hiperligações para $1, limpando',

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
'math_bad_tmpdir'       => 'Ocorreram problemas na criação ou escrita no diretório temporário math',
'math_bad_output'       => 'Ocorreram problemas na criação ou escrita no diretório de resultados math',
'math_notexvc'          => 'O executável texvc não foi encontrado. Consulte math/README para instruções da configuração.',

# Patrolling
'markaspatrolleddiff'                 => 'Marcar como verificado',
'markaspatrolledtext'                 => 'Marcar esta página como verificada',
'markedaspatrolled'                   => 'Marcado como verificado',
'markedaspatrolledtext'               => 'A revisão selecionada de [[:$1]] foi marcada como patrulhada.',
'rcpatroldisabled'                    => 'Edições verificadas nas Mudanças Recentes desativadas',
'rcpatroldisabledtext'                => 'A funcionalidade de Edições verificadas nas Mudanças Recentes está atualmente desativada.',
'markedaspatrollederror'              => 'Não é possível marcar como verificado',
'markedaspatrollederrortext'          => 'Você precisa de especificar uma revisão para poder marcar como verificado.',
'markedaspatrollederror-noautopatrol' => 'Você não está autorizado a marcar suas próprias edições como edições patrulhadas.',

# Patrol log
'patrol-log-page'      => 'Registro de edições patrulhadas',
'patrol-log-header'    => 'Este é um registro de edições patrulhadas.',
'patrol-log-line'      => 'marcou a $1 de $2 como uma edição patrulhada $3',
'patrol-log-auto'      => 'automaticamente',
'patrol-log-diff'      => 'edição $1',
'log-show-hide-patrol' => '$1 registro de edições patrulhadas',

# Image deletion
'deletedrevision'                 => 'Apagou a versão antiga $1',
'filedeleteerror-short'           => 'Erro ao eliminar arquivo: $1',
'filedeleteerror-long'            => 'Foram encontrados erros ao tentar eliminar o arquivo:

$1',
'filedelete-missing'              => 'Não é possível eliminar "$1" já que o arquivo não existe.',
'filedelete-old-unregistered'     => 'A revisão de arquivo especificada para "$1" não se encontra na base de dados.',
'filedelete-current-unregistered' => 'O arquivo "$1" não se encontra na base de dados.',
'filedelete-archive-read-only'    => 'O servidor web não é capaz de fazer alterações no diretório "$1".',

# Browsing diffs
'previousdiff' => '← Edição anterior',
'nextdiff'     => 'Edição posterior →',

# Media information
'mediawarning'         => "'''Aviso''': Este tipo de arquivo pode conter código malicioso.
Executá-lo poderá comprometer a segurança do seu sistema.",
'imagemaxsize'         => "Limite de tamanho de imagem:<br />''(para páginas de descrição de arquivos)''",
'thumbsize'            => 'Tamanho de miniaturas:',
'widthheightpage'      => '$1×$2, $3 {{PLURAL:$3|página|páginas}}',
'file-info'            => '(tamanho: $1, tipo MIME: $2)',
'file-info-size'       => '($1 × $2 pixels, tamanho: $3, tipo MIME: $4)',
'file-nohires'         => '<small>Sem resolução maior disponível.</small>',
'svg-long-desc'        => '(arquivo SVG, de $1 × $2 pixels, tamanho: $3)',
'show-big-image'       => 'Resolução completa',
'show-big-image-thumb' => '<small>Tamanho desta previsão: $1 × $2 pixels</small>',
'file-info-gif-looped' => 'cíclico',
'file-info-gif-frames' => '$1 {{PLURAL:$1|quadro|quadros}}',

# Special:NewFiles
'newimages'             => 'Galeria de novos arquivos',
'imagelisttext'         => "É exibida a seguir uma listagem {{PLURAL:$1|de '''um''' arquivo organizado|de '''$1''' arquivos organizados}} por $2.",
'newimages-summary'     => 'Esta página especial mostra os arquivos mais recentemente enviados',
'newimages-legend'      => 'Filtrar',
'newimages-label'       => 'Nome de arquivo (ou parte dele):',
'showhidebots'          => '($1 robôs)',
'noimages'              => 'Nada para ver.',
'ilsubmit'              => 'Procurar',
'bydate'                => 'por data',
'sp-newimages-showfrom' => 'Mostrar novos arquivos a partir das $2 de $1',

# Bad image list
'bad_image_list' => 'O formato é o seguinte:

Só itens da lista (linhas começando com *) são considerados.
A primeira ligação em uma linha deve ser uma ligação para um arquivo ruim.
Qualquer ligação posterior na mesma linha são consideradas como exceções, ou seja, páginas nas quais o arquivo pode aparecer na linha.',

# Metadata
'metadata'          => 'Metadados',
'metadata-help'     => "Este arquivo contém informação adicional, provavelmente adicionada a partir da câmara digital ou ''scanner'' utilizada para criar ou digitalizá-lo.
Caso o arquivo tenha sido modificado a partir do seu estado original, alguns detalhes poderão não refletir completamente as mudanças efetuadas.",
'metadata-expand'   => 'Mostrar detalhes adicionais',
'metadata-collapse' => 'Esconder detalhes restantes',
'metadata-fields'   => 'Os campos de metadados EXIF listados nesta mensagem poderão estar presente na exibição da página de imagem quando a tabela de metadados estiver no modo "expandida". Outros poderão estar escondidos por padrão.
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
'exif-datetime'                    => 'Data e hora de modificação do arquivo',
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
'exif-usercomment'                 => 'Comentários de usuários',
'exif-relatedsoundfile'            => 'arquivo áudio relacionado',
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
'exif-oecf'                        => 'Fator de conversão optoeletrônica.',
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
'exif-filesource'                  => 'Fonte do arquivo',
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
'exif-gpstimestamp'                => 'Tempo GPS (relógio atômico)',
'exif-gpssatellites'               => 'Satélites utilizados para a medição',
'exif-gpsstatus'                   => 'Estado do receptor',
'exif-gpsmeasuremode'              => 'Modo da medição',
'exif-gpsdop'                      => 'Precisão da medição',
'exif-gpsspeedref'                 => 'Unidade da velocidade',
'exif-gpsspeed'                    => 'Velocidade do receptor GPS',
'exif-gpstrackref'                 => 'Referência para a direção do movimento',
'exif-gpstrack'                    => 'Direção do movimento',
'exif-gpsimgdirectionref'          => 'Referência para a direção da imagem',
'exif-gpsimgdirection'             => 'Direção da imagem',
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
'exif-gpsdifferential'             => 'Correção do diferencial do GPS',

# EXIF attributes
'exif-compression-1' => 'Sem compressão',

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
'exif-flash-return-0'   => "''strobe'' não encontrou ou detectou nenhuma função",
'exif-flash-return-2'   => "''strobe'' não retornou a função detectada",
'exif-flash-return-3'   => "''strobe'' retornou a luz detectada",
'exif-flash-mode-1'     => 'disparo de flash forçado',
'exif-flash-mode-2'     => "disparo de ''flash'' suprimido",
'exif-flash-mode-3'     => 'modo automático',
'exif-flash-function-1' => "Sem função de ''flash''",
'exif-flash-redeye-1'   => 'modo de redução de olhos vermelhos',

'exif-focalplaneresolutionunit-2' => 'polegadas',

'exif-sensingmethod-1' => 'Indefinido',
'exif-sensingmethod-2' => 'Sensor de áreas de cores de um chip',
'exif-sensingmethod-3' => 'Sensor de áreas de cores de dois chips',
'exif-sensingmethod-4' => 'Sensor de áreas de cores de três chips',
'exif-sensingmethod-5' => 'Sensor de área sequencial de cores',
'exif-sensingmethod-7' => 'Sensor trilinear',
'exif-sensingmethod-8' => 'Sensor linear sequencial de cores',

'exif-scenetype-1' => 'Imagem fotografada diretamente',

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
'exif-gpsdirection-t' => 'Direção real',
'exif-gpsdirection-m' => 'Direção magnética',

# External editor support
'edit-externally'      => 'Editar este arquivo utilizando uma aplicação externa',
'edit-externally-help' => '(Consulte as [http://www.mediawiki.org/wiki/Manual:External_editors instruções de instalação] para maiores informações)',

# 'all' in various places, this might be different for inflected languages
'recentchangesall' => 'todas',
'imagelistall'     => 'todas',
'watchlistall2'    => 'todas',
'namespacesall'    => 'todas',
'monthsall'        => 'todos',
'limitall'         => 'todas',

# E-mail address confirmation
'confirmemail'             => 'Confirmar endereço de E-mail',
'confirmemail_noemail'     => 'Não possui um endereço de e-mail válido indicado nas suas [[Special:Preferences|preferências de usuário]].',
'confirmemail_text'        => 'Esta wiki requer que valide o seu endereço de e-mail antes de utilizar as funcionalidades que requerem um endereço de e-mail. Ative o botão abaixo para enviar uma confirmação para o seu endereço de e-mail. A mensagem incluíra um endereço que contém um código; carregue o endereço no seu navegador para confirmar que o seu endereço de e-mail encontra-se válido.',
'confirmemail_pending'     => 'Um código de confirmação já foi enviado para você; caso tenha criado sua conta recentemente, é recomendável aguardar alguns minutos para o receber antes de tentar pedir um novo código.',
'confirmemail_send'        => 'Enviar código de confirmação',
'confirmemail_sent'        => 'E-mail de confirmação enviado.',
'confirmemail_oncreate'    => 'Foi enviado um código de confirmação para o seu endereço de e-mail.
Tal código não é exigido para que possa se autenticar no sistema, mas será necessário que você o forneça antes de habilitar qualquer ferramenta baseada no uso de e-mail deste wiki.',
'confirmemail_sendfailed'  => 'O {{SITENAME}} não pôde enviar o email de confirmação.
Verifique se o seu endereço de e-mail possui caracteres inválidos.

O mailer retornou: $1',
'confirmemail_invalid'     => 'Código de confirmação inválido. O código poderá ter expirado.',
'confirmemail_needlogin'   => 'Precisa de $1 para confirmar o seu endereço de e-mail.',
'confirmemail_success'     => 'O seu endereço de e-mail foi confirmado. Pode agora se ligar.',
'confirmemail_loggedin'    => 'O seu endereço de e-mail foi agora confirmado.',
'confirmemail_error'       => 'Alguma coisa correu mal ao guardar a sua confirmação.',
'confirmemail_subject'     => '{{SITENAME}} confirmação de endereço de e-mail',
'confirmemail_body'        => 'Alguém, provavelmente você, com o endereço de IP $1,
registrou uma conta "$2" com este endereço de e-mail em {{SITENAME}}.

Para confirmar que esta conta realmente é sua, e para ativar
as funcionalidades de e-mail em {{SITENAME}},
abra o seguinte endereço no seu navegador:

$3

Se você *não* registrou a conta, siga a seguinte ligação
para cancelar a confirmação do endereço de e-mail:

$5

Este código de confirmação irá expirar em $4.',
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
'confirmrecreate'     => "O usuário [[User:$1|$1]] ([[User talk:$1|Discussão]]) eliminou esta página após você ter começado a editar, pelo seguinte motivo:
: ''$2''
Por favor, confirme que realmente deseja recriar esta página.",
'recreate'            => 'Recriar',

# action=purge
'confirm_purge_button' => 'OK',
'confirm-purge-top'    => 'Limpar a memória cache desta página?',
'confirm-purge-bottom' => "Purgar uma página limpa o ''cache'' e força a sua versão mais recente a aparecer.",

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
'autoredircomment' => 'Redirecionando para [[$1]]',
'autosumm-new'     => "Criou página com '$1'",

# Live preview
'livepreview-loading' => 'Carregando…',
'livepreview-ready'   => 'Carregando… Pronto!',
'livepreview-failed'  => 'A previsão instantânea falhou!
Tente a previsão comum.',
'livepreview-error'   => 'Falha ao conectar: $1 "$2"
Tente a previsão comum.',

# Friendlier slave lag warnings
'lag-warn-normal' => 'É possível que as alterações que sejam mais recentes do que $1 {{PLURAL:$1|segundo|segundos}} não sejam exibidas nesta lista.',
'lag-warn-high'   => 'Devido a sérios problemas de latência no servidor do banco de dados, as alterações mais recentes que $1 {{PLURAL:$1|segundo|segundos}} poderão não ser exibidas nesta lista.',

# Watchlist editor
'watchlistedit-numitems'       => 'A sua lista de páginas vigiadas possui {{PLURAL:$1|um título|$1 títulos}}, além das respectivas páginas de discussão.',
'watchlistedit-noitems'        => 'A sua lista de páginas vigiadas não possui títulos.',
'watchlistedit-normal-title'   => 'Editar lista de páginas vigiadas',
'watchlistedit-normal-legend'  => 'Remover títulos da lista de páginas vigiadas',
'watchlistedit-normal-explain' => 'Os títulos das páginas de sua lista de vigiadas são exibidos abaixo.
Para remover um título, marque a caixa ao lado do mesmo e clique "{{int:Watchlistedit-normal-submit}}".
Você pode também [[Special:Watchlist/raw|editar a lista de páginas vigiadas em forma de texto]].',
'watchlistedit-normal-submit'  => 'Remover Títulos',
'watchlistedit-normal-done'    => '{{PLURAL:$1|um título foi removido|$1 títulos foram removidos}} de sua lista de páginas vigiadas:',
'watchlistedit-raw-title'      => 'Edição crua da lista de páginas vigiadas',
'watchlistedit-raw-legend'     => 'Edição crua da lista de páginas vigiadas',
'watchlistedit-raw-explain'    => 'A lista de páginas vigiadas é apresentada abaixo. Você pode adicionar novas linhas ou remover linhas para aumentar ou reduzir a lista, desde que mantenha uma única página por linha.
Quando terminar, clique "{{int:Watchlistedit-raw-submit}}".
Você também pode [[Special:Watchlist/edit|editar a lista da maneira convencional]].',
'watchlistedit-raw-titles'     => 'Títulos:',
'watchlistedit-raw-submit'     => 'Atualizar a lista de páginas vigiadas',
'watchlistedit-raw-done'       => 'Sua lista de páginas vigiadas foi atualizada.',
'watchlistedit-raw-added'      => '{{PLURAL:$1|Foi adicionado um título|Foram adicionados $1 títulos}}:',
'watchlistedit-raw-removed'    => '{{PLURAL:$1|Foi removido um título|Foram removidos $1 títulos}}:',

# Watchlist editing tools
'watchlisttools-view' => 'Ver alterações relevantes',
'watchlisttools-edit' => 'Ver e editar a lista de páginas vigiadas',
'watchlisttools-raw'  => 'Edição crua da lista de páginas vigiadas',

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
'filepath'         => 'Diretório do arquivo',
'filepath-page'    => 'arquivo:',
'filepath-submit'  => 'Ir',
'filepath-summary' => 'Através dsta página especial é possível descobrir o endereço completo de um determinado arquivo. As imagens serão exibidas em sua resolução máxima, outros tipos de arquivos serão iniciados automaticamente em seus programas correspondentes.

Entre com o nome do arquivo sem utilizar o prefixo "{{ns:file}}:".',

# Special:FileDuplicateSearch
'fileduplicatesearch'          => 'Procurar por arquivos duplicados',
'fileduplicatesearch-summary'  => 'Procure por arquivos duplicados tendo por base seu valor "hash".

Entre com o nome de arquivo sem fornecer o prefixo "{{ns:file}}:".',
'fileduplicatesearch-legend'   => 'Procurar por duplicatas',
'fileduplicatesearch-filename' => 'Nome do arquivo:',
'fileduplicatesearch-submit'   => 'Pesquisa',
'fileduplicatesearch-info'     => '$1 × $2 pixels<br />Tamanho: $3<br />tipo MIME: $4',
'fileduplicatesearch-result-1' => 'O arquivo "$1" não possui cópias idênticas.',
'fileduplicatesearch-result-n' => 'O arquivo "$1" possui {{PLURAL:$2|uma cópia idêntica|$2 cópias idênticas}}.',

# Special:SpecialPages
'specialpages'                   => 'Páginas especiais',
'specialpages-note'              => '----
* Páginas especiais normais.
* <strong class="mw-specialpagerestricted">Páginas especiais restritas.</strong>',
'specialpages-group-maintenance' => 'Relatórios de manutenção',
'specialpages-group-other'       => 'Outras páginas especiais',
'specialpages-group-login'       => 'Entrar / registrar-se',
'specialpages-group-changes'     => 'Mudanças e registros recentes',
'specialpages-group-media'       => 'Relatórios de mídias e uploads',
'specialpages-group-users'       => 'Usuários e privilégios',
'specialpages-group-highuse'     => 'Páginas muito usadas',
'specialpages-group-pages'       => 'Listas de páginas',
'specialpages-group-pagetools'   => 'Ferramentas de páginas',
'specialpages-group-wiki'        => 'Dados e ferramentas sobre este wiki',
'specialpages-group-redirects'   => 'Páginas especiais redirecionadas',
'specialpages-group-spam'        => 'Ferramentas anti-spam',

# Special:BlankPage
'blankpage'              => 'Página em branco',
'intentionallyblankpage' => 'Esta página foi intencionalmente deixada em branco e é usada para medições de performance, etc.',

# External image whitelist
'external_image_whitelist' => " # Deixe esta linha exatamente como ela é <pre> 
# Coloque uma expressão regular (apenas a parte que vai entre o //) a seguir
# Estas serão casadas com as URLs de imagens externas (''hotlinked'')
# Aqueles que corresponderem serão exibidos como imagens, caso contrário, apenas uma ligação para a imagem será mostrada
# As linhas que começam com # são tratadas como comentários
# Isto não é sensível à capitalização

# Coloque todos os fragmentos de ''regex'' acima dessa linha. Deixe esta linha exatamente como ela é</pre>",

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
'dberr-problems'    => 'Desculpe! Este sítio está passando por dificuldades técnicas.',
'dberr-again'       => 'Experimente esperar alguns minutos e atualizar.',
'dberr-info'        => '(Não foi possível contactar o servidor de base de dados: $1)',
'dberr-usegoogle'   => 'Você pode tentar pesquisar no Google entretanto.',
'dberr-outofdate'   => 'Note que os seus índices relativos ao nosso conteúdo podem estar desatualizados.',
'dberr-cachederror' => 'A seguinte página é uma cópia em cache da página pedida e pode não ser atual.',

# HTML forms
'htmlform-invalid-input'       => 'Existem problemas com alguns dos dados introduzidos',
'htmlform-select-badoption'    => 'O valor que você especificou não é uma opção válida.',
'htmlform-int-invalid'         => 'O valor que você especificou não é um inteiro.',
'htmlform-float-invalid'       => 'O valor que você especificou não é um número.',
'htmlform-int-toolow'          => 'O valor que você especificou está abaixo do mínimo de $1',
'htmlform-int-toohigh'         => 'O valor que você especificou está acima do máximo de $1',
'htmlform-submit'              => 'Enviar',
'htmlform-reset'               => 'Desfazer alterações',
'htmlform-selectorother-other' => 'Outros',

);
