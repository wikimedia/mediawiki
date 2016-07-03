<?php
/** Brazilian Portuguese (português do Brasil)
 *
 * To improve a translation please visit https://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Alcali
 * @author Alchimista
 * @author Amgauna
 * @author Anaclaudiaml
 * @author Bani
 * @author Brion
 * @author BrunaaAa
 * @author Brunoy Anastasiya Seryozhenko
 * @author Cainamarques
 * @author Capmo
 * @author Carla404
 * @author Chicocvenancio
 * @author Crazymadlover
 * @author Daemorris
 * @author Danielsouzat
 * @author Dianakc
 * @author Dicionarista
 * @author Diego Queiroz
 * @author Eduardo.mps
 * @author Elival
 * @author Emufarmers
 * @author Everton137
 * @author Francisco Leandro
 * @author Fúlvio
 * @author GKnedo
 * @author Giro720
 * @author GoEThe
 * @author Gusta
 * @author Hamilton Abreu
 * @author Helder.wiki
 * @author Jaideraf
 * @author Jesielt
 * @author Jgpacker
 * @author Jorge Morais
 * @author Kaganer
 * @author Leonardo.stabile
 * @author LeonardoG
 * @author Lijealso
 * @author Luckas
 * @author Luckas Blade
 * @author Malafaya
 * @author ManoDbo
 * @author Matheus Sousa L.T
 * @author Matma Rex
 * @author McDutchie
 * @author MetalBrasil
 * @author MisterSanderson
 * @author Mordecaista
 * @author Nemo bis
 * @author OTAVIO1981
 * @author Opraco
 * @author Pedroca cerebral
 * @author Ppena
 * @author Rafael Vargas
 * @author Raylton P. Sousa
 * @author Rodrigo Calanca Nishino
 * @author Rodrigo Padula
 * @author Rodrigo codignoli
 * @author Sir Lestaty de Lioncourt
 * @author Teles
 * @author TheGabrielZaum
 * @author Titoncio
 * @author Urhixidur
 * @author Vivaelcelta
 * @author Vuln
 * @author Waldir
 * @author Yves Marques Junqueira
 * @author לערי ריינהארט
 * @author 555
 */

$fallback = 'pt';

$namespaceNames = [
	NS_MEDIA            => 'Mídia',
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
];

$namespaceAliases = [
	'Imagem' => NS_FILE,
	'Imagem_Discussão' => NS_FILE_TALK,
	'Ficheiro' => NS_FILE,
	'Ficheiro_Discussão' => NS_FILE_TALK,
];

$namespaceGenderAliases = [
	NS_USER => [ 'male' => 'Usuário', 'female' => 'Usuária' ],
	NS_USER_TALK => [ 'male' => 'Usuário_Discussão', 'female' => 'Usuária_Discussão' ],
];

$defaultDateFormat = 'dmy';

$dateFormats = [

	'dmy time' => 'H"h"i"min"',
	'dmy date' => 'j "de" F "de" Y',
	'dmy both' => 'H"h"i"min" "de" j "de" F "de" Y',

];

$separatorTransformTable = [ ',' => "\xc2\xa0", '.' => ',' ];

$specialPageAliases = [
	'Activeusers'               => [ 'Usuários_ativos' ],
	'Allmessages'               => [ 'Todas_as_mensagens', 'Todas_mensagens' ],
	'Allpages'                  => [ 'Todas_as_páginas', 'Todos_os_artigos', 'Todas_páginas', 'Todos_artigos' ],
	'Ancientpages'              => [ 'Páginas_inativas', 'Artigos_inativos' ],
	'Badtitle'                  => [ 'Título_inválido' ],
	'Blankpage'                 => [ 'Página_em_branco' ],
	'Block'                     => [ 'Bloquear', 'Bloquear_IP', 'Bloquear_utilizador', 'Bloquear_usuário' ],
	'Booksources'               => [ 'Fontes_de_livros' ],
	'BrokenRedirects'           => [ 'Redirecionamentos_quebrados' ],
	'Categories'                => [ 'Categorias' ],
	'ChangePassword'            => [ 'Trocar_senha', 'Repor_senha' ],
	'ComparePages'              => [ 'Comparar_páginas' ],
	'Confirmemail'              => [ 'Confirmar_e-mail', 'Confirmar_email' ],
	'Contributions'             => [ 'Contribuições' ],
	'CreateAccount'             => [ 'Criar_conta' ],
	'Deadendpages'              => [ 'Páginas_sem_saída', 'Artigos_sem_saída' ],
	'DeletedContributions'      => [ 'Contribuições_eliminadas', 'Edições_eliminadas' ],
	'Diff'                      => [ 'Mudanças_entre_edições', 'Diferenças_entre_edições' ],
	'DoubleRedirects'           => [ 'Redirecionamentos_duplos' ],
	'EditWatchlist'             => [ 'Editar_lista_de_páginas_vigiadas' ],
	'Emailuser'                 => [ 'Contatar_usuário', 'Contactar_usuário', 'Contactar_utilizador' ],
	'Export'                    => [ 'Exportar' ],
	'Fewestrevisions'           => [ 'Páginas_com_menos_edições', 'Artigos_com_menos_edições', 'Artigos_menos_editados' ],
	'FileDuplicateSearch'       => [ 'Busca_de_arquivos_duplicados', 'Busca_de_ficheiros_duplicados' ],
	'Filepath'                  => [ 'Diretório_de_arquivo', 'Diretório_de_ficheiro' ],
	'Import'                    => [ 'Importar' ],
	'Invalidateemail'           => [ 'Invalidar_e-mail' ],
	'BlockList'                 => [ 'Registro_de_bloqueios', 'IPs_bloqueados', 'Utilizadores_bloqueados', 'Usuários_bloqueados', 'Registo_de_bloqueios' ],
	'LinkSearch'                => [ 'Pesquisar_links' ],
	'Listadmins'                => [ 'Lista_de_administradores', 'Administradores', 'Admins', 'Lista_de_admins' ],
	'Listbots'                  => [ 'Lista_de_robôs', 'Bots', 'Lista_de_bots' ],
	'Listfiles'                 => [ 'Lista_de_arquivos', 'Lista_de_imagens', 'Lista_de_ficheiros' ],
	'Listgrouprights'           => [ 'Listar_privilégios_de_grupos' ],
	'Listredirects'             => [ 'Lista_de_redirecionamentos', 'Redirecionamentos' ],
	'Listusers'                 => [ 'Lista_de_usuários', 'Lista_de_utilizadores' ],
	'Lockdb'                    => [ 'Bloquear_banco_de_dados', 'Bloquear_a_base_de_dados' ],
	'Log'                       => [ 'Registro', 'Registos', 'Registros', 'Registo' ],
	'Lonelypages'               => [ 'Páginas_órfãs', 'Artigos_órfãos', 'Páginas_sem_afluentes', 'Artigos_sem_afluentes' ],
	'Longpages'                 => [ 'Páginas_longas', 'Artigos_extensos' ],
	'MergeHistory'              => [ 'Fundir_históricos', 'Fundir_edições' ],
	'MIMEsearch'                => [ 'Busca_MIME' ],
	'Mostcategories'            => [ 'Páginas_com_mais_categorias', 'Artigos_com_mais_categorias' ],
	'Mostimages'                => [ 'Imagens_com_mais_afluentes', 'Ficheiros_com_mais_afluentes', 'Arquivos_com_mais_afluentes' ],
	'Mostlinked'                => [ 'Páginas_com_mais_afluentes', 'Artigos_com_mais_afluentes' ],
	'Mostlinkedcategories'      => [ 'Categorias_com_mais_afluentes' ],
	'Mostlinkedtemplates'       => [ 'Predefinições_com_mais_afluentes' ],
	'Mostrevisions'             => [ 'Páginas_com_mais_edições', 'Artigos_com_mais_edições' ],
	'Movepage'                  => [ 'Mover_página', 'Mover', 'Mover_artigo' ],
	'Mycontributions'           => [ 'Minhas_contribuições', 'Minhas_edições' ],
	'Mypage'                    => [ 'Minha_página' ],
	'Mytalk'                    => [ 'Minha_discussão' ],
	'Newimages'                 => [ 'Arquivos_novos', 'Imagens_novas', 'Ficheiros_novos' ],
	'Newpages'                  => [ 'Páginas_novas', 'Artigos_novos' ],
	'PermanentLink'             => [ 'Ligação_permanente', 'Link_permanente' ],
	'Preferences'               => [ 'Preferências' ],
	'Prefixindex'               => [ 'Índice_de_prefixo', 'Índice_por_prefixo' ],
	'Protectedpages'            => [ 'Páginas_protegidas', 'Artigos_protegidos' ],
	'Protectedtitles'           => [ 'Títulos_protegidos' ],
	'Randompage'                => [ 'Aleatória', 'Aleatório', 'Página_aleatória', 'Artigo_aleatório' ],
	'Randomredirect'            => [ 'Redirecionamento_aleatório' ],
	'Recentchanges'             => [ 'Mudanças_recentes', 'Recentes' ],
	'Recentchangeslinked'       => [ 'Mudanças_relacionadas', 'Novidades_relacionadas' ],
	'Revisiondelete'            => [ 'Eliminar_edição', 'Eliminar_revisão', 'Apagar_edição', 'Apagar_revisão' ],
	'Search'                    => [ 'Busca', 'Buscar', 'Procurar', 'Pesquisar', 'Pesquisa' ],
	'Shortpages'                => [ 'Páginas_curtas', 'Artigos_curtos' ],
	'Specialpages'              => [ 'Páginas_especiais' ],
	'Statistics'                => [ 'Estatísticas' ],
	'Tags'                      => [ 'Etiquetas' ],
	'Unblock'                   => [ 'Desbloquear' ],
	'Uncategorizedcategories'   => [ 'Categorias_sem_categorias' ],
	'Uncategorizedimages'       => [ 'Arquivos_sem_categorias', 'Imagens_sem_categorias', 'Ficheiros_sem_categorias' ],
	'Uncategorizedpages'        => [ 'Páginas_sem_categorias', 'Artigos_sem_categorias' ],
	'Uncategorizedtemplates'    => [ 'Predefinições_não_categorizadas', 'Predefinições_sem_categorias' ],
	'Undelete'                  => [ 'Restaurar', 'Restaurar_páginas_eliminadas', 'Restaurar_artigos_eliminados' ],
	'Unlockdb'                  => [ 'Desbloquear_banco_de_dados', 'Desbloquear_a_base_de_dados' ],
	'Unusedcategories'          => [ 'Categorias_não_utilizadas', 'Categorias_sem_uso' ],
	'Unusedimages'              => [ 'Arquivos_sem_uso', 'Arquivos_não_utilizados', 'Imagens_sem_uso', 'Imagens_não_utilizadas', 'Ficheiros_sem_uso', 'Ficheiros_não_utilizados' ],
	'Unusedtemplates'           => [ 'Predefinições_sem_uso', 'Predefinições_não_utilizadas' ],
	'Unwatchedpages'            => [ 'Páginas_não-vigiadas', 'Páginas_não_vigiadas', 'Artigos_não-vigiados', 'Artigos_não_vigiados' ],
	'Upload'                    => [ 'Carregar_arquivo', 'Carregar_imagem', 'Carregar_ficheiro', 'Enviar' ],
	'Userlogin'                 => [ 'Autenticar-se', 'Entrar' ],
	'Userlogout'                => [ 'Sair' ],
	'Userrights'                => [ 'Privilégios', 'Direitos', 'Estatutos' ],
	'Version'                   => [ 'Versão', 'Sobre' ],
	'Wantedcategories'          => [ 'Categorias_pedidas', 'Categorias_em_falta', 'Categorias_inexistentes' ],
	'Wantedfiles'               => [ 'Arquivos_pedidos', 'Arquivos_em_falta', 'Ficheiros_em_falta', 'Imagens_em_falta' ],
	'Wantedpages'               => [ 'Páginas_pedidas', 'Páginas_em_falta', 'Artigos_em_falta', 'Artigos_pedidos' ],
	'Wantedtemplates'           => [ 'Predefinições_pedidas', 'Predefinições_em_falta' ],
	'Watchlist'                 => [ 'Páginas_vigiadas', 'Artigos_vigiados', 'Vigiados' ],
	'Whatlinkshere'             => [ 'Páginas_afluentes', 'Artigos_afluentes' ],
	'Withoutinterwiki'          => [ 'Páginas_sem_interwikis', 'Artigos_sem_interwikis' ],
];

$magicWords = [
	'redirect'                  => [ '0', '#REDIRECIONAMENTO', '#REDIRECT' ],
	'notoc'                     => [ '0', '__SEMTDC__', '__SEMSUMÁRIO__', '__NOTOC__' ],
	'nogallery'                 => [ '0', '__SEMGALERIA__', '__NOGALLERY__' ],
	'forcetoc'                  => [ '0', '__FORCARTDC__', '__FORCARSUMARIO__', '__FORÇARTDC__', '__FORÇARSUMÁRIO__', '__FORCETOC__' ],
	'toc'                       => [ '0', '__TDC__', '__SUMARIO__', '__SUMÁRIO__', '__TOC__' ],
	'noeditsection'             => [ '0', '__NAOEDITARSECAO__', '__NÃOEDITARSEÇÃO__', '__SEMEDITARSEÇÃO__', '__SEMEDITARSECAO__', '__NOEDITSECTION__' ],
	'currentmonth'              => [ '1', 'MESATUAL', 'MESATUAL2', 'CURRENTMONTH', 'CURRENTMONTH2' ],
	'currentmonth1'             => [ '1', 'MESATUAL1', 'CURRENTMONTH1' ],
	'currentmonthname'          => [ '1', 'NOMEDOMESATUAL', 'CURRENTMONTHNAME' ],
	'currentmonthabbrev'        => [ '1', 'MESATUALABREV', 'MESATUALABREVIADO', 'ABREVIATURADOMESATUAL', 'CURRENTMONTHABBREV' ],
	'currentday'                => [ '1', 'DIAATUAL', 'CURRENTDAY' ],
	'currentday2'               => [ '1', 'DIAATUAL2', 'CURRENTDAY2' ],
	'currentdayname'            => [ '1', 'NOMEDODIAATUAL', 'CURRENTDAYNAME' ],
	'currentyear'               => [ '1', 'ANOATUAL', 'CURRENTYEAR' ],
	'currenttime'               => [ '1', 'HORARIOATUAL', 'CURRENTTIME' ],
	'currenthour'               => [ '1', 'HORAATUAL', 'CURRENTHOUR' ],
	'localmonth'                => [ '1', 'MESLOCAL', 'LOCALMONTH', 'LOCALMONTH2' ],
	'localmonth1'               => [ '1', 'MESLOCAL1', 'LOCALMONTH1' ],
	'localmonthname'            => [ '1', 'NOMEDOMESLOCAL', 'LOCALMONTHNAME' ],
	'localmonthabbrev'          => [ '1', 'MESLOCALABREV', 'MESLOCALABREVIADO', 'ABREVIATURADOMESLOCAL', 'LOCALMONTHABBREV' ],
	'localday'                  => [ '1', 'DIALOCAL', 'LOCALDAY' ],
	'localday2'                 => [ '1', 'DIALOCAL2', 'LOCALDAY2' ],
	'localdayname'              => [ '1', 'NOMEDODIALOCAL', 'LOCALDAYNAME' ],
	'localyear'                 => [ '1', 'ANOLOCAL', 'LOCALYEAR' ],
	'localtime'                 => [ '1', 'HORARIOLOCAL', 'LOCALTIME' ],
	'localhour'                 => [ '1', 'HORALOCAL', 'LOCALHOUR' ],
	'numberofpages'             => [ '1', 'NUMERODEPAGINAS', 'NÚMERODEPÁGINAS', 'NUMBEROFPAGES' ],
	'numberofarticles'          => [ '1', 'NUMERODEARTIGOS', 'NÚMERODEARTIGOS', 'NUMBEROFARTICLES' ],
	'numberoffiles'             => [ '1', 'NUMERODEARQUIVOS', 'NÚMERODEARQUIVOS', 'NUMBEROFFILES' ],
	'numberofusers'             => [ '1', 'NUMERODEUSUARIOS', 'NÚMERODEUSUÁRIOS', 'NUMBEROFUSERS' ],
	'numberofactiveusers'       => [ '1', 'NUMERODEUSUARIOSATIVOS', 'NÚMERODEUSUÁRIOSATIVOS', 'NUMBEROFACTIVEUSERS' ],
	'numberofedits'             => [ '1', 'NUMERODEEDICOES', 'NÚMERODEEDIÇÕES', 'NUMBEROFEDITS' ],
	'pagename'                  => [ '1', 'NOMEDAPAGINA', 'NOMEDAPÁGINA', 'PAGENAME' ],
	'pagenamee'                 => [ '1', 'NOMEDAPAGINAC', 'NOMEDAPÁGINAC', 'PAGENAMEE' ],
	'namespace'                 => [ '1', 'DOMINIO', 'DOMÍNIO', 'ESPACONOMINAL', 'ESPAÇONOMINAL', 'NAMESPACE' ],
	'namespacee'                => [ '1', 'DOMINIOC', 'DOMÍNIOC', 'ESPACONOMINALC', 'ESPAÇONOMINALC', 'NAMESPACEE' ],
	'talkspace'                 => [ '1', 'PAGINADEDISCUSSAO', 'PÁGINADEDISCUSSÃO', 'TALKSPACE' ],
	'talkspacee'                => [ '1', 'PAGINADEDISCUSSAOC', 'PÁGINADEDISCUSSÃOC', 'TALKSPACEE' ],
	'subjectspace'              => [ '1', 'PAGINADECONTEUDO', 'PAGINADECONTEÚDO', 'SUBJECTSPACE', 'ARTICLESPACE' ],
	'subjectspacee'             => [ '1', 'PAGINADECONTEUDOC', 'PAGINADECONTEÚDOC', 'SUBJECTSPACEE', 'ARTICLESPACEE' ],
	'fullpagename'              => [ '1', 'NOMECOMPLETODAPAGINA', 'NOMECOMPLETODAPÁGINA', 'FULLPAGENAME' ],
	'fullpagenamee'             => [ '1', 'NOMECOMPLETODAPAGINAC', 'NOMECOMPLETODAPÁGINAC', 'FULLPAGENAMEE' ],
	'subpagename'               => [ '1', 'NOMEDASUBPAGINA', 'NOMEDASUBPÁGINA', 'SUBPAGENAME' ],
	'subpagenamee'              => [ '1', 'NOMEDASUBPAGINAC', 'NOMEDASUBPÁGINAC', 'SUBPAGENAMEE' ],
	'basepagename'              => [ '1', 'NOMEDAPAGINABASE', 'NOMEDAPÁGINABASE', 'BASEPAGENAME' ],
	'basepagenamee'             => [ '1', 'NOMEDAPAGINABASEC', 'NOMEDAPÁGINABASEC', 'BASEPAGENAMEE' ],
	'talkpagename'              => [ '1', 'NOMEDAPAGINADEDISCUSSAO', 'NOMEDAPÁGINADEDISCUSSÃO', 'TALKPAGENAME' ],
	'talkpagenamee'             => [ '1', 'NOMEDAPAGINADEDISCUSSAOC', 'NOMEDAPÁGINADEDISCUSSÃOC', 'TALKPAGENAMEE' ],
	'subjectpagename'           => [ '1', 'NOMEDAPAGINADECONTEUDO', 'NOMEDAPÁGINADECONTEÚDO', 'SUBJECTPAGENAME', 'ARTICLEPAGENAME' ],
	'subjectpagenamee'          => [ '1', 'NOMEDAPAGINADECONTEUDOC', 'NOMEDAPÁGINADECONTEÚDOC', 'SUBJECTPAGENAMEE', 'ARTICLEPAGENAMEE' ],
	'img_thumbnail'             => [ '1', 'miniaturadaimagem', 'miniatura', 'thumbnail', 'thumb' ],
	'img_manualthumb'           => [ '1', 'miniaturadaimagem=$1', 'miniatura=$1', 'thumbnail=$1', 'thumb=$1' ],
	'img_right'                 => [ '1', 'direita', 'right' ],
	'img_left'                  => [ '1', 'esquerda', 'left' ],
	'img_none'                  => [ '1', 'nenhum', 'none' ],
	'img_center'                => [ '1', 'centro', 'center', 'centre' ],
	'img_framed'                => [ '1', 'commoldura', 'comborda', 'framed', 'enframed', 'frame' ],
	'img_frameless'             => [ '1', 'semmoldura', 'semborda', 'frameless' ],
	'img_page'                  => [ '1', 'página=$1', 'página_$1', 'página $1', 'page=$1', 'page $1' ],
	'img_upright'               => [ '1', 'superiordireito', 'superiordireito=$1', 'superiordireito_$1', 'superiordireito $1', 'upright', 'upright=$1', 'upright $1' ],
	'img_border'                => [ '1', 'borda', 'border' ],
	'img_baseline'              => [ '1', 'linhadebase', 'baseline' ],
	'img_top'                   => [ '1', 'acima', 'top' ],
	'img_middle'                => [ '1', 'meio', 'middle' ],
	'img_bottom'                => [ '1', 'abaixo', 'bottom' ],
	'img_link'                  => [ '1', 'ligação=$1', 'link=$1' ],
	'sitename'                  => [ '1', 'NOMEDOSITE', 'NOMEDOSÍTIO', 'NOMEDOSITIO', 'SITENAME' ],
	'server'                    => [ '0', 'SERVIDOR', 'SERVER' ],
	'servername'                => [ '0', 'NOMEDOSERVIDOR', 'SERVERNAME' ],
	'scriptpath'                => [ '0', 'CAMINHODOSCRIPT', 'SCRIPTPATH' ],
	'gender'                    => [ '0', 'GENERO', 'GÊNERO', 'GENDER:' ],
	'notitleconvert'            => [ '0', '__SEMCONVERTERTITULO__', '__SEMCONVERTERTÍTULO__', '__SEMCT__', '__NOTITLECONVERT__', '__NOTC__' ],
	'nocontentconvert'          => [ '0', '__SEMCONVERTERCONTEUDO__', '__SEMCONVERTERCONTEÚDO__', '__SEMCC__', '__NOCONTENTCONVERT__', '__NOCC__' ],
	'currentweek'               => [ '1', 'SEMANAATUAL', 'CURRENTWEEK' ],
	'currentdow'                => [ '1', 'DIADASEMANAATUAL', 'CURRENTDOW' ],
	'localweek'                 => [ '1', 'SEMANALOCAL', 'LOCALWEEK' ],
	'localdow'                  => [ '1', 'DIADASEMANALOCAL', 'LOCALDOW' ],
	'revisionid'                => [ '1', 'IDDAREVISAO', 'IDDAREVISÃO', 'REVISIONID' ],
	'revisionday'               => [ '1', 'DIADAREVISAO', 'DIADAREVISÃO', 'REVISIONDAY' ],
	'revisionday2'              => [ '1', 'DIADAREVISAO2', 'DIADAREVISÃO2', 'REVISIONDAY2' ],
	'revisionmonth'             => [ '1', 'MESDAREVISAO', 'MÊSDAREVISÃO', 'REVISIONMONTH' ],
	'revisionyear'              => [ '1', 'ANODAREVISAO', 'ANODAREVISÃO', 'REVISIONYEAR' ],
	'revisionuser'              => [ '1', 'USUARIODAREVISAO', 'USUÁRIODAREVISÃO', 'REVISIONUSER' ],
	'fullurl'                   => [ '0', 'URLCOMPLETO:', 'FULLURL:' ],
	'fullurle'                  => [ '0', 'URLCOMPLETOC:', 'FULLURLE:' ],
	'lcfirst'                   => [ '0', 'PRIMEIRAMINUSCULA:', 'PRIMEIRAMINÚSCULA:', 'LCFIRST:' ],
	'ucfirst'                   => [ '0', 'PRIMEIRAMAIUSCULA:', 'PRIMEIRAMAIÚSCULA:', 'UCFIRST:' ],
	'lc'                        => [ '0', 'MINUSCULA', 'MINÚSCULA', 'MINUSCULAS', 'MINÚSCULAS', 'LC:' ],
	'uc'                        => [ '0', 'MAIUSCULA', 'MAIÚSCULA', 'MAIUSCULAS', 'MAIÚSCULAS', 'UC:' ],
	'displaytitle'              => [ '1', 'EXIBETITULO', 'EXIBETÍTULO', 'DISPLAYTITLE' ],
	'newsectionlink'            => [ '1', '__LINKDENOVASECAO__', '__LINKDENOVASEÇÃO__', '__LIGACAODENOVASECAO__', '__LIGAÇÃODENOVASEÇÃO__', '__NEWSECTIONLINK__' ],
	'nonewsectionlink'          => [ '1', '__SEMLINKDENOVASECAO__', '__SEMLINKDENOVASEÇÃO__', '__SEMLIGACAODENOVASECAO__', '__SEMLIGAÇÃODENOVASEÇÃO__', '__NONEWSECTIONLINK__' ],
	'currentversion'            => [ '1', 'REVISAOATUAL', 'REVISÃOATUAL', 'CURRENTVERSION' ],
	'urlencode'                 => [ '0', 'CODIFICAURL:', 'URLENCODE:' ],
	'anchorencode'              => [ '0', 'CODIFICAANCORA:', 'CODIFICAÂNCORA:', 'ANCHORENCODE' ],
	'language'                  => [ '0', '#IDIOMA:', '#LANGUAGE:' ],
	'contentlanguage'           => [ '1', 'IDIOMADOCONTEUDO', 'IDIOMADOCONTEÚDO', 'CONTENTLANGUAGE', 'CONTENTLANG' ],
	'pagesinnamespace'          => [ '1', 'PAGINASNOESPACONOMINAL', 'PÁGINASNOESPAÇONOMINAL', 'PAGINASNODOMINIO', 'PÁGINASNODOMÍNIO', 'PAGESINNAMESPACE:', 'PAGESINNS:' ],
	'numberofadmins'            => [ '1', 'NUMERODEADMINISTRADORES', 'NÚMERODEADMINISTRADORES', 'NUMBEROFADMINS' ],
	'defaultsort'               => [ '1', 'ORDENACAOPADRAO', 'ORDENAÇÃOPADRÃO', 'ORDEMPADRAO', 'ORDEMPADRÃO', 'DEFAULTSORT:', 'DEFAULTSORTKEY:', 'DEFAULTCATEGORYSORT:' ],
	'filepath'                  => [ '0', 'CAMINHODOARQUIVO', 'FILEPATH:' ],
	'hiddencat'                 => [ '1', '__CATEGORIAOCULTA__', '__CATOCULTA__', '__HIDDENCAT__' ],
	'pagesincategory'           => [ '1', 'PAGINASNACATEGORIA', 'PÁGINASNACATEGORIA', 'PAGINASNACAT', 'PÁGINASNACAT', 'PAGESINCATEGORY', 'PAGESINCAT' ],
	'pagesize'                  => [ '1', 'TAMANHODAPAGINA', 'TAMANHODAPÁGINA', 'PAGESIZE' ],
	'index'                     => [ '1', '__INDEXAR__', '__INDEX__' ],
	'noindex'                   => [ '1', '__NAOINDEXAR__', '__NÃOINDEXAR__', '__NOINDEX__' ],
	'numberingroup'             => [ '1', 'NUMERONOGRUPO', 'NÚMERONOGRUPO', 'NUMBERINGROUP', 'NUMINGROUP' ],
	'staticredirect'            => [ '1', '__REDIRECIONAMENTOESTATICO__', '__REDIRECIONAMENTOESTÁTICO__', '__STATICREDIRECT__' ],
	'protectionlevel'           => [ '1', 'NIVELDEPROTECAO', 'NÍVELDEPROTEÇÃO', 'PROTECTIONLEVEL' ],
	'url_path'                  => [ '0', 'CAMINHO', 'PATH' ],
];

