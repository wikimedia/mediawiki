<?php
/** Portuguese (português)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Alchimista
 * @author Andresilvazito
 * @author Cainamarques
 * @author Capmo
 * @author Crazymadlover
 * @author Daemorris
 * @author DanielTom
 * @author Dannyps
 * @author Dicionarista
 * @author Francisco Leandro
 * @author Fúlvio
 * @author Giro720
 * @author GoEThe
 * @author Hamilton Abreu
 * @author Helder.wiki
 * @author Indech
 * @author Jens Liebenau
 * @author Jorge Morais
 * @author Kaganer
 * @author Leonardo.stabile
 * @author Lijealso
 * @author Luckas
 * @author Luckas Blade
 * @author Lugusto
 * @author MCruz
 * @author MF-Warburg
 * @author Malafaya
 * @author Manuel Menezes de Sequeira
 * @author Masked Rogue
 * @author McDutchie
 * @author MetalBrasil
 * @author Minh Nguyen
 * @author Nemo bis
 * @author Nuno Tavares
 * @author OTAVIO1981
 * @author Opraco
 * @author Paulo Juntas
 * @author Pedroca cerebral
 * @author Rafael Vargas
 * @author Rei-artur
 * @author Remember the dot
 * @author RmSilva
 * @author Rodrigo Calanca Nishino
 * @author SandroHc
 * @author Sarilho1
 * @author Sir Lestaty de Lioncourt
 * @author Sérgio Ribeiro
 * @author Teles
 * @author Urhixidur
 * @author Villate
 * @author Waldir
 * @author Yves Marques Junqueira
 * @author לערי ריינהארט
 * @author 555
 */

$fallback = 'pt-br';

$namespaceNames = array(
	NS_MEDIA            => 'Multimédia',
	NS_SPECIAL          => 'Especial',
	NS_TALK             => 'Discussão',
	NS_USER             => 'Utilizador',
	NS_USER_TALK        => 'Utilizador_Discussão',
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
	'Usuário'           => NS_USER,
	'Usuário_Discussão' => NS_USER_TALK,
	'Imagem'            => NS_FILE,
	'Imagem_Discussão'  => NS_FILE_TALK,
	'Arquivo'           => NS_FILE,
	'Arquivo_Discussão' => NS_FILE_TALK,
);

$namespaceGenderAliases = array(
	NS_USER => array( 'male' => 'Utilizador', 'female' => 'Utilizadora' ),
	NS_USER_TALK => array( 'male' => 'Utilizador_Discussão', 'female' => 'Utilizadora_Discussão' ),
);

$defaultDateFormat = 'dmy';

$dateFormats = array(
	'dmy time' => 'H\hi\m\i\n',
	'dmy date' => 'j \d\e F \d\e Y',
	'dmy both' => 'H\hi\m\i\n \d\e j \d\e F \d\e Y',
);

$separatorTransformTable = array( ',' => "\xc2\xa0", '.' => ',' );
$linkTrail = '/^([áâãàéêẽçíòóôõq̃úüűũa-z]+)(.*)$/sDu'; # Bug 21168, 27633

$specialPageAliases = array(
	'Activeusers'               => array( 'Utilizadores_activos' ),
	'Allmessages'               => array( 'Todas_as_mensagens', 'Todas_mensagens' ),
	'Allpages'                  => array( 'Todas_as_páginas', 'Todos_os_artigos', 'Todas_páginas', 'Todos_artigos' ),
	'Ancientpages'              => array( 'Páginas_inactivas', 'Páginas_inativas', 'Artigos_inativos' ),
	'Badtitle'                  => array( 'Título_inválido' ),
	'Blankpage'                 => array( 'Página_em_branco' ),
	'Block'                     => array( 'Bloquear', 'Bloquear_IP', 'Bloquear_utilizador', 'Bloquear_usuário' ),
	'Blockme'                   => array( 'Bloquear-me', 'Auto-bloqueio' ),
	'Booksources'               => array( 'Fontes_de_livros' ),
	'BrokenRedirects'           => array( 'Redireccionamentos_quebrados', 'Redirecionamentos_quebrados' ),
	'Categories'                => array( 'Categorias' ),
	'ChangePassword'            => array( 'Reiniciar_palavra-chave', 'Repor_senha', 'Zerar_senha' ),
	'ComparePages'              => array( 'Comparar_páginas' ),
	'Confirmemail'              => array( 'Confirmar_correio_electrónico', 'Confirmar_e-mail', 'Confirmar_email' ),
	'Contributions'             => array( 'Contribuições' ),
	'CreateAccount'             => array( 'Criar_conta' ),
	'Deadendpages'              => array( 'Páginas_sem_saída', 'Artigos_sem_saída' ),
	'DeletedContributions'      => array( 'Contribuições_eliminadas', 'Edições_eliminadas' ),
	'Disambiguations'           => array( 'Desambiguações', 'Páginas_de_desambiguação', 'Desambiguar' ),
	'DoubleRedirects'           => array( 'Redireccionamentos_duplos', 'Redirecionamentos_duplos' ),
	'EditWatchlist'             => array( 'Editar_lista_de_páginas_vigiadas' ),
	'Emailuser'                 => array( 'Contactar_utilizador', 'Contactar_usuário', 'Contatar_usuário' ),
	'Export'                    => array( 'Exportar' ),
	'Fewestrevisions'           => array( 'Páginas_com_menos_edições', 'Artigos_com_menos_edições', 'Artigos_menos_editados' ),
	'FileDuplicateSearch'       => array( 'Busca_de_ficheiros_duplicados', 'Busca_de_arquivos_duplicados' ),
	'Filepath'                  => array( 'Directório_de_ficheiro', 'Diretório_de_ficheiro', 'Diretório_de_arquivo' ),
	'Import'                    => array( 'Importar' ),
	'Invalidateemail'           => array( 'Invalidar_correio_electrónico', 'Invalidar_e-mail' ),
	'BlockList'                 => array( 'Registo_de_bloqueios', 'IPs_bloqueados', 'Utilizadores_bloqueados', 'Registro_de_bloqueios', 'Usuários_bloqueados' ),
	'LinkSearch'                => array( 'Pesquisar_links' ),
	'Listadmins'                => array( 'Administradores', 'Admins', 'Lista_de_administradores', 'Lista_de_admins' ),
	'Listbots'                  => array( 'Robôs', 'Lista_de_robôs', 'Bots', 'Lista_de_bots' ),
	'Listfiles'                 => array( 'Lista_de_ficheiros', 'Lista_de_imagens', 'Lista_de_arquivos' ),
	'Listgrouprights'           => array( 'Lista_de_privilégios_de_grupos', 'Listar_privilégios_de_grupos' ),
	'Listredirects'             => array( 'Redireccionamentos', 'Redirecionamentos', 'Lista_de_redireccionamentos', 'Lista_de_redirecionamentos' ),
	'Listusers'                 => array( 'Lista_de_utilizadores', 'Lista_de_usuários' ),
	'Lockdb'                    => array( 'Bloquear_base_de_dados', 'Bloquear_a_base_de_dados', 'Bloquear_banco_de_dados' ),
	'Log'                       => array( 'Registo', 'Registos', 'Registro', 'Registros' ),
	'Lonelypages'               => array( 'Páginas_órfãs', 'Páginas_sem_afluentes', 'Artigos_órfãos', 'Artigos_sem_afluentes' ),
	'Longpages'                 => array( 'Páginas_longas', 'Artigos_extensos' ),
	'MergeHistory'              => array( 'Fundir_históricos', 'Fundir_edições' ),
	'MIMEsearch'                => array( 'Busca_MIME' ),
	'Mostcategories'            => array( 'Páginas_com_mais_categorias', 'Artigos_com_mais_categorias' ),
	'Mostimages'                => array( 'Ficheiros_com_mais_afluentes', 'Imagens_com_mais_afluentes', 'Arquivos_com_mais_afluentes' ),
	'Mostlinked'                => array( 'Páginas_com_mais_afluentes', 'Artigos_com_mais_afluentes' ),
	'Mostlinkedcategories'      => array( 'Categorias_com_mais_afluentes', 'Categorias_mais_usadas' ),
	'Mostlinkedtemplates'       => array( 'Predefinições_com_mais_afluentes', 'Predefinições_mais_usadas' ),
	'Mostrevisions'             => array( 'Páginas_com_mais_edições', 'Artigos_com_mais_edições' ),
	'Movepage'                  => array( 'Mover_página', 'Mover', 'Mover_artigo' ),
	'Mycontributions'           => array( 'Minhas_contribuições', 'Minhas_edições', 'Minhas_constribuições' ),
	'Mypage'                    => array( 'Minha_página' ),
	'Mytalk'                    => array( 'Minha_discussão' ),
	'Newimages'                 => array( 'Ficheiros_novos', 'Imagens_novas', 'Arquivos_novos' ),
	'Newpages'                  => array( 'Páginas_novas', 'Artigos_novos' ),
	'PermanentLink'             => array( 'Ligação_permanente', 'Link_permanente' ),
	'Popularpages'              => array( 'Páginas_populares', 'Artigos_populares' ),
	'Preferences'               => array( 'Preferências' ),
	'Prefixindex'               => array( 'Índice_por_prefixo', 'Índice_de_prefixo' ),
	'Protectedpages'            => array( 'Páginas_protegidas', 'Artigos_protegidos' ),
	'Protectedtitles'           => array( 'Títulos_protegidos' ),
	'Randompage'                => array( 'Aleatória', 'Aleatório', 'Página_aleatória', 'Artigo_aleatório' ),
	'Randomredirect'            => array( 'Redireccionamento_aleatório', 'Redirecionamento_aleatório' ),
	'Recentchanges'             => array( 'Mudanças_recentes' ),
	'Recentchangeslinked'       => array( 'Alterações_relacionadas', 'Novidades_relacionadas', 'Mudanças_relacionadas' ),
	'Revisiondelete'            => array( 'Eliminar_edição', 'Eliminar_revisão', 'Apagar_edição', 'Apagar_revisão' ),
	'Search'                    => array( 'Pesquisar', 'Busca', 'Buscar', 'Procurar', 'Pesquisa' ),
	'Shortpages'                => array( 'Páginas_curtas', 'Artigos_curtos' ),
	'Specialpages'              => array( 'Páginas_especiais' ),
	'Statistics'                => array( 'Estatísticas' ),
	'Tags'                      => array( 'Etiquetas' ),
	'Unblock'                   => array( 'Desbloquear' ),
	'Uncategorizedcategories'   => array( 'Categorias_não_categorizadas', 'Categorias_sem_categorias' ),
	'Uncategorizedimages'       => array( 'Ficheiros_não_categorizados', 'Imagens_não_categorizadas', 'Imagens_sem_categorias', 'Ficheiros_sem_categorias', 'Arquivos_sem_categorias' ),
	'Uncategorizedpages'        => array( 'Páginas_não_categorizadas', 'Páginas_sem_categorias', 'Artigos_sem_categorias' ),
	'Uncategorizedtemplates'    => array( 'Predefinições_não_categorizadas', 'Predefinições_sem_categorias' ),
	'Undelete'                  => array( 'Restaurar', 'Restaurar_páginas_eliminadas', 'Restaurar_artigos_eliminados' ),
	'Unlockdb'                  => array( 'Desbloquear_base_de_dados', 'Desbloquear_a_base_de_dados', 'Desbloquear_banco_de_dados' ),
	'Unusedcategories'          => array( 'Categorias_não_utilizadas', 'Categorias_sem_uso' ),
	'Unusedimages'              => array( 'Ficheiros_não_utilizados', 'Imagens_não_utilizadas' ),
	'Unusedtemplates'           => array( 'Predefinições_não_utilizadas', 'Predefinições_sem_uso' ),
	'Unwatchedpages'            => array( 'Páginas_não_vigiadas', 'Páginas_não-vigiadas', 'Artigos_não-vigiados', 'Artigos_não_vigiados' ),
	'Upload'                    => array( 'Carregar_imagem', 'Carregar_ficheiro', 'Carregar_arquivo', 'Enviar' ),
	'Userlogin'                 => array( 'Entrar', 'Login' ),
	'Userlogout'                => array( 'Sair', 'Logout' ),
	'Userrights'                => array( 'Privilégios', 'Direitos', 'Estatutos' ),
	'Version'                   => array( 'Versão', 'Sobre' ),
	'Wantedcategories'          => array( 'Categorias_pedidas', 'Categorias_em_falta', 'Categorias_inexistentes' ),
	'Wantedfiles'               => array( 'Ficheiros_pedidos', 'Imagens_pedidas', 'Ficheiros_em_falta', 'Arquivos_em_falta', 'Imagens_em_falta' ),
	'Wantedpages'               => array( 'Páginas_pedidas', 'Páginas_em_falta', 'Artigos_em_falta', 'Artigos_pedidos' ),
	'Wantedtemplates'           => array( 'Predefinições_pedidas', 'Predefinições_em_falta' ),
	'Watchlist'                 => array( 'Páginas_vigiadas', 'Artigos_vigiados', 'Vigiados' ),
	'Whatlinkshere'             => array( 'Páginas_afluentes', 'Artigos_afluentes' ),
	'Withoutinterwiki'          => array( 'Páginas_sem_interwikis', 'Artigos_sem_interwikis' ),
);

$magicWords = array(
	'redirect'                  => array( '0', '#REDIRECIONAMENTO', '#REDIRECT' ),
	'notoc'                     => array( '0', '__SEMTDC__', '__SEMSUMÁRIO__', '__NOTOC__' ),
	'nogallery'                 => array( '0', '__SEMGALERIA__', '__NOGALLERY__' ),
	'forcetoc'                  => array( '0', '__FORCARTDC__', '__FORCARSUMARIO__', '__FORÇARTDC__', '__FORÇARSUMÁRIO__', '__FORCETOC__' ),
	'toc'                       => array( '0', '__TDC__', '__SUMÁRIO__', '__SUMARIO__', '__TOC__' ),
	'noeditsection'             => array( '0', '__NÃOEDITARSEÇÃO__', '__SEMEDITARSEÇÃO__', '__NAOEDITARSECAO__', '__SEMEDITARSECAO__', '__NOEDITSECTION__' ),
	'currentmonth'              => array( '1', 'MESATUAL', 'MESATUAL2', 'CURRENTMONTH', 'CURRENTMONTH2' ),
	'currentmonth1'             => array( '1', 'MESATUAL1', 'CURRENTMONTH1' ),
	'currentmonthname'          => array( '1', 'NOMEDOMESATUAL', 'CURRENTMONTHNAME' ),
	'currentmonthabbrev'        => array( '1', 'MESATUALABREV', 'MESATUALABREVIADO', 'ABREVIATURADOMESATUAL', 'CURRENTMONTHABBREV' ),
	'currentday'                => array( '1', 'DIAATUAL', 'CURRENTDAY' ),
	'currentday2'               => array( '1', 'DIAATUAL2', 'CURRENTDAY2' ),
	'currentdayname'            => array( '1', 'NOMEDODIAATUAL', 'CURRENTDAYNAME' ),
	'currentyear'               => array( '1', 'ANOATUAL', 'CURRENTYEAR' ),
	'currenttime'               => array( '1', 'HORARIOATUAL', 'CURRENTTIME' ),
	'currenthour'               => array( '1', 'HORAATUAL', 'CURRENTHOUR' ),
	'localmonth'                => array( '1', 'MESLOCAL', 'LOCALMONTH', 'LOCALMONTH2' ),
	'localmonth1'               => array( '1', 'MESLOCAL1', 'LOCALMONTH1' ),
	'localmonthname'            => array( '1', 'NOMEDOMESLOCAL', 'LOCALMONTHNAME' ),
	'localmonthabbrev'          => array( '1', 'MESLOCALABREV', 'MESLOCALABREVIADO', 'ABREVIATURADOMESLOCAL', 'LOCALMONTHABBREV' ),
	'localday'                  => array( '1', 'DIALOCAL', 'LOCALDAY' ),
	'localday2'                 => array( '1', 'DIALOCAL2', 'LOCALDAY2' ),
	'localdayname'              => array( '1', 'NOMEDODIALOCAL', 'LOCALDAYNAME' ),
	'localyear'                 => array( '1', 'ANOLOCAL', 'LOCALYEAR' ),
	'localtime'                 => array( '1', 'HORARIOLOCAL', 'LOCALTIME' ),
	'localhour'                 => array( '1', 'HORALOCAL', 'LOCALHOUR' ),
	'numberofpages'             => array( '1', 'NUMERODEPAGINAS', 'NÚMERODEPÁGINAS', 'NUMBEROFPAGES' ),
	'numberofarticles'          => array( '1', 'NUMERODEARTIGOS', 'NÚMERODEARTIGOS', 'NUMBEROFARTICLES' ),
	'numberoffiles'             => array( '1', 'NUMERODEARQUIVOS', 'NÚMERODEARQUIVOS', 'NUMBEROFFILES' ),
	'numberofusers'             => array( '1', 'NUMERODEUSUARIOS', 'NÚMERODEUSUÁRIOS', 'NUMBEROFUSERS' ),
	'numberofactiveusers'       => array( '1', 'NUMERODEUSUARIOSATIVOS', 'NÚMERODEUSUÁRIOSATIVOS', 'NUMBEROFACTIVEUSERS' ),
	'numberofedits'             => array( '1', 'NUMERODEEDICOES', 'NÚMERODEEDIÇÕES', 'NUMBEROFEDITS' ),
	'numberofviews'             => array( '1', 'NUMERODEEXIBICOES', 'NÚMERODEEXIBIÇÕES', 'NUMBEROFVIEWS' ),
	'pagename'                  => array( '1', 'NOMEDAPAGINA', 'NOMEDAPÁGINA', 'PAGENAME' ),
	'pagenamee'                 => array( '1', 'NOMEDAPAGINAC', 'NOMEDAPÁGINAC', 'PAGENAMEE' ),
	'namespace'                 => array( '1', 'DOMINIO', 'DOMÍNIO', 'ESPACONOMINAL', 'ESPAÇONOMINAL', 'NAMESPACE' ),
	'namespacee'                => array( '1', 'DOMINIOC', 'DOMÍNIOC', 'ESPACONOMINALC', 'ESPAÇONOMINALC', 'NAMESPACEE' ),
	'talkspace'                 => array( '1', 'PAGINADEDISCUSSAO', 'PÁGINADEDISCUSSÃO', 'TALKSPACE' ),
	'talkspacee'                => array( '1', 'PAGINADEDISCUSSAOC', 'PÁGINADEDISCUSSÃOC', 'TALKSPACEE' ),
	'subjectspace'              => array( '1', 'PAGINADECONTEUDO', 'PAGINADECONTEÚDO', 'SUBJECTSPACE', 'ARTICLESPACE' ),
	'subjectspacee'             => array( '1', 'PAGINADECONTEUDOC', 'PAGINADECONTEÚDOC', 'SUBJECTSPACEE', 'ARTICLESPACEE' ),
	'fullpagename'              => array( '1', 'NOMECOMPLETODAPAGINA', 'NOMECOMPLETODAPÁGINA', 'FULLPAGENAME' ),
	'fullpagenamee'             => array( '1', 'NOMECOMPLETODAPAGINAC', 'NOMECOMPLETODAPÁGINAC', 'FULLPAGENAMEE' ),
	'subpagename'               => array( '1', 'NOMEDASUBPAGINA', 'NOMEDASUBPÁGINA', 'SUBPAGENAME' ),
	'subpagenamee'              => array( '1', 'NOMEDASUBPAGINAC', 'NOMEDASUBPÁGINAC', 'SUBPAGENAMEE' ),
	'basepagename'              => array( '1', 'NOMEDAPAGINABASE', 'NOMEDAPÁGINABASE', 'BASEPAGENAME' ),
	'basepagenamee'             => array( '1', 'NOMEDAPAGINABASEC', 'NOMEDAPÁGINABASEC', 'BASEPAGENAMEE' ),
	'talkpagename'              => array( '1', 'NOMEDAPAGINADEDISCUSSAO', 'NOMEDAPÁGINADEDISCUSSÃO', 'TALKPAGENAME' ),
	'talkpagenamee'             => array( '1', 'NOMEDAPAGINADEDISCUSSAOC', 'NOMEDAPÁGINADEDISCUSSÃOC', 'TALKPAGENAMEE' ),
	'subjectpagename'           => array( '1', 'NOMEDAPAGINADECONTEUDO', 'NOMEDAPÁGINADECONTEÚDO', 'SUBJECTPAGENAME', 'ARTICLEPAGENAME' ),
	'subjectpagenamee'          => array( '1', 'NOMEDAPAGINADECONTEUDOC', 'NOMEDAPÁGINADECONTEÚDOC', 'SUBJECTPAGENAMEE', 'ARTICLEPAGENAMEE' ),
	'img_thumbnail'             => array( '1', 'miniaturadaimagem', 'miniatura', 'thumbnail', 'thumb' ),
	'img_manualthumb'           => array( '1', 'miniaturadaimagem=$1', 'miniatura=$1', 'thumbnail=$1', 'thumb=$1' ),
	'img_right'                 => array( '1', 'direita', 'right' ),
	'img_left'                  => array( '1', 'esquerda', 'left' ),
	'img_none'                  => array( '1', 'nenhum', 'none' ),
	'img_center'                => array( '1', 'centro', 'center', 'centre' ),
	'img_framed'                => array( '1', 'commoldura', 'comborda', 'framed', 'enframed', 'frame' ),
	'img_frameless'             => array( '1', 'semmoldura', 'semborda', 'frameless' ),
	'img_page'                  => array( '1', 'página=$1', 'página $1', 'page=$1', 'page $1' ),
	'img_upright'               => array( '1', 'superiordireito', 'superiordireito=$1', 'superiordireito $1', 'upright', 'upright=$1', 'upright $1' ),
	'img_border'                => array( '1', 'borda', 'border' ),
	'img_baseline'              => array( '1', 'linhadebase', 'baseline' ),
	'img_top'                   => array( '1', 'acima', 'top' ),
	'img_middle'                => array( '1', 'meio', 'middle' ),
	'img_bottom'                => array( '1', 'abaixo', 'bottom' ),
	'img_link'                  => array( '1', 'ligação=$1', 'link=$1' ),
	'sitename'                  => array( '1', 'NOMEDOSITE', 'NOMEDOSÍTIO', 'NOMEDOSITIO', 'SITENAME' ),
	'server'                    => array( '0', 'SERVIDOR', 'SERVER' ),
	'servername'                => array( '0', 'NOMEDOSERVIDOR', 'SERVERNAME' ),
	'scriptpath'                => array( '0', 'CAMINHODOSCRIPT', 'SCRIPTPATH' ),
	'gender'                    => array( '0', 'GENERO', 'GÊNERO', 'GENDER:' ),
	'notitleconvert'            => array( '0', '__SEMCONVERTERTITULO__', '__SEMCONVERTERTÍTULO__', '__SEMCT__', '__NOTITLECONVERT__', '__NOTC__' ),
	'nocontentconvert'          => array( '0', '__SEMCONVERTERCONTEUDO__', '__SEMCONVERTERCONTEÚDO__', '__SEMCC__', '__NOCONTENTCONVERT__', '__NOCC__' ),
	'currentweek'               => array( '1', 'SEMANAATUAL', 'CURRENTWEEK' ),
	'currentdow'                => array( '1', 'DIADASEMANAATUAL', 'CURRENTDOW' ),
	'localweek'                 => array( '1', 'SEMANALOCAL', 'LOCALWEEK' ),
	'localdow'                  => array( '1', 'DIADASEMANALOCAL', 'LOCALDOW' ),
	'revisionid'                => array( '1', 'IDDAREVISAO', 'IDDAREVISÃO', 'REVISIONID' ),
	'revisionday'               => array( '1', 'DIADAREVISAO', 'DIADAREVISÃO', 'REVISIONDAY' ),
	'revisionday2'              => array( '1', 'DIADAREVISAO2', 'DIADAREVISÃO2', 'REVISIONDAY2' ),
	'revisionmonth'             => array( '1', 'MESDAREVISAO', 'MÊSDAREVISÃO', 'REVISIONMONTH' ),
	'revisionyear'              => array( '1', 'ANODAREVISAO', 'ANODAREVISÃO', 'REVISIONYEAR' ),
	'revisionuser'              => array( '1', 'USUARIODAREVISAO', 'USUÁRIODAREVISÃO', 'REVISIONUSER' ),
	'fullurl'                   => array( '0', 'URLCOMPLETO:', 'FULLURL:' ),
	'fullurle'                  => array( '0', 'URLCOMPLETOC:', 'FULLURLE:' ),
	'lcfirst'                   => array( '0', 'PRIMEIRAMINUSCULA:', 'PRIMEIRAMINÚSCULA:', 'LCFIRST:' ),
	'ucfirst'                   => array( '0', 'PRIMEIRAMAIUSCULA:', 'PRIMEIRAMAIÚSCULA:', 'UCFIRST:' ),
	'lc'                        => array( '0', 'MINUSCULA', 'MINÚSCULA', 'MINUSCULAS', 'MINÚSCULAS', 'LC:' ),
	'uc'                        => array( '0', 'MAIUSCULA', 'MAIÚSCULA', 'MAIUSCULAS', 'MAIÚSCULAS', 'UC:' ),
	'displaytitle'              => array( '1', 'EXIBETITULO', 'EXIBETÍTULO', 'DISPLAYTITLE' ),
	'newsectionlink'            => array( '1', '__LINKDENOVASECAO__', '__LINKDENOVASEÇÃO__', '__LIGACAODENOVASECAO__', '__LIGAÇÃODENOVASEÇÃO__', '__NEWSECTIONLINK__' ),
	'nonewsectionlink'          => array( '1', '__SEMLINKDENOVASECAO__', '__SEMLINKDENOVASEÇÃO__', '__SEMLIGACAODENOVASECAO__', '__SEMLIGAÇÃODENOVASEÇÃO__', '__NONEWSECTIONLINK__' ),
	'currentversion'            => array( '1', 'REVISAOATUAL', 'REVISÃOATUAL', 'CURRENTVERSION' ),
	'urlencode'                 => array( '0', 'CODIFICAURL:', 'URLENCODE:' ),
	'anchorencode'              => array( '0', 'CODIFICAANCORA:', 'CODIFICAÂNCORA:', 'ANCHORENCODE' ),
	'language'                  => array( '0', '#IDIOMA:', '#LANGUAGE:' ),
	'contentlanguage'           => array( '1', 'IDIOMADOCONTEUDO', 'IDIOMADOCONTEÚDO', 'CONTENTLANGUAGE', 'CONTENTLANG' ),
	'pagesinnamespace'          => array( '1', 'PAGINASNOESPACONOMINAL', 'PÁGINASNOESPAÇONOMINAL', 'PAGINASNODOMINIO', 'PÁGINASNODOMÍNIO', 'PAGESINNAMESPACE:', 'PAGESINNS:' ),
	'numberofadmins'            => array( '1', 'NUMERODEADMINISTRADORES', 'NÚMERODEADMINISTRADORES', 'NUMBEROFADMINS' ),
	'defaultsort'               => array( '1', 'ORDENACAOPADRAO', 'ORDENAÇÃOPADRÃO', 'ORDEMPADRAO', 'ORDEMPADRÃO', 'DEFAULTSORT:', 'DEFAULTSORTKEY:', 'DEFAULTCATEGORYSORT:' ),
	'filepath'                  => array( '0', 'CAMINHODOARQUIVO', 'FILEPATH:' ),
	'hiddencat'                 => array( '1', '__CATEGORIAOCULTA__', '__CATOCULTA__', '__HIDDENCAT__' ),
	'pagesincategory'           => array( '1', 'PAGINASNACATEGORIA', 'PÁGINASNACATEGORIA', 'PAGINASNACAT', 'PÁGINASNACAT', 'PAGESINCATEGORY', 'PAGESINCAT' ),
	'pagesize'                  => array( '1', 'TAMANHODAPAGINA', 'TAMANHODAPÁGINA', 'PAGESIZE' ),
	'index'                     => array( '1', '__INDEXAR__', '__INDEX__' ),
	'noindex'                   => array( '1', '__NAOINDEXAR__', '__NÃOINDEXAR__', '__NOINDEX__' ),
	'numberingroup'             => array( '1', 'NUMERONOGRUPO', 'NÚMERONOGRUPO', 'NUMBERINGROUP', 'NUMINGROUP' ),
	'staticredirect'            => array( '1', '__REDIRECIONAMENTOESTATICO__', '__REDIRECIONAMENTOESTÁTICO__', '__STATICREDIRECT__' ),
	'protectionlevel'           => array( '1', 'NIVELDEPROTECAO', 'NÍVELDEPROTEÇÃO', 'PROTECTIONLEVEL' ),
);

$messages = array(
# User preference toggles
'tog-underline' => 'Sublinhar ligação:',
'tog-justify' => 'Justificar parágrafos',
'tog-hideminor' => 'Esconder edições menores nas mudanças recentes',
'tog-hidepatrolled' => 'Esconder edições patrulhadas nas mudanças recentes',
'tog-newpageshidepatrolled' => 'Esconder páginas patrulhadas na lista de páginas novas',
'tog-extendwatchlist' => 'Listagem expandida de todas as mudanças às páginas vigiadas, não apenas das mais recentes',
'tog-usenewrc' => 'Agrupar alterações por página nas mudanças recentes e páginas vigiadas',
'tog-numberheadings' => 'Auto-numerar cabeçalhos',
'tog-showtoolbar' => 'Mostrar barra de edição',
'tog-editondblclick' => 'Editar páginas quando houver um clique duplo',
'tog-editsection' => 'Possibilitar a edição de seções com links [editar]',
'tog-editsectiononrightclick' => 'Possibilitar a edição de seções por clique com o botão direito no título da seção',
'tog-showtoc' => 'Mostrar índice (para páginas com mais de três seções)',
'tog-rememberpassword' => 'Recordar os meus dados neste browser (no máximo, durante $1 {{PLURAL:$1|dia|dias}})',
'tog-watchcreations' => 'Adicionar as páginas e ficheiros que eu criar às minhas páginas vigiadas',
'tog-watchdefault' => 'Adicionar as páginas e ficheiros que eu editar às minhas páginas vigiadas',
'tog-watchmoves' => 'Adicionar as páginas e ficheiros que eu mover às minhas páginas vigiadas',
'tog-watchdeletion' => 'Adicionar as páginas e ficheiros que eu eliminar às minhas páginas vigiadas',
'tog-minordefault' => 'Por omissão, marcar todas as edições como menores',
'tog-previewontop' => 'Mostrar a antevisão antes da caixa de edição',
'tog-previewonfirst' => 'Mostrar a antevisão na primeira edição',
'tog-nocache' => 'Desativar a cache de páginas do browser',
'tog-enotifwatchlistpages' => 'Notificar-me por correio electrónico quando uma página ou ficheiro vigiado for alterado',
'tog-enotifusertalkpages' => 'Notificar-me por correio electrónico quando a minha página de discussão é editada',
'tog-enotifminoredits' => 'Notificar-me por correio electrónico também sobre edições menores de páginas ou ficheiros',
'tog-enotifrevealaddr' => 'Revelar o meu endereço de correio electrónico nas notificações',
'tog-shownumberswatching' => 'Mostrar o número de utilizadores a vigiar',
'tog-oldsig' => 'Assinatura existente:',
'tog-fancysig' => 'Tratar assinatura como texto wiki (sem link automático)',
'tog-uselivepreview' => 'Usar a antevisão ao vivo (experimental)',
'tog-forceeditsummary' => 'Avisar-me se deixar o resumo da edição vazio',
'tog-watchlisthideown' => 'Esconder as minhas edições ao listar mudanças às páginas vigiadas',
'tog-watchlisthidebots' => 'Esconder edições de robôs ao listar mudanças às páginas vigiadas',
'tog-watchlisthideminor' => 'Esconder edições menores ao listar mudanças às páginas vigiadas',
'tog-watchlisthideliu' => 'Esconder edições de utilizadores autenticados ao listar mudanças às páginas vigiadas',
'tog-watchlisthideanons' => 'Esconder edições de utilizadores anónimos ao listar mudanças às páginas vigiadas',
'tog-watchlisthidepatrolled' => 'Esconder edições patrulhadas ao listar mudanças às páginas vigiadas',
'tog-ccmeonemails' => 'Enviar-me cópias das mensagens por correio electrónico que eu enviar a outros utilizadores',
'tog-diffonly' => 'Não mostrar o conteúdo da página ao comparar duas edições',
'tog-showhiddencats' => 'Mostrar categorias ocultas',
'tog-noconvertlink' => 'Impossibilitar a conversão dos títulos de links',
'tog-norollbackdiff' => 'Omitir diferenças depois de reverter edições em bloco',
'tog-useeditwarning' => 'Avisar-me ao abandonar uma página editada sem gravar as alterações.',
'tog-prefershttps' => 'Sempre utilizar uma conexão segura ao iniciar sessão',

'underline-always' => 'Sempre',
'underline-never' => 'Nunca',
'underline-default' => 'Usar opção padrão do tema ou do browser',

# Font style option in Special:Preferences
'editfont-style' => 'Fonte de edição:',
'editfont-default' => 'Fonte por omissão, do browser',
'editfont-monospace' => 'Fonte monoespaçada',
'editfont-sansserif' => 'Fonte sans-serif',
'editfont-serif' => 'Fonte serifada',

# Dates
'sunday' => 'domingo',
'monday' => 'segunda-feira',
'tuesday' => 'terça-feira',
'wednesday' => 'quarta-feira',
'thursday' => 'quinta-feira',
'friday' => 'sexta-feira',
'saturday' => 'Sábado',
'sun' => 'Dom',
'mon' => 'Seg',
'tue' => 'Ter',
'wed' => 'Qua',
'thu' => 'Qui',
'fri' => 'Sex',
'sat' => 'Sáb',
'january' => 'janeiro',
'february' => 'fevereiro',
'march' => 'março',
'april' => 'abril',
'may_long' => 'maio',
'june' => 'junho',
'july' => 'julho',
'august' => 'agosto',
'september' => 'setembro',
'october' => 'outubro',
'november' => 'novembro',
'december' => 'dezembro',
'january-gen' => 'Janeiro',
'february-gen' => 'Fevereiro',
'march-gen' => 'Março',
'april-gen' => 'Abril',
'may-gen' => 'Maio',
'june-gen' => 'Junho',
'july-gen' => 'Julho',
'august-gen' => 'Agosto',
'september-gen' => 'Setembro',
'october-gen' => 'Outubro',
'november-gen' => 'Novembro',
'december-gen' => 'Dezembro',
'jan' => 'Jan.',
'feb' => 'Fev.',
'mar' => 'Mar.',
'apr' => 'Abr.',
'may' => 'maio',
'jun' => 'Jun.',
'jul' => 'Jul.',
'aug' => 'Ago.',
'sep' => 'Set.',
'oct' => 'Out.',
'nov' => 'Nov.',
'dec' => 'Dez.',
'january-date' => '$1 de Janeiro',
'february-date' => '$1 de Fevereiro',
'march-date' => '$1 de Março',
'april-date' => '$1 de Abril',
'may-date' => '$1 de Maio',
'june-date' => '$1 de Junho',
'july-date' => '$1 de Julho',
'august-date' => '$1 de Agosto',
'september-date' => '$1 de Setembro',
'october-date' => '$1 de Outubro',
'november-date' => '$1 de Novembro',
'december-date' => '$1 de Dezembro',

# Categories related messages
'pagecategories' => '{{PLURAL:$1|Categoria|Categorias}}',
'category_header' => 'Páginas na categoria "$1"',
'subcategories' => 'Subcategorias',
'category-media-header' => 'Multimédia na categoria "$1"',
'category-empty' => "''Esta categoria não contém atualmente nenhuma página ou ficheiro multimédia.''",
'hidden-categories' => '{{PLURAL:$1|Categoria oculta|Categorias ocultas}}',
'hidden-category-category' => 'Categorias ocultas',
'category-subcat-count' => '{{PLURAL:$2|Esta categoria só contém a seguinte subcategoria.|Esta categoria contém {{PLURAL:$1|a seguinte subcategoria|as seguintes $1 subcategorias}} (de um total de $2).}}',
'category-subcat-count-limited' => 'Esta categoria tem {{PLURAL:$1|a seguinte subcategoria|as seguintes $1 subcategorias}}.',
'category-article-count' => '{{PLURAL:$2|Esta categoria só contém a seguinte página.|Esta categoria contém {{PLURAL:$1|a seguinte página|as seguintes $1 páginas}} (de um total de $2).}}',
'category-article-count-limited' => 'Nesta categoria há {{PLURAL:$1|uma página|$1 páginas}}.',
'category-file-count' => '{{PLURAL:$2|Esta categoria só contém o seguinte ficheiro.|Esta categoria contém {{PLURAL:$1|o seguinte ficheiro|os seguintes $1 ficheiros}} (de um total de $2).}}',
'category-file-count-limited' => 'Nesta categoria há {{PLURAL:$1|um ficheiro|$1 ficheiros}}.',
'listingcontinuesabbrev' => 'cont.',
'index-category' => 'Páginas indexadas',
'noindex-category' => 'Páginas não indexadas',
'broken-file-category' => 'Páginas com links quebrados para ficheiros',

'about' => 'Sobre',
'article' => 'Página de conteúdo',
'newwindow' => '(abre numa janela nova)',
'cancel' => 'Cancelar',
'moredotdotdot' => 'Mais...',
'morenotlisted' => 'Esta lista não está completa.',
'mypage' => 'Página',
'mytalk' => 'Discussão',
'anontalk' => 'Discussão para este IP',
'navigation' => 'Navegação',
'and' => '&#32;e',

# Cologne Blue skin
'qbfind' => 'Procurar',
'qbbrowse' => 'Navegar',
'qbedit' => 'Editar',
'qbpageoptions' => 'Esta página',
'qbmyoptions' => 'As minhas páginas',
'qbspecialpages' => 'Páginas especiais',
'faq' => 'Perguntas frequentes',
'faqpage' => 'Project:FAQ',

# Vector skin
'vector-action-addsection' => 'Adicionar&nbsp;tópico',
'vector-action-delete' => 'Eliminar',
'vector-action-move' => 'Mover',
'vector-action-protect' => 'Proteger',
'vector-action-undelete' => 'Restaurar',
'vector-action-unprotect' => 'Alterar proteção',
'vector-simplesearch-preference' => 'Ativar barra de pesquisa simplificada (apenas no tema Vector)',
'vector-view-create' => 'Criar',
'vector-view-edit' => 'Editar',
'vector-view-history' => 'Ver histórico',
'vector-view-view' => 'Ler',
'vector-view-viewsource' => 'Ver fonte',
'actions' => 'Ações',
'namespaces' => 'Espaços nominais',
'variants' => 'Variantes',

'navigation-heading' => 'Menu de navegação',
'errorpagetitle' => 'Erro',
'returnto' => 'Voltar para $1.',
'tagline' => 'Da {{SITENAME}}',
'help' => 'Ajuda',
'search' => 'Pesquisa',
'searchbutton' => 'Pesquisar',
'go' => 'Prosseguir',
'searcharticle' => 'Ir',
'history' => 'Histórico',
'history_short' => 'Histórico',
'updatedmarker' => 'atualizado desde a minha última visita',
'printableversion' => 'Versão para impressão',
'permalink' => 'Link permanente',
'print' => 'Imprimir',
'view' => 'Ver',
'edit' => 'Editar',
'create' => 'Criar',
'editthispage' => 'Editar esta página',
'create-this-page' => 'Criar esta página',
'delete' => 'Eliminar',
'deletethispage' => 'Eliminar esta página',
'undeletethispage' => 'Restaurar esta página',
'undelete_short' => 'Restaurar {{PLURAL:$1|uma edição|$1 edições}}',
'viewdeleted_short' => 'Ver {{PLURAL:$1|uma edição eliminada|$1 edições eliminadas}}',
'protect' => 'Proteger',
'protect_change' => 'alterar',
'protectthispage' => 'Proteger esta página',
'unprotect' => 'Alterar proteção',
'unprotectthispage' => 'Alterar a proteção desta página',
'newpage' => 'Página nova',
'talkpage' => 'Discutir esta página',
'talkpagelinktext' => 'discussão',
'specialpage' => 'Página especial',
'personaltools' => 'Ferramentas pessoais',
'postcomment' => 'Seção nova',
'articlepage' => 'Ver página de conteúdo',
'talk' => 'Discussão',
'views' => 'Vistas',
'toolbox' => 'Ferramentas',
'userpage' => 'Ver página de utilizador',
'projectpage' => 'Ver página de projeto',
'imagepage' => 'Ver página de ficheiro',
'mediawikipage' => 'Ver página da mensagem',
'templatepage' => 'Ver página da predefinição',
'viewhelppage' => 'Ver página de ajuda',
'categorypage' => 'Ver página da categoria',
'viewtalkpage' => 'Ver discussão',
'otherlanguages' => 'Noutras línguas',
'redirectedfrom' => '(Redireccionado de $1)',
'redirectpagesub' => 'Página de redirecionamento',
'lastmodifiedat' => 'Esta página foi modificada pela última vez à(s) $2 de $1.',
'viewcount' => 'Esta página foi acedida {{PLURAL:$1|uma vez|$1 vezes}}.',
'protectedpage' => 'Página protegida',
'jumpto' => 'Ir para:',
'jumptonavigation' => 'navegação',
'jumptosearch' => 'pesquisa',
'view-pool-error' => 'Desculpe, mas de momento os servidores estão sobrecarregados.
Há demasiados utilizadores a tentar visionar esta página.
Espere um pouco antes de tentar aceder à página novamente, por favor.

$1',
'pool-timeout' => 'Tempo limite de espera para o bloqueio excedido',
'pool-queuefull' => 'A fila de processos está cheia',
'pool-errorunknown' => 'Erro desconhecido',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage).
'aboutsite' => 'Sobre a {{SITENAME}}',
'aboutpage' => 'Project:Sobre',
'copyright' => 'Conteúdo disponibilizado nos termos da $1, salvo indicação em contrário.',
'copyrightpage' => '{{ns:project}}:Direitos_de_autor',
'currentevents' => 'Notícias',
'currentevents-url' => 'Project:Notícias',
'disclaimers' => 'Exoneração de responsabilidade',
'disclaimerpage' => 'Project:Aviso_geral',
'edithelp' => 'Ajuda de edição',
'helppage' => 'Help:Conteúdos',
'mainpage' => 'Página principal',
'mainpage-description' => 'Página principal',
'policy-url' => 'Project:Políticas',
'portal' => 'Portal comunitário',
'portal-url' => 'Project:Portal comunitário',
'privacy' => 'Política de privacidade',
'privacypage' => 'Project:Política_de_privacidade',

'badaccess' => 'Erro de permissão',
'badaccess-group0' => 'Não está autorizado a executar a operação solicitada.',
'badaccess-groups' => 'A operação solicitada está limitada a utilizadores {{PLURAL:$2|do grupo|de um dos seguintes grupos}}: $1.',

'versionrequired' => 'É necessária a versão $1 do MediaWiki',
'versionrequiredtext' => 'É necessária a versão $1 do MediaWiki para usar esta página.
Consulte a página da [[Special:Version|versão do sistema]].',

'ok' => 'OK',
'retrievedfrom' => 'Obtida de "$1"',
'youhavenewmessages' => 'Tem $1 ($2).',
'newmessageslink' => 'mensagens novas',
'newmessagesdifflink' => 'comparar com a penúltima revisão',
'youhavenewmessagesfromusers' => 'Tem $1 de {{PLURAL:$3|outro utilizador|$3 utilizadores}} ($2).',
'youhavenewmessagesmanyusers' => 'Tem $1 de muitos utilizadores ($2).',
'newmessageslinkplural' => '{{PLURAL:$1|uma mensagem nova|mensagens novas}}',
'newmessagesdifflinkplural' => '{{PLURAL:$1|última alteração|últimas alterações}}',
'youhavenewmessagesmulti' => 'Tem mensagens novas em $1',
'editsection' => 'editar',
'editold' => 'editar',
'viewsourceold' => 'ver código-fonte',
'editlink' => 'editar',
'viewsourcelink' => 'ver código-fonte',
'editsectionhint' => 'Editar seção: $1',
'toc' => 'Índice',
'showtoc' => 'mostrar',
'hidetoc' => 'esconder',
'collapsible-collapse' => 'Ocultar',
'collapsible-expand' => 'Expandir',
'thisisdeleted' => 'Ver ou restaurar $1?',
'viewdeleted' => 'Ver $1?',
'restorelink' => '{{PLURAL:$1|uma edição eliminada|$1 edições eliminadas}}',
'feedlinks' => "''Feed'':",
'feed-invalid' => "Tipo de subscrição de ''feed'' inválido.",
'feed-unavailable' => 'Não há "feeds" disponíveis',
'site-rss-feed' => "''Feed'' RSS $1",
'site-atom-feed' => "''Feed'' Atom $1",
'page-rss-feed' => "''Feed'' RSS de \"\$1\"",
'page-atom-feed' => "''Feed'' Atom de \"\$1\"",
'red-link-title' => '$1 (página não existe)',
'sort-descending' => 'Ordenar por ordem descendente',
'sort-ascending' => 'Ordenar por ordem ascendente',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main' => 'Página',
'nstab-user' => 'Página d{{GENDER:{{#titleparts:{{PAGENAME}}|1|1}}|o utilizador|a utilizadora|e utilizador(a)}}',
'nstab-media' => 'Multimédia',
'nstab-special' => 'Página especial',
'nstab-project' => 'Página do projeto',
'nstab-image' => 'Ficheiro',
'nstab-mediawiki' => 'Mensagem',
'nstab-template' => 'Predefinição',
'nstab-help' => 'Ajuda',
'nstab-category' => 'Categoria',

# Main script and global functions
'nosuchaction' => 'Operação não existe',
'nosuchactiontext' => 'A operação especificada pela URL é inválida.
É possível que tenha escrito mal a URL ou seguido um link incorreto.
Isto pode também indicar um defeito no software da {{SITENAME}}.',
'nosuchspecialpage' => 'Esta página especial não existe',
'nospecialpagetext' => '<strong>Solicitou uma página especial inválida.</strong>

Encontra uma lista das páginas especiais válidas em [[Special:SpecialPages|{{int:specialpages}}]].',

# General errors
'error' => 'Erro',
'databaseerror' => 'Erro na base de dados',
'databaseerror-text' => 'Ocorreu um erro na consulta à base de dados.
Isto pode indicar um defeito no programa.',
'databaseerror-textcl' => 'Ocorreu um erro na consulta à base de dados.',
'databaseerror-query' => 'Consulta:$1',
'databaseerror-error' => 'Erro: $1',
'laggedslavemode' => "'''Aviso:''' A página pode não conter as atualizações mais recentes.",
'readonly' => 'Base de dados bloqueada (limitada a leituras)',
'enterlockreason' => 'Introduza um motivo para bloquear, incluindo uma estimativa de quando será desbloqueada',
'readonlytext' => 'A base de dados está bloqueada para impedir a inserção e modificação de dados, provavelmente para uma manutenção de rotina, após a qual a situação será normalizada.

O administrador que a bloqueou deu a seguinte explicação: $1',
'missing-article' => 'A base de dados não encontrou o texto de uma página que deveria ter encontrado, com o nome "$1" $2.

Geralmente, esta situação ocorre ao clicar um link para diferenças desatualizado ou para o histórico de uma página que tenha sido removida.

Se nenhuma destas situações se verifica, pode ter encontrado um defeito no programa.
Anote a URL e reporte este incidente a um [[Special:ListUsers/sysop|administrador]], por favor.',
'missingarticle-rev' => '(revisão#: $1)',
'missingarticle-diff' => '(Dif.: $1, $2)',
'readonly_lag' => 'A base de dados foi automaticamente bloqueada enquanto os servidores secundários se sincronizam com o primário',
'internalerror' => 'Erro interno',
'internalerror_info' => 'Erro interno: $1',
'fileappenderrorread' => 'Não foi possível ler "$1" durante a anexação.',
'fileappenderror' => 'Não foi possível adicionar "$1" a "$2".',
'filecopyerror' => 'Não foi possível copiar o ficheiro "$1" para "$2".',
'filerenameerror' => 'Não foi possível alterar o nome do ficheiro "$1" para "$2".',
'filedeleteerror' => 'Não foi possível eliminar o ficheiro "$1".',
'directorycreateerror' => 'Não foi possível criar o diretório "$1".',
'filenotfound' => 'Não foi possível encontrar o ficheiro "$1".',
'fileexistserror' => 'Não foi possível gravar no ficheiro "$1": ele já existe',
'unexpected' => 'Valor não esperado: "$1"="$2".',
'formerror' => 'Erro: Não foi possível enviar o formulário',
'badarticleerror' => 'Esta operação não pode ser realizada nesta página.',
'cannotdelete' => 'Não foi possível eliminar a página ou ficheiro "$1".
Pode já ter sido eliminado por outro utilizador.',
'cannotdelete-title' => 'Não é possível eliminar a página "$1"',
'delete-hook-aborted' => 'A eliminação foi cancelada por um "hook".
Não foi dada nenhuma explicação.',
'no-null-revision' => 'Não foi possível criar uma nova revisão nula para a página "$1"',
'badtitle' => 'Título inválido',
'badtitletext' => 'O título de página solicitado era inválido, vazio, ou um link interlínguas ou interwikis incorrecto.
Talvez contenha um ou mais caracteres que não podem ser usados em títulos.',
'perfcached' => "Os seguintes dados encontram-se armazenados na ''cache'' e podem não estar atualizados. No máximo {{PLURAL:$1|um resultado é disponível|$1 resultados são disponíveis}} na ''cache''.",
'perfcachedts' => "Os seguintes dados encontram-se armazenados na ''cache'' e foram atualizados pela última vez a $1. No máximo {{PLURAL:$4|um resultado está disponível|$4 resultados estão disponíveis}} na ''cache''.",
'querypage-no-updates' => 'As atualizações estão presentemente desativadas para esta página.
Por enquanto, os dados aqui presentes não poderão ser atualizados.',
'wrong_wfQuery_params' => 'Parâmetros incorretos para wfQuery()<br />
Função: $1<br />
Consulta: $2',
'viewsource' => 'Ver código-fonte',
'viewsource-title' => 'Mostrar código-fonte de $1',
'actionthrottled' => 'Operação limitada',
'actionthrottledtext' => 'Como medida anti-spam, está impedido de realizar esta operação demasiadas vezes num espaço de tempo curto e já excedeu esse limite. Tente de novo dentro de alguns minutos, por favor.',
'protectedpagetext' => 'Esta página foi protegida para prevenir a sua edição.',
'viewsourcetext' => 'Pode ver e copiar o conteúdo desta página:',
'viewyourtext' => "Pode ver e copiar o código-fonte das '''suas edições''' desta página:",
'protectedinterface' => 'Esta página fornece o texto da interface ao software, e está protegida para prevenir abusos.',
'editinginterface' => "'''Aviso:''' Está a editar uma página usada para fornecer texto de interface ao software. Alterações a esta página afetarão a aparência da interface de utilizador para os outros utilizadores. Para traduções, considere utilizar a [//translatewiki.net/wiki/Main_Page?setlang=pt translatewiki.net], um projeto destinado à tradução do MediaWiki.",
'cascadeprotected' => 'Esta página foi protegida contra edições por estar incluída {{PLURAL:$1|na página listada|nas páginas listadas}} a seguir, ({{PLURAL:$1|página essa que está protegida|páginas essas que estão protegidas}} com a opção de proteção "em cascata" ativada):
$2',
'namespaceprotected' => "Não possui permissão para editar páginas no espaço nominal '''$1'''.",
'customcssprotected' => 'Não tem permissões para editar esta página de CSS porque ela contém as configurações pessoais de outro utilizador.',
'customjsprotected' => 'Não tem permissões para editar esta página de JavaScript porque ela contém as configurações pessoais de outro utilizador.',
'mycustomcssprotected' => 'Não tem permissão para editar esta página de CSS.',
'mycustomjsprotected' => 'Não tem permissão para editar esta página de JavaScript.',
'myprivateinfoprotected' => 'Você não tem permissão para editar sua informação privada.',
'mypreferencesprotected' => 'Você não tem permissão para editar as suas preferências.',
'ns-specialprotected' => 'Não é possível editar páginas especiais',
'titleprotected' => 'Este título foi protegido contra criação por [[User:$1|$1]].
A justificação foi "\'\'$2\'\'".',
'filereadonlyerror' => 'Não é possível modificar o ficheiro "$1" porque o repositório de ficheiros "$2" está em modo de leitura.

O administrador que efetuou o bloqueio deu a seguinte explicação: "$3".',
'invalidtitle-knownnamespace' => 'Título inválido com o espaço nominal "$2" e texto "$3"',
'invalidtitle-unknownnamespace' => 'Título inválido com número de espaço nominal $1 desconhecido e texto "$2"',
'exception-nologin' => 'Não está autenticado',
'exception-nologin-text' => 'Esta página ou operação requer que esteja autenticado nesta wiki.',

# Virus scanner
'virus-badscanner' => "Má configuração: antivírus desconhecido: ''$1''",
'virus-scanfailed' => 'a verificação falhou (código $1)',
'virus-unknownscanner' => 'antivírus desconhecido:',

# Login and logout pages
'logouttext' => "'''Já não está autenticado.'''

Tenha em atenção que algumas páginas poderão continuar a ser apresentadas como se ainda estivesse autenticado até limpar a cache do seu browser.",
'welcomeuser' => 'Bem-vindo, $1!',
'welcomecreation-msg' => 'A sua conta foi criada.
Não se esqueça de personalizar as suas [[Special:Preferences|preferências]].',
'yourname' => 'Nome de utilizador:',
'userlogin-yourname' => 'Nome de utilizador(a):',
'userlogin-yourname-ph' => 'Digite seu nome de utilizador(a)',
'createacct-another-username-ph' => 'Digite o nome de utilizador',
'yourpassword' => 'Palavra-chave:',
'userlogin-yourpassword' => 'Palavra-chave',
'userlogin-yourpassword-ph' => 'Digite sua palavra-chave',
'createacct-yourpassword-ph' => 'Digite uma palavra-chave',
'yourpasswordagain' => 'Repita a palavra-chave:',
'createacct-yourpasswordagain' => 'Confirme a palavra-chave',
'createacct-yourpasswordagain-ph' => 'Digite a palavra-chave novamente',
'remembermypassword' => 'Recordar os meus dados neste computador (no máximo, por $1 {{PLURAL:$1|dia|dias}})',
'userlogin-remembermypassword' => 'Manter-me autenticado',
'userlogin-signwithsecure' => 'Use uma ligação segura',
'yourdomainname' => 'O seu domínio:',
'password-change-forbidden' => 'Não pode alterar senhas nesta wiki.',
'externaldberror' => 'Ocorreu um erro externo à base de dados durante a autenticação ou não lhe é permitido atualizar a sua conta externa.',
'login' => 'Autenticação',
'nav-login-createaccount' => 'Entrar / criar conta',
'loginprompt' => "É necessário ter os ''cookies'' ativados no seu browser para poder autenticar-se na {{SITENAME}}.",
'userlogin' => 'Criar uma conta ou entrar',
'userloginnocreate' => 'Autenticação',
'logout' => 'Sair',
'userlogout' => 'Sair',
'notloggedin' => 'Não autenticado',
'userlogin-noaccount' => 'Não tem uma conta?',
'userlogin-joinproject' => 'Junte-se ao projeto {{SITENAME}}',
'nologin' => 'Não possui uma conta? $1.',
'nologinlink' => 'Criar uma conta',
'createaccount' => 'Criar conta',
'gotaccount' => "Já possui uma conta? '''$1'''.",
'gotaccountlink' => 'Autentique-se',
'userlogin-resetlink' => 'Esqueceu-se do seu nome de utilizador ou da palavra-chave?',
'userlogin-resetpassword-link' => 'Recuperar palavra-chave',
'helplogin-url' => 'Help:Autenticação',
'userlogin-helplink' => '[[{{MediaWiki:helplogin-url}}|Ajuda a fazer login]]',
'userlogin-loggedin' => 'Já está {{GENDER:$1|autenticado|autenticada|autenticado}} com o nome $1.
Use o formulário abaixo para iniciar uma sessão com outro nome.',
'userlogin-createanother' => 'Criar outra conta',
'createacct-join' => 'Insira a sua informação abaixo.',
'createacct-another-join' => 'Digite a informação da nova conta abaixo.',
'createacct-emailrequired' => 'Endereço de email',
'createacct-emailoptional' => 'Endereço de email (opcional)',
'createacct-email-ph' => 'Digite seu endereço de email',
'createacct-another-email-ph' => 'Digite o endereço de e-mail',
'createaccountmail' => 'Usar uma palavra passe aleatória e temporária e enviar para o endereço de correio eletrónico especificado',
'createacct-realname' => 'Nome verdadeiro (opcional)',
'createaccountreason' => 'Motivo:',
'createacct-reason' => 'Razão',
'createacct-reason-ph' => 'Porque está a criar outra conta',
'createacct-captcha' => 'Verificar segurança',
'createacct-imgcaptcha-ph' => 'Digite o texto que vê acima',
'createacct-submit' => 'Crie a sua conta',
'createacct-another-submit' => 'Criar uma outra conta',
'createacct-benefit-heading' => '{{SITENAME}} é feito por pessoas como você.',
'createacct-benefit-body1' => '{{PLURAL:$1|edição|edições}}',
'createacct-benefit-body2' => '{{PLURAL:$1|página|páginas}}',
'createacct-benefit-body3' => '{{PLURAL:$1|contribuidor|contribuidores}} recentes',
'badretype' => 'As palavras-chave que introduziu não são iguais.',
'userexists' => 'O nome de utilizador introduzido já existe.
Por favor escolha um nome diferente.',
'loginerror' => 'Erro de autenticação',
'createacct-error' => 'Erro na criação da conta',
'createaccounterror' => 'Não foi possível criar a conta: $1',
'nocookiesnew' => "A conta de utilizador foi criada, mas neste momento não está autenticado.
A {{SITENAME}} utiliza ''cookies'' para autenticar os utilizadores.
Os ''cookies'' estão desativados no seu browser.
Ative-os e autentique-se com o seu nome de utilizador e a sua palavra-chave, por favor.",
'nocookieslogin' => "A {{SITENAME}} utiliza ''cookies'' para autenticar os utilizadores.
Os ''cookies'' estão desativados no seu browser.
Ative-os e tente novamente, por favor.",
'nocookiesfornew' => 'A conta de utilizador não foi criada, porque não foi possível confirmar a sua origem.
Certifique-se de que tem os cookies ativados, recarregue esta página e tente novamente.',
'noname' => 'Não especificou um nome de utilizador válido.',
'loginsuccesstitle' => 'Login bem sucedido',
'loginsuccess' => "'''Encontra-se agora ligado à {{SITENAME}} como \"\$1\"'''.",
'nosuchuser' => 'Não existe nenhum utilizador com o nome "$1".
Os nomes de utilizador são sensíveis à capitalização.
Verifique a ortografia, ou [[Special:UserLogin/signup|crie uma nova conta]].',
'nosuchusershort' => 'Não existe um utilizador com o nome "$1". Verifique o nome que introduziu.',
'nouserspecified' => 'Precisa de especificar um nome de utilizador.',
'login-userblocked' => 'Este utilizador está bloqueado. Não é permitido o acesso.',
'wrongpassword' => 'A palavra-chave que introduziu é inválida. Tente novamente, por favor.',
'wrongpasswordempty' => 'A palavra-chave não foi introduzida. Introduza-a, por favor.',
'passwordtooshort' => 'A palavra-chave deve ter no mínimo $1 {{PLURAL:$1|carácter|caracteres}}.',
'password-name-match' => 'A sua palavra-chave tem de ser diferente do seu nome de utilizador.',
'password-login-forbidden' => 'Foi proibido o uso deste nome de utilizador e palavra-chave.',
'mailmypassword' => 'Enviar uma palavra-chave nova por correio electrónico',
'passwordremindertitle' => 'Nova palavra-chave temporária na {{SITENAME}}',
'passwordremindertext' => 'Alguém (provavelmente você, a partir do endereço IP $1) solicitou uma palavra-chave nova para a sua conta na {{SITENAME}} ($4).
Foi criada a palavra-chave temporária "$3" para o utilizador "$2".
Se o pedido foi feito por si, entre agora na sua conta e escolha uma palavra-chave nova.
A palavra-chave temporária expira após {{PLURAL:$5|um dia|$5 dias}}.

Caso outra pessoa tenha feito o pedido, ou se entretanto se recordou da sua palavra-chave e já não deseja alterá-la, ignore esta mensagem e continue a utilizar a palavra-chave antiga.',
'noemail' => 'Não foi registado um endereço de correio electrónico para o utilizador "$1".',
'noemailcreate' => 'Precisa de fornecer um endereço de correio electrónico válido',
'passwordsent' => 'Foi enviada uma palavra-chave nova para o endereço de correio electrónico do utilizador "$1".
Volte a autenticar-se após recebê-la, por favor.',
'blocked-mailpassword' => 'O seu endereço IP foi bloqueado e, portanto, não será possível utilizar a função de recuperação da palavra-chave, para prevenir o uso abusivo.',
'eauthentsent' => 'Foi enviada uma mensagem de confirmação para o endereço de correio electrónico que elegeu.
Antes que seja enviada qualquer outra mensagem para a conta, terá de seguir as instruções na mensagem enviada, de modo a confirmar que a conta lhe pertence.',
'throttled-mailpassword' => 'Já foi enviada um email de recuperação de palavra-chave {{PLURAL:$1|na última hora|nas últimas $1 horas}}.
Para prevenir abusos, só um email de recuperação de palavra-chave pode ser enviado a cada {{PLURAL:$1|hora|$1 horas}}.',
'mailerror' => 'Erro ao enviar correio: $1',
'acct_creation_throttle_hit' => 'Visitantes desta wiki com o seu endereço IP criaram $1 {{PLURAL:$1|conta|contas}} no último dia, o que é o máximo permitido neste período de tempo.
Em resultado, visitantes com este endereço IP não podem criar mais nenhuma conta neste momento.',
'emailauthenticated' => 'O seu endereço de correio electrónico foi autenticado a $2 às $3.',
'emailnotauthenticated' => 'O seu endereço de correio electrónico ainda não foi autenticado.
Não serão enviados correios de nenhuma das seguintes funcionalidades.',
'noemailprefs' => 'Especifique um endereço de correio eletrónico nas suas preferências para ativar estas funcionalidades.',
'emailconfirmlink' => 'Confirme o seu endereço de correio electrónico',
'invalidemailaddress' => 'O endereço de correio eletrónico não pode ser aceite porque parece ter um formato inválido.
Introduza um endereço formatado corretamente ou deixe o campo vazio.',
'cannotchangeemail' => 'Os endereços de correio electrónico das contas não podem ser alterados nesta wiki.',
'emaildisabled' => 'Este site não consegue enviar e-mails.',
'accountcreated' => 'Conta criada',
'accountcreatedtext' => 'A conta de utilizador para [[{{ns:User}}:$1|$1]] ([[{{ns:User talk}}:$1|talk]]) foi criada.',
'createaccount-title' => 'Criação de conta na {{SITENAME}}',
'createaccount-text' => 'Alguém criou uma conta com o nome $2 para o seu endereço de correio electrónico, na wiki {{SITENAME}} ($4), com a palavra-chave "$3".
Deve agora autenticar-se e alterar a sua palavra-chave.

Se a conta foi criada por engano, pode ignorar esta mensagem.',
'usernamehasherror' => 'O nome de utilizador não pode conter o símbolo de cardinal (#).',
'login-throttled' => 'Você realizou demasiadas tentativas de autenticação com esta conta.
Aguarde $1 antes de tentar novamente, por favor.',
'login-abort-generic' => 'A sua autenticação não teve êxito - Cancelada',
'loginlanguagelabel' => 'Língua: $1',
'suspicious-userlogout' => 'O seu pedido para sair foi negado porque parece ter sido enviado por um browser danificado ou por um proxy com cache.',
'createacct-another-realname-tip' => 'O fornecimento do nome verdadeiro é opcional.
Se optar por revelá-lo, ele será utilizado para atribuir-lhe crédito pelo seu trabalho.',

# Email sending
'php-mail-error-unknown' => 'Erro desconhecido na função mail() do PHP',
'user-mail-no-addy' => 'Tentou enviar uma mensagem sem um endereço de correio electrónico',
'user-mail-no-body' => 'Tentou mandar email sem conteúdo ou com conteúdo demasiado pequeno.',

# Change password dialog
'resetpass' => 'Alterar palavra-chave',
'resetpass_announce' => 'Autenticou-se usando uma palavra-chave temporária enviada por correio electrónico.
Para prosseguir, será necessário definir uma nova palavra-chave.',
'resetpass_text' => '<!-- Adicionar texto aqui -->',
'resetpass_header' => 'Alterar palavra-chave da conta',
'oldpassword' => 'Palavra-chave anterior:',
'newpassword' => 'Palavra-chave nova:',
'retypenew' => 'Repita a palavra-chave nova:',
'resetpass_submit' => 'Definir palavra-chave e entrar',
'changepassword-success' => 'A sua palavra-chave foi alterada com êxito!',
'resetpass_forbidden' => 'Não é possível alterar palavras-chave',
'resetpass-no-info' => 'Precisa estar autenticado para aceder diretamente a esta página.',
'resetpass-submit-loggedin' => 'Alterar palavra-chave',
'resetpass-submit-cancel' => 'Cancelar',
'resetpass-wrong-oldpass' => 'Palavra-chave temporária ou atual inválida.
Pode ter já alterado com sucesso a sua palavra-chave ou solicitado uma nova palavra-chave temporária.',
'resetpass-temp-password' => 'Palavra-chave temporária:',
'resetpass-abort-generic' => 'A alteração da senha foi cancelada por uma extensão.',

# Special:PasswordReset
'passwordreset' => 'Repor palavra-chave',
'passwordreset-text-one' => 'Preencha este formulário para repor a sua palavra-passe.',
'passwordreset-text-many' => '{{PLURAL:$1|Preencha um dos campos para redefinir sua senha.}}',
'passwordreset-legend' => 'Reiniciar a palavra-chave',
'passwordreset-disabled' => 'O reinício da palavra-chave foi impossibilitado nesta wiki.',
'passwordreset-emaildisabled' => 'Recursos de e-mail foram desativados neste wiki.',
'passwordreset-username' => 'Nome de utilizador:',
'passwordreset-domain' => 'Domínio:',
'passwordreset-capture' => 'Ver o email resultante?',
'passwordreset-capture-help' => 'Se marcar esta caixa, poderá ver a mensagem (com a senha temporária) que será enviada ao utilizador.',
'passwordreset-email' => 'Correio electrónico:',
'passwordreset-emailtitle' => 'Detalhes da conta na {{SITENAME}}',
'passwordreset-emailtext-ip' => 'Alguém (provavelmente você, a partir do endereço IP $1) pediu a recuperação da palavra-passe no projeto {{SITENAME}} ($4). {{PLURAL:$3|A seguinte conta de utilizador está associada|As seguintes contas de utilizador estão associadas}} a este correio eletrónico:

$2

{{PLURAL:$3|Esta palavra-passe temporária irá|Estas palavras-passes temporárias irão}} expirar dentro de {{PLURAL:$5|um dia|$5 dias}}.
Deve autenticar-se e escolher uma palavra-passe nova agora. Se outra pessoa fez este pedido, ou se entretanto se recordou da sua palavra-passe original e já não deseja alterá-la, pode ignorar esta mensagem e continuar a usar a palavra-passe antiga.',
'passwordreset-emailtext-user' => 'O utilizador $1 do projeto {{SITENAME}} pediu a recuperação da sua palavra-passe no projeto {{SITENAME}} ($4). {{PLURAL:$3|A seguinte conta de utilizador está associada|As seguintes contas de utilizador estão associadas}} a este endereço de correio eletrónico:

$2

{{PLURAL:$3|Esta palavra-passe temporária irá|Estas palavras-passes temporárias irão}} expirar dentro de {{PLURAL:$5|um dia|$5 dias}}.
Deve autenticar-se e escolher uma palavra-passe nova agora. Se outra pessoa fez este pedido, ou se entretanto se recordou da sua palavra-passe original e já não deseja alterá-la, pode ignorar esta mensagem e continuar a usar a palavra-passe antiga.',
'passwordreset-emailelement' => 'Utilizador: $1
Palavra-chave temporária: $2',
'passwordreset-emailsent' => 'Foi enviado um correio eletrónico para recuperação da palavra-passe.',
'passwordreset-emailsent-capture' => 'Foi enviado um e-mail de recuperação da palavra-passe, que é mostrado abaixo.',
'passwordreset-emailerror-capture' => 'Foi gerado o e-mail de reposição de palavra-passe mostrado abaixo, contudo falhou o seu envio para {{GENDER:$2|o utilizador|a utilizadora}}: $1',

# Special:ChangeEmail
'changeemail' => 'Alterar o endereço de correio electrónico',
'changeemail-header' => 'Alterar o endereço de correio electrónico da conta',
'changeemail-text' => 'Preencha este formulário para alterar o endereço de correio electrónico. Para confirmar esta alteração terá de introduzir a sua palavra-chave.',
'changeemail-no-info' => 'Para aceder diretamente a esta página tem de estar autenticado.',
'changeemail-oldemail' => 'Correio electrónico actual:',
'changeemail-newemail' => 'Correio electrónico novo:',
'changeemail-none' => '(nenhum)',
'changeemail-password' => 'A sua senha na wiki {{SITENAME}}:',
'changeemail-submit' => 'Alterar correio electrónico',
'changeemail-cancel' => 'Cancelar',

# Special:ResetTokens
'resettokens' => 'Redefinir chaves',
'resettokens-text' => 'Pode redefinir as chaves de acesso a certos dados privados associados à sua conta aqui.

Deve fazê-lo se as divulgou acidentalmente a alguém ou se a sua conta tiver sido comprometida.',
'resettokens-no-tokens' => 'Não há chaves para redefinir.',
'resettokens-legend' => 'Redefinir chaves',
'resettokens-tokens' => 'Chaves:',
'resettokens-token-label' => '$1 (valor actual: $2)',
'resettokens-watchlist-token' => "Chave para o ''feed'' Atom/RSS de [[Special:Watchlist|mudanças às páginas vigiadas]]",
'resettokens-done' => 'As chaves foram redefinidas.',
'resettokens-resetbutton' => 'Redefinir chaves selecionadas',

# Edit page toolbar
'bold_sample' => 'Texto a negrito',
'bold_tip' => 'Texto a negrito',
'italic_sample' => 'Texto em itálico',
'italic_tip' => 'Texto em itálico',
'link_sample' => 'Título do link',
'link_tip' => 'Link interno',
'extlink_sample' => 'http://www.example.com link externo',
'extlink_tip' => 'Link externo (lembre-se do prefixo http://)',
'headline_sample' => 'Texto do cabeçalho',
'headline_tip' => 'Seção de nível 2',
'nowiki_sample' => 'Inserir texto não-formatado aqui',
'nowiki_tip' => 'Ignorar formatação wiki',
'image_sample' => 'Exemplo.jpg',
'image_tip' => 'Ficheiro incorporado',
'media_sample' => 'Exemplo.ogg',
'media_tip' => 'Link para ficheiro',
'sig_tip' => 'A sua assinatura, com hora e data',
'hr_tip' => 'Linha horizontal (utilize moderadamente)',

# Edit pages
'summary' => 'Resumo:',
'subject' => 'Assunto/cabeçalho:',
'minoredit' => 'Marcar como edição menor',
'watchthis' => 'Vigiar esta página',
'savearticle' => 'Gravar página',
'preview' => 'Antevisão',
'showpreview' => 'Antever resultado',
'showlivepreview' => 'Antevisão em tempo real',
'showdiff' => 'Mostrar alterações',
'anoneditwarning' => "'''Aviso''': Não se encontra autenticado.
O seu endereço IP será registado no histórico de edições desta página.",
'anonpreviewwarning' => "''Não está autenticado. Gravar registará o seu endereço IP no histórico de edições da página.''",
'missingsummary' => "'''Atenção:''' Não introduziu um resumo da edição.
Se clicar novamente \"Gravar página\" a sua edição será gravada sem resumo.",
'missingcommenttext' => 'Introduza um comentário abaixo, por favor.',
'missingcommentheader' => "'''Atenção:''' Não introduziu um assunto ou cabeçalho para este comentário.
Se clicar novamente \"{{int:savearticle}}\", a sua edição será gravada sem assunto ou cabeçalho.",
'summary-preview' => 'Antevisão do resumo:',
'subject-preview' => 'Antevisão do assunto/cabeçalho:',
'blockedtitle' => 'O utilizador está bloqueado',
'blockedtext' => 'O seu nome de utilizador ou endereço IP foram bloqueados

O bloqueio foi realizado por $1.
O motivo apresentado foi \'\'$2\'\'.

* Início do bloqueio: $8
* Expiração do bloqueio: $6
* Destinatário do bloqueio: $7

Pode contactar $1 ou outro [[{{MediaWiki:Grouppage-sysop}}|administrador]] para discutir o bloqueio.

Note que para utilizar a funcionalidade "Contactar utilizador" precisa de ter um endereço de correio electrónico válido nas suas [[Special:Preferences|preferências de utilizador]] e de não lhe ter sido bloqueado o uso desta funcionalidade.

O seu endereço IP neste momento é $3 e a identificação (ID) do bloqueio é #$5.
Inclua todos os detalhes acima em quaisquer contactos relacionados com este bloqueio, por favor.',
'autoblockedtext' => 'O seu endereço IP foi bloqueado de forma automática, uma vez que foi utilizado recentemente por outro utilizador, o qual foi bloqueado por $1.
O motivo apresentado foi:

:\'\'$2\'\'

* Início do bloqueio: $8
* Expiração do bloqueio: $6
* Destinatário do bloqueio: $7

Pode contactar $1 ou outro [[{{MediaWiki:Grouppage-sysop}}|administrador]] para discutir o bloqueio.

Note que para utilizar a funcionalidade "Contactar utilizador" precisa de ter um endereço de correio electrónico válido nas suas [[Special:Preferences|preferências de utilizador]] e de não lhe ter sido bloqueado o uso desta funcionalidade.

O seu endereço IP neste momento é $3 e a identificação (ID) do bloqueio é #$5.
Inclua todos os detalhes acima em quaisquer contactos relacionados com este bloqueio, por favor.',
'blockednoreason' => 'sem motivo especificado',
'whitelistedittext' => 'Precisa de $1 para poder editar páginas.',
'confirmedittext' => 'Precisa de confirmar o seu endereço de correio electrónico antes de começar a editar páginas.
Introduza e valide o endereço através das [[Special:Preferences|preferências do utilizador]], por favor.',
'nosuchsectiontitle' => 'Não foi possível encontrar a seção',
'nosuchsectiontext' => 'Tentou editar uma seção que não existe.
Ela pode ter sido movida ou removida enquanto estava a ver a página.',
'loginreqtitle' => 'Autenticação necessária',
'loginreqlink' => 'autenticar-se',
'loginreqpagetext' => 'Precisa de $1 para ver outras páginas.',
'accmailtitle' => 'Palavra-chave enviada.',
'accmailtext' => 'Uma palavra-chave gerada aleatoriamente para [[User talk:$1|$1]] foi enviada para $2.

Ela pode ser alterada na página [[Special:ChangePassword|de alteração da palavra-chave]] após autenticação.',
'newarticle' => '(Nova)',
'newarticletext' => "Seguiu um link para uma página que ainda não existe.
Para criá-la, escreva o seu conteúdo na caixa abaixo (consulte a [[{{MediaWiki:Helppage}}|página de ajuda]] para mais detalhes).
Se chegou aqui por engano, clique o botão '''voltar''' (ou ''back'') do seu browser.",
'anontalkpagetext' => "----''Esta é a página de discussão de um utilizador anónimo que ainda não criou uma conta ou não a utiliza, pelo que temos de utilizar o endereço IP para identificá-lo(a).
Um endereço IP pode ser partilhado por vários utilizadores.
Se é um utilizador anónimo e sente que lhe foram direccionados comentários irrelevantes, por favor [[Special:UserLogin/signup|crie uma conta]] ou [[Special:UserLogin|autentique-se]] para evitar futuras confusões com outros utilizadores anónimos.''",
'noarticletext' => 'Ainda não existe texto nesta página.
Pode [[Special:Search/{{PAGENAME}}|pesquisar o título desta página]] noutras páginas,
<span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} procurar registos relacionados]
ou [{{fullurl:{{FULLPAGENAME}}|action=edit}} editar esta página]</span>.',
'noarticletext-nopermission' => 'Ainda não existe texto nesta página.
Pode [[Special:Search/{{PAGENAME}}|pesquisar o título desta página]] noutras páginas, ou <span class="plainlinks">[{{fullurl:{{#Special:Log}}|page={{FULLPAGENAMEE}}}} procurar nos registos relacionados]</span>, mas não tem permissão para criar esta página.',
'missing-revision' => 'A revisão #$1 da página denominada "{{PAGENAME}}" não existe.

Isto é geralmente causado por seguir um link de histórico desatualizado para uma página que foi eliminada.
Os detalhes podem ser encontrados no [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} registo de eliminação].',
'userpage-userdoesnotexist' => 'A conta "<nowiki>$1</nowiki>" não se encontra registada.
Verifique se deseja realmente criar ou editar esta página, por favor.',
'userpage-userdoesnotexist-view' => 'A conta de utilizador "$1" não está registada.',
'blocked-notice-logextract' => 'Este utilizador encontra-se atualmente bloqueado.
Para referência, o último registro de bloqueio é apresentado abaixo:',
'clearyourcache' => "'''Nota:''' Após gravar, terá de limpar a ''cache'' do seu browser para ver as alterações.
*'''Firefox / Safari:''' Pressione ''Shift'' enquanto clica ''Recarregar'', ou pressione ''Ctrl-F5'' ou ''Ctrl-R'' (''⌘-R'' no Mac)
*'''Google Chrome:''' Pressione ''Ctrl-Shift-R'' (''⌘-Shift-R'' no Mac)
*'''Internet Explorer:''' Pressione ''Ctrl'' enquanto clica ''Recarregar'', ou pressione ''Ctrl-F5''
*'''Opera:''' Limpe a ''cache'' em ''Ferramentas → Preferências'' (''Tools → Preferences'')",
'usercssyoucanpreview' => "'''Dica:''' Use o botão \"{{int:showpreview}}\" para testar o seu novo CSS antes de gravar.",
'userjsyoucanpreview' => "'''Dica:''' Use o botão \"{{int:showpreview}}\" para testar o seu novo JavaScript antes de gravar.",
'usercsspreview' => "'''Lembre-se de que está apenas a antever o seu CSS particular.
Este ainda não foi gravado!'''",
'userjspreview' => "'''Lembre-se que está apenas a testar ou antever o seu JavaScript particular.'''
Este ainda não foi gravado!",
'sitecsspreview' => "'''Lembre-se de que está apenas a antever este CSS.'''
'''Ele ainda não foi gravado!'''",
'sitejspreview' => "'''Lembre-se de que está apenas a antever este código JavaScript.'''
'''Ele ainda não foi gravado!'''",
'userinvalidcssjstitle' => "'''Aviso:''' Não existe um tema \"\$1\". Lembre-se que as páginas .css e  .js têm um título em minúsculas, exemplo: {{ns:user}}:Alguém/vector.css em vez de {{ns:user}}:Alguém/Vector.css.",
'updated' => '(Atualizado)',
'note' => "'''Nota:'''",
'previewnote' => "'''Lembre-se que esta é apenas uma antevisão do resultado.'''
As modificações ainda não foram gravadas!",
'continue-editing' => 'Ir para a área de edição',
'previewconflict' => 'Esta antevisão do resultado apresenta o texto da caixa de edição acima tal como este aparecerá se escolher gravá-lo.',
'session_fail_preview' => "'''Não foi possível processar a edição devido à perda dos dados da sua sessão.
Tente novamente, por favor.
Caso continue a não funcionar, tente [[Special:UserLogout|sair]] e voltar a entrar na sua conta.'''",
'session_fail_preview_html' => "'''Não foi possível processar a edição devido à perda dos dados da sua sessão.'''

''Como a wiki {{SITENAME}} possibilita o uso de HTML bruto, a antevisão está oculta por precaução contra ataques com JavaScript.''

'''Se esta é uma tentativa legítima de edição tente novamente, por favor.'''
Caso continue a não funcionar, tente [[Special:UserLogout|sair]] e voltar a entrar na sua conta.",
'token_suffix_mismatch' => "'''A edição foi rejeitada porque o seu browser alterou os sinais de pontuação no editor.'''
A edição foi rejeitada para evitar perdas no texto da página.
Isso acontece ocasionalmente quando se usa um serviço de proxy anonimizador mal configurado.'''",
'edit_form_incomplete' => "'''Algumas partes do formulário de edição não chegaram ao servidor; verifique que a sua edição continua intacta e tente novamente, por favor.'''",
'editing' => 'A editar $1',
'creating' => 'A criar $1',
'editingsection' => 'A editar $1 (seção)',
'editingcomment' => 'A editar $1 (nova seção)',
'editconflict' => 'Conflito de edição: $1',
'explainconflict' => "A página foi alterada por alguém desde que começou a editá-la.
A caixa de texto abaixo mostra o texto existente neste momento.
As suas mudanças são mostradas na área ao fundo da página.
Terá de reintegrar as suas mudanças no texto da caixa abaixo.
'''Só''' o texto desta caixa será gravado quando clicar \"{{int:savearticle}}\".",
'yourtext' => 'O seu texto',
'storedversion' => 'Versão guardada',
'nonunicodebrowser' => "'''Aviso: O seu browser não é compatível com as especificações Unicode.
Foi ativado um sistema de edição alternativo que lhe permite editar as páginas com segurança: os caracteres não-ASCII aparecerão na caixa de edição no formato de códigos hexadecimais.'''",
'editingold' => "'''Aviso: Está a editar uma revisão desatualizada desta página.'''
Se gravar, todas as mudanças feitas a partir desta revisão serão perdidas.",
'yourdiff' => 'Diferenças',
'copyrightwarning' => "Note, por favor, que todas as suas contribuições na {{SITENAME}} são consideradas publicadas nos termos da licença $2 (consulte $1 para mais detalhes).
Se não deseja que o seu texto possa ser inexoravelmente editado e redistribuído, não o envie.
Garante-nos também que isto é algo escrito por si, ou copiado do domínio público ou de outra fonte de teor livre.<br />
'''Não envie conteúdos cujos direitos de autor estão protegidos, sem ter a devida permissão!'''",
'copyrightwarning2' => "Note, por favor, que todas as suas contribuições na {{SITENAME}} podem ser editadas, alteradas ou removidas por outros utilizadores.
Se não deseja que o seu texto seja inexoravelmente editado, não o envie.<br />
Garante-nos também que isto é algo escrito por si, ou copiado do domínio público ou de outra fonte de teor livre (consulte $1 para mais detalhes).<br />
'''Não envie conteúdos cujos direitos de autor estão protegidos, sem ter a devida permissão!'''",
'longpageerror' => "'''Erro: O texto que submeteu ocupa {{PLURAL:$1|um kilobyte|$1 kilobytes}}, que excede o máximo de {{PLURAL:$2|um kilobyte|$2 kilobytes}}.'''
A página não pode ser gravada.",
'readonlywarning' => "'''Aviso: A base de dados foi bloqueada para manutenção, pelo que não poderá gravar a sua edição neste momento.'''
Pode, no entanto, copiar e colar o seu texto num ficheiro de texto e guardá-lo para mais tarde.

O administrador que bloqueou a base de dados forneceu a seguinte explicação: $1",
'protectedpagewarning' => "'''Aviso: Esta página foi protegida para só poder ser editada por administradores.'''
O último registo é apresentado abaixo para referência:",
'semiprotectedpagewarning' => "'''Nota:''' Esta página foi protegida de modo a que apenas utilizadores registados a possam editar.
A última entrada no histórico é fornecida abaixo como referência.",
'cascadeprotectedwarning' => "'''Aviso:''' Esta página está protegida de forma que apenas os administradores possam editá-la, porque se encontra incluída {{PLURAL:$1|na seguinte página protegida|nas seguintes páginas protegidas}} em cascata:",
'titleprotectedwarning' => "'''Aviso: Esta página foi protegida de forma a que [[Special:ListGroupRights|privilégios específicos]] sejam necessários para criá-la.'''
Para referência, é apresentada abaixo a última entrada do registo:",
'templatesused' => '{{PLURAL:$1|Predefinição utilizada|Predefinições utilizadas}} nesta página:',
'templatesusedpreview' => '{{PLURAL:$1|Predefinição utilizada|Predefinições utilizadas}} nesta antevisão:',
'templatesusedsection' => '{{PLURAL:$1|Predefinição utilizada|Predefinições utilizadas}} nesta seção:',
'template-protected' => '(protegida)',
'template-semiprotected' => '(semi-protegida)',
'hiddencategories' => 'Esta página pertence a {{PLURAL:$1|uma categoria oculta|$1 categorias ocultas}}:',
'edittools' => '<!-- O texto colocado aqui será mostrado abaixo dos formulários de edição e de envio de ficheiros. -->',
'nocreatetext' => 'A {{SITENAME}} restringe a criação de páginas novas por utilizadores anónimos.
Pode voltar atrás e editar uma página já existente, ou [[Special:UserLogin|autenticar-se ou criar uma conta]].',
'nocreate-loggedin' => 'Não possui permissão para criar novas páginas.',
'sectioneditnotsupported-title' => 'Edição de seções não é suportada',
'sectioneditnotsupported-text' => 'A edição de seções não é suportada nesta página de edição.',
'permissionserrors' => 'Erros de permissões',
'permissionserrorstext' => 'Não possui permissão para fazer isso, {{PLURAL:$1|pelo seguinte motivo|pelos seguintes motivos}}:',
'permissionserrorstext-withaction' => 'Não possui permissão para $2, {{PLURAL:$1|pelo seguinte motivo|pelos seguintes motivos}}:',
'recreate-moveddeleted-warn' => "'''Aviso: Está a recriar uma página anteriormente eliminada.'''

Verifique se é apropriado continuar a editar esta página.
Para sua conveniência, é apresentado de seguida o registo de eliminação e de movimento da página:",
'moveddeleted-notice' => 'Esta página foi eliminada.
Para referência, é apresentado de seguida o registo de eliminações e de movimento da página.',
'log-fulllog' => 'Ver registo detalhado',
'edit-hook-aborted' => 'A edição foi abortada por um hook.
Não foi dada nenhuma explicação.',
'edit-gone-missing' => 'Não foi possível atualizar a página.
Ela parece ter sido eliminada.',
'edit-conflict' => 'Conflito de edição.',
'edit-no-change' => 'A sua edição foi ignorada, uma vez que o texto não sofreu alterações.',
'postedit-confirmation' => 'A sua edição foi gravada.',
'edit-already-exists' => 'Não foi possível criar uma página nova.
Ela já existia.',
'defaultmessagetext' => 'Texto da mensagem padrão',
'content-failed-to-parse' => 'Falha ao analisar conteúdo $2 para modelo $1:$3',
'invalid-content-data' => 'Dados de conteúdo inválidos',
'content-not-allowed-here' => 'Conteúdo do tipo "$1" não é permitido na página [[$2]]',
'editwarning-warning' => 'Sair desta página fará com que você perca quaisquer alterações feitas por você.
Se você se autenticou, pode desabilitar este aviso na seção "Edição" das suas preferências.',

# Content models
'content-model-wikitext' => 'wikitexto',
'content-model-text' => 'texto simples',
'content-model-javascript' => 'JavaScript',
'content-model-css' => 'CSS',

# Parser/template warnings
'expensive-parserfunction-warning' => "'''Aviso:''' Esta página contém demasiadas chamadas de funções exigentes do analisador sintático.

Devia ter menos de $2 {{PLURAL:$2|chamada|chamadas}}. Neste momento tem $1 {{PLURAL:$1|chamada|chamadas}}.",
'expensive-parserfunction-category' => 'Páginas com demasiadas chamadas a funções exigentes',
'post-expand-template-inclusion-warning' => 'Aviso: O tamanho de inclusão de predefinições é demasiado grande, algumas predefinições não serão incluídas.',
'post-expand-template-inclusion-category' => 'Páginas onde o tamanho de inclusão de predefinições é excedido',
'post-expand-template-argument-warning' => 'Aviso: Esta página contém pelo menos um argumento de predefinição com um tamanho expandido demasiado grande.
Estes argumentos foram omitidos.',
'post-expand-template-argument-category' => 'Páginas com omissão de argumentos para predefinições',
'parser-template-loop-warning' => 'Ciclo de predefinições detectado: [[$1]]',
'parser-template-recursion-depth-warning' => 'Foi excedido o limite da profundidade de recursividade nas predefinições ($1)',
'language-converter-depth-warning' => 'O limite de profundidade do conversor de línguas excedeu a ($1)',
'node-count-exceeded-category' => 'Páginas em que o total de nós é excedido',
'node-count-exceeded-warning' => 'A página excedeu o total de nós',
'expansion-depth-exceeded-category' => 'Páginas em que a profundidade de expansão é excedida',
'expansion-depth-exceeded-warning' => 'A página excedeu a profundidade de expansão',
'parser-unstrip-loop-warning' => 'Foi detectado um ciclo infinito unstrip',
'parser-unstrip-recursion-limit' => 'Limite de recursão do unstrip excedido ($1)',
'converter-manual-rule-error' => 'Erro detetado na regra de conversão de língua manual',

# "Undo" feature
'undo-success' => 'É possível desfazer a edição.
Verifique a comparação abaixo, para se certificar que corresponde ao que pretende fazer.
Depois grave as alterações, para finalizar e desfazer a edição.',
'undo-failure' => 'Não foi possível desfazer a edição por conflito com alterações intermédias.',
'undo-norev' => 'Não foi possível desfazer a edição porque ela não existe ou foi apagada.',
'undo-summary' => 'Desfeita a edição $1 de [[Special:Contributions/$2|$2]] ([[User talk:$2|Discussão]])',
'undo-summary-username-hidden' => 'Desfazer a revisão  $1  por um usuário oculto',

# Account creation failure
'cantcreateaccounttitle' => 'Não é possível criar uma conta',
'cantcreateaccount-text' => "A criação de contas a partir deste endereço IP ('''$1''') foi bloqueada por [[User:$3|$3]].

O motivo apresentado por $3 foi ''$2''",

# History pages
'viewpagelogs' => 'Ver registos para esta página',
'nohistory' => 'Não há histórico de edições para esta página.',
'currentrev' => 'Revisão atual',
'currentrev-asof' => 'Edição atual desde as $1',
'revisionasof' => 'Revisão das $1',
'revision-info' => 'Revisão das $1 por $2',
'previousrevision' => '← Revisão anterior',
'nextrevision' => 'Revisão seguinte →',
'currentrevisionlink' => 'Revisão atual',
'cur' => 'act',
'next' => 'prox',
'last' => 'ant',
'page_first' => 'primeira',
'page_last' => 'última',
'histlegend' => "Seleção de diferenças: use os botões de opção para marcar as versões que deseja comparar.
Pressione 'Enter' ou clique o botão \"{{int:compareselectedversions}}\".<br />
Legenda: '''({{int:cur}})''' = diferenças para a versão atual,
'''({{int:last}})''' = diferenças para a versão anterior,
'''{{int:minoreditletter}}''' = edição menor",
'history-fieldset-title' => 'Navegar pelo histórico',
'history-show-deleted' => 'Somente eliminados',
'histfirst' => 'Mais antigas',
'histlast' => 'Mais novas',
'historysize' => '({{PLURAL:$1|1 byte|$1 bytes}})',
'historyempty' => '(vazia)',

# Revision feed
'history-feed-title' => 'História de revisão',
'history-feed-description' => 'Histórico de edições para esta página nesta wiki',
'history-feed-item-nocomment' => '$1 em $2',
'history-feed-empty' => 'A página solicitada não existe.
Pode ter sido eliminada da wiki ou o nome sido alterado.
Tente [[Special:Search|pesquisar na wiki]] novas páginas relevantes.',

# Revision deletion
'rev-deleted-comment' => '(resumo da edição suprimido)',
'rev-deleted-user' => '(nome de utilizador removido)',
'rev-deleted-event' => '(entrada removida)',
'rev-deleted-user-contribs' => '[nome de utilizador ou IP removido - edição ocultada das contribuições]',
'rev-deleted-text-permission' => "Esta revisão de página foi '''eliminada'''.
Podem existir mais detalhes no [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} registo de eliminações].",
'rev-deleted-text-unhide' => "Esta revisão de página foi '''eliminada'''.
Podem existir mais detalhes no [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} registo de eliminações].
Pode mesmo assim [$1 ver esta edição] se deseja prosseguir.",
'rev-suppressed-text-unhide' => "Esta revisão de página foi '''suprimida'''.
Podem existir mais detalhes no [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} registo de supressões].
Pode mesmo assim [$1 ver esta revisão] se deseja prosseguir.",
'rev-deleted-text-view' => "Esta revisão de página foi '''eliminada'''.
Você pode vê-la; podem existir mais detalhes no [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} registo de eliminações].",
'rev-suppressed-text-view' => "Esta revisão de página foi '''suprimida'''.
Você pode vê-la; podem existir mais detalhes no [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} registo de supressões].",
'rev-deleted-no-diff' => "Não pode ver esta diferença entre revisões porque uma das revisões foi '''eliminada'''.
Podem existir mais detalhes no [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} registo de eliminações].",
'rev-suppressed-no-diff' => "Não pode ver esta diferença entre versões porque uma das revisões foi '''eliminada'''.",
'rev-deleted-unhide-diff' => "Uma das revisões desta diferença entre revisões foi '''eliminada'''.
Podem existir mais detalhes no [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} registo de eliminações].
Pode mesmo assim [$1 ver estas diferenças] se deseja prosseguir.",
'rev-suppressed-unhide-diff' => "Uma das revisões desta diferença entre revisões foi '''suprimida'''.
Podem existir mais detalhes no [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} registo de supressões].
Pode mesmo assim [$1 ver estas diferenças] se deseja prosseguir.",
'rev-deleted-diff-view' => "Uma das revisões desta diferença entre revisões foi '''eliminada'''.
Você pode ver a diferença entre revisões; podem existir mais detalhes no [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} registo de eliminações].",
'rev-suppressed-diff-view' => "Uma das revisões desta diferença entre revisões foi '''suprimida'''.
Você pode ver a diferença entre revisões; podem existir mais detalhes no [{{fullurl:{{#Special:Log}}/suppress|page={{FULLPAGENAMEE}}}} registo de supressões].",
'rev-delundel' => 'mostrar/esconder',
'rev-showdeleted' => 'mostrar',
'revisiondelete' => 'Eliminar/restaurar edições',
'revdelete-nooldid-title' => 'Edição de destino inválida',
'revdelete-nooldid-text' => 'Ocorreu uma das seguintes: não especificou a revisão (ou revisões) de destino para realizar esta função, a revisão que especificou não existe, ou está tentando ocultar a revisão atual.',
'revdelete-nologtype-title' => 'Tipo de registo não especificado',
'revdelete-nologtype-text' => 'Não especificou um tipo de registo sobre o qual será executada esta operação.',
'revdelete-nologid-title' => 'Entrada de registo inválida',
'revdelete-nologid-text' => 'Ou não especificou um evento do registo sobre o qual executar esta função, ou a entrada que especificou não existe.',
'revdelete-no-file' => 'O ficheiro especificado não existe.',
'revdelete-show-file-confirm' => 'Tem a certeza de que quer ver uma revisão eliminada do ficheiro "<nowiki>$1</nowiki>" de $2 às $3?',
'revdelete-show-file-submit' => 'Sim',
'revdelete-selected' => "'''{{PLURAL:$2|Edição selecionada|Edições selecionadas}} de [[:$1]]:'''",
'logdelete-selected' => "'''{{PLURAL:$1|Evento do registo selecionado|Eventos do registo selecionados}}:'''",
'revdelete-text' => "'''Edições e eventos eliminados continuarão a aparecer no histórico e registos da página, mas partes do seu conteúdo estarão inacessíveis ao público.'''
Outros administradores da {{SITENAME}} continuarão a poder aceder ao conteúdo escondido e podem restaurá-lo novamente através desta mesma interface, a menos que restrições adicionais sejam definidas.",
'revdelete-confirm' => 'Por favor confirme que pretende executar esta operação, que compreende as suas consequências e que o faz em concordância com as [[{{MediaWiki:Policy-url}}|políticas e recomendações]].',
'revdelete-suppress-text' => "A supressão '''só''' deverá ser usada nos seguintes casos:
* Informação potencialmente caluniosa, difamatória ou injuriosa
* Informação pessoal imprópria
*: ''endereços de domicílio e números de telefone, números da segurança social, etc''",
'revdelete-legend' => 'Definir restrições de visibilidade',
'revdelete-hide-text' => 'Ocultar texto da edição',
'revdelete-hide-image' => 'Ocultar conteúdo do ficheiro',
'revdelete-hide-name' => 'Ocultar operação e destino',
'revdelete-hide-comment' => 'Ocultar resumo da edição',
'revdelete-hide-user' => 'Ocultar nome de utilizador/IP',
'revdelete-hide-restricted' => 'Ocultar dados dos administradores e de todos os outros',
'revdelete-radio-same' => '(manter)',
'revdelete-radio-set' => 'Sim',
'revdelete-radio-unset' => 'Não',
'revdelete-suppress' => 'Ocultar dados dos administradores e de todos os outros',
'revdelete-unsuppress' => 'Remover restrições das revisões restauradas',
'revdelete-log' => 'Motivo:',
'revdelete-submit' => 'Aplicar {{PLURAL:$1|à revisão selecionada|às revisões selecionadas}}',
'revdelete-success' => "'''A visibilidade da revisão foi atualizada.'''",
'revdelete-failure' => "'''A visibilidade da revisão não foi atualizada:'''
$1",
'logdelete-success' => "'''A visibilidade da entrada do registo foi definida.'''",
'logdelete-failure' => "'''Não foi possível definir a visibilidade da entrada do registo:'''
$1",
'revdel-restore' => 'Alterar visibilidade',
'revdel-restore-deleted' => 'revisões eliminadas',
'revdel-restore-visible' => 'revisões visíveis',
'pagehist' => 'Histórico da página',
'deletedhist' => 'Histórico de eliminações',
'revdelete-hide-current' => 'Erro ao ocultar o item datado de $2, $1: esta é a revisão atual.
Não pode ser ocultada.',
'revdelete-show-no-access' => 'Erro ao mostrar o item datado de $2, $1: este item foi marcado como "restrito".
Não tem acesso.',
'revdelete-modify-no-access' => 'Erro ao modificar o item datado de $2, $1: este item foi marcado como "restrito".
Não tem acesso.',
'revdelete-modify-missing' => 'Erro ao modificar o item ID $1: não existe na base de dados!',
'revdelete-no-change' => "'''Aviso:''' a revisão com data de $2, $1 já tem as configurações de visibilidade solicitadas.",
'revdelete-concurrent-change' => 'Erro ao modificar o item com data/hora $2, $1: o seu estado parece ter sido alterado por outra pessoa enquanto você tentava modificá-lo.
Verifique os registos, por favor.',
'revdelete-only-restricted' => 'Erro ao ocultar o item de $2 às $1: não pode impedir que os itens sejam vistos pelos administradores sem selecionar também uma das outras opções de visibilidade.',
'revdelete-reason-dropdown' => '*Razões comuns para eliminação
** Violação de direitos de autor
** Comentário ou informações pessoais inapropriados
** Nome de utilizador inapropriado
** Informações potencialmente difamatórias',
'revdelete-otherreason' => 'Outro/motivo adicional:',
'revdelete-reasonotherlist' => 'Outro motivo',
'revdelete-edit-reasonlist' => 'Editar motivos de eliminação',
'revdelete-offender' => 'Autor da revisão:',

# Suppression log
'suppressionlog' => 'Registo de supressões',
'suppressionlogtext' => 'Abaixo está uma lista das eliminações e bloqueios envolvendo conteúdo ocultado para administradores.
Veja a [[Special:BlockList|lista de bloqueios]] para uma lista de banimentos e bloqueios em efeito neste momento.',

# History merging
'mergehistory' => 'Fundir histórico de páginas',
'mergehistory-header' => 'Esta página permite fundir o histórico de revisões de uma página no de outra.
Certifique-se de que esta alteração manterá a continuidade histórica da página.',
'mergehistory-box' => 'Fundir edições de duas páginas:',
'mergehistory-from' => 'Página de origem:',
'mergehistory-into' => 'Página de destino:',
'mergehistory-list' => 'Histórico de edições fundíveis',
'mergehistory-merge' => 'As seguintes edições de [[:$1]] podem ser fundidas em [[:$2]].
Usando os botões de opção, pode escolher fundir apenas as edições até àquela que marcar.
Note que, se usar os links de navegação, os botões de opção voltarão aos valores originais.',
'mergehistory-go' => 'Mostrar edições que podem ser fundidas',
'mergehistory-submit' => 'Fundir edições',
'mergehistory-empty' => 'Não existem revisões fundíveis.',
'mergehistory-success' => 'Foram fundidas $3 {{PLURAL:$3|edição|edições}} de [[:$1]] em [[:$2]].',
'mergehistory-fail' => 'Não foi possível fundir os históricos; verifique a página e os parâmetros de tempo, por favor.',
'mergehistory-no-source' => 'A página de origem $1 não existe.',
'mergehistory-no-destination' => 'A página de destino $1 não existe.',
'mergehistory-invalid-source' => 'A página de origem precisa ser um título válido.',
'mergehistory-invalid-destination' => 'A página de destino precisa ser um título válido.',
'mergehistory-autocomment' => '[[:$1]] fundida com [[:$2]]',
'mergehistory-comment' => '[[:$1]] fundida com [[:$2]]: $3',
'mergehistory-same-destination' => 'As páginas de origem e de destino não podem ser a mesma',
'mergehistory-reason' => 'Motivo:',

# Merge log
'mergelog' => 'Registo de fusão de históricos',
'pagemerge-logentry' => '[[$1]] foi fundida em [[$2]] (até a edição $3)',
'revertmerge' => 'Desfazer fusão',
'mergelogpagetext' => 'Segue-se um registo das mais recentes fusões de históricos de páginas.',

# Diffs
'history-title' => 'Histórico de edições de "$1"',
'difference-title' => 'Diferenças entre edições de "$1"',
'difference-title-multipage' => 'Diferenças entre as páginas "$1" e "$2"',
'difference-multipage' => '(Diferenças entre páginas)',
'lineno' => 'Linha $1:',
'compareselectedversions' => 'Comparar as versões selecionadas',
'showhideselectedversions' => 'Mostrar/ocultar versões selecionadas',
'editundo' => 'desfazer',
'diff-empty' => '(Sem diferenças)',
'diff-multi' => '({{PLURAL:$1|Uma edição intermédia|$1 edições intermédias}} de {{PLURAL:$2|um utilizador|$2 utilizadores}} {{PLURAL:$1|não apresentada|não apresentadas}})',
'diff-multi-manyusers' => '({{PLURAL:$1|Uma edição intermédia|$1 edições intermédias}} de mais de {{PLURAL:$2|um utilizador|$2 utilizadores}} não {{PLURAL:$1|apresentada|apresentadas}})',
'difference-missing-revision' => '{{PLURAL:$2|Uma revisão|$2 revisões}} desta diferença ($1) não {{PLURAL:$2|foi encontrada|foram encontradas}}.

Isto é geralmente causado por seguir um link de histórico desatualizado para uma página que foi eliminada.
Os detalhes podem ser encontrados no [{{fullurl:{{#Special:Log}}/delete|page={{FULLPAGENAMEE}}}} registo de eliminação].',

# Search results
'searchresults' => 'Resultados da pesquisa',
'searchresults-title' => 'Resultados da pesquisa de "$1"',
'searchresulttext' => 'Para mais informações sobre pesquisas na {{SITENAME}}, consulte [[{{MediaWiki:Helppage}}|{{int:help}}]].',
'searchsubtitle' => 'Pesquisou \'\'\'[[:$1]]\'\'\' ([[Special:Prefixindex/$1|páginas iniciadas por "$1"]]{{int:pipe-separator}}[[Special:WhatLinksHere/$1|páginas que apontam para "$1"]])',
'searchsubtitleinvalid' => 'Pesquisou "$1"',
'toomanymatches' => 'Foram devolvidos demasiados resultados; tente outro termo de pesquisa, por favor',
'titlematches' => 'Resultados no título das páginas',
'notitlematches' => 'Nenhum título de página coincide com o termo pesquisado',
'textmatches' => 'Resultados no conteúdo das páginas',
'notextmatches' => 'Não foi possível localizar, no conteúdo das páginas, o termo pesquisado',
'prevn' => '{{PLURAL:$1|anterior|$1 anteriores}}',
'nextn' => '{{PLURAL:$1|posterior|$1 posteriores}}',
'prevn-title' => '$1 {{PLURAL:$1|resultado anterior|resultados anteriores}}',
'nextn-title' => '{{PLURAL:$1|próximo|próximos}} $1 {{PLURAL:$1|resultado|resultados}}',
'shown-title' => 'Mostrar $1 {{PLURAL:$1|resultado|resultados}} por página',
'viewprevnext' => 'Ver ($1 {{int:pipe-separator}} $2) ($3).',
'searchmenu-legend' => 'Opções de pesquisa',
'searchmenu-exists' => "'''Há uma página com o nome \"[[:\$1]]\" nesta wiki'''",
'searchmenu-new' => "'''Crie a página \"[[:\$1]]\" nesta wiki!'''",
'searchmenu-prefix' => '[[Special:PrefixIndex/$1|Navegar as páginas com este prefixo]]',
'searchprofile-articles' => 'Páginas de conteúdo',
'searchprofile-project' => 'Páginas de ajuda e de projeto',
'searchprofile-images' => 'Multimédia',
'searchprofile-everything' => 'Todas',
'searchprofile-advanced' => 'Personalizar',
'searchprofile-articles-tooltip' => 'Pesquisar em $1',
'searchprofile-project-tooltip' => 'Pesquisar em $1',
'searchprofile-images-tooltip' => 'Pesquisar ficheiros',
'searchprofile-everything-tooltip' => 'Pesquisar em todo o conteúdo (incluindo páginas de discussão)',
'searchprofile-advanced-tooltip' => 'Personalizar os espaços nominais onde pesquisar',
'search-result-size' => '$1 ({{PLURAL:$2|1 palavra|$2 palavras}})',
'search-result-category-size' => '{{PLURAL:$1|1 membro|$1 membros}} ({{PLURAL:$2|1 subcategoria|$2 subcategorias}}, {{PLURAL:$3|1 ficheiro|$3 ficheiros}})',
'search-result-score' => 'Relevancia: $1%',
'search-redirect' => '(redirecionamento de $1)',
'search-section' => '(seção $1)',
'search-suggest' => 'Será que queria dizer: $1',
'search-interwiki-caption' => 'Projetos irmãos',
'search-interwiki-default' => 'Resultados de $1:',
'search-interwiki-more' => '(mais)',
'search-relatedarticle' => 'Relacionado',
'mwsuggest-disable' => 'Desativar sugestões de pesquisa',
'searcheverything-enable' => 'Pesquisar em todos os espaços nominais',
'searchrelated' => 'relacionados',
'searchall' => 'todos',
'showingresults' => "{{PLURAL:$1|É apresentado '''um''' resultado|São apresentados até '''$1''' resultados}} abaixo{{PLURAL:$1||, começando pelo '''$2'''º}}.",
'showingresultsnum' => "{{PLURAL:$3|É apresentado '''um''' resultado|São apresentados '''$3''' resultados}} abaixo{{PLURAL:$3||, começando pelo '''$2'''º}}.",
'showingresultsheader' => "{{PLURAL:$5|Resultado '''$1''' de '''$3'''|Resultados '''$1–$2''' de '''$3'''}} para '''$4'''",
'nonefound' => "'''Nota''': Por omissão, só alguns dos espaços nominais são pesquisados.
Tente usar o prefixo ''all:'' para pesquisar todo o conteúdo (incluindo páginas de discussão, predefinições, etc.), ou use como prefixo o espaço nominal desejado.",
'search-nonefound' => 'A pesquisa não produziu resultados.',
'powersearch' => 'Pesquisa avançada',
'powersearch-legend' => 'Pesquisa avançada',
'powersearch-ns' => 'Pesquisar nos espaços nominais:',
'powersearch-redir' => 'Listar redirecionamentos',
'powersearch-field' => 'Pesquisar',
'powersearch-togglelabel' => 'Marcar:',
'powersearch-toggleall' => 'Todos',
'powersearch-togglenone' => 'Nenhum',
'search-external' => 'Pesquisa externa',
'searchdisabled' => 'Foi impossibilitada a realização de pesquisas na {{SITENAME}}.
Entretanto, pode realizar pesquisas através do Google.
Note, no entanto, que a indexação da {{SITENAME}} neste motor de busca pode estar desatualizada.',
'search-error' => 'Um erro ocorreu enquanto se efectuava a busca: $1',

# Preferences page
'preferences' => 'Preferências',
'mypreferences' => 'Preferências',
'prefs-edits' => 'Número de edições:',
'prefsnologin' => 'Não autenticado',
'prefsnologintext' => 'Precisa de estar <span class="plainlinks">[{{fullurl:{{#Special:UserLogin}}|returnto=$1}} autenticado]</span> para definir as suas preferências.',
'changepassword' => 'Alterar palavra-chave',
'prefs-skin' => 'Tema',
'skin-preview' => 'Antever tema',
'datedefault' => 'Sem preferência',
'prefs-beta' => 'Funcionalidades beta',
'prefs-datetime' => 'Data e hora',
'prefs-labs' => 'Funcionalidades dos laboratórios',
'prefs-user-pages' => 'Páginas de utilizador',
'prefs-personal' => 'Dados do utilizador',
'prefs-rc' => 'Mudanças recentes',
'prefs-watchlist' => 'Páginas vigiadas',
'prefs-watchlist-days' => 'Dias a mostrar nas mudanças às páginas vigiadas:',
'prefs-watchlist-days-max' => 'Máximo: $1 {{PLURAL:$1|dia|dias}}',
'prefs-watchlist-edits' => 'Número de edições a mostrar na listagem expandida:',
'prefs-watchlist-edits-max' => 'Máximo: 1000',
'prefs-watchlist-token' => 'Chave secreta da lista de páginas vigiadas:',
'prefs-misc' => 'Diversos',
'prefs-resetpass' => 'Alterar palavra-chave',
'prefs-changeemail' => 'Alterar correio electrónico',
'prefs-setemail' => 'Definir um endereço de correio electrónico',
'prefs-email' => 'Opções do correio electrónico',
'prefs-rendering' => 'Aparência',
'saveprefs' => 'Gravar',
'resetprefs' => 'Eliminar as alterações que não foram gravadas',
'restoreprefs' => 'Repor todas as configurações padrão (em todas as secções)',
'prefs-editing' => 'Edição',
'rows' => 'Linhas:',
'columns' => 'Colunas:',
'searchresultshead' => 'Pesquisar',
'resultsperpage' => 'Resultados por página:',
'stub-threshold' => 'Links para páginas curtas terão <a href="#" class="stub">este formato</a> se elas ocuparem menos de (bytes):',
'stub-threshold-disabled' => 'Desativado',
'recentchangesdays' => 'Dias a apresentar nas mudanças recentes:',
'recentchangesdays-max' => 'Máximo: $1 {{PLURAL:$1|dia|dias}}',
'recentchangescount' => 'Número de edições a apresentar por omissão:',
'prefs-help-recentchangescount' => 'Inclui mudanças recentes, histórico de páginas e registos.',
'prefs-help-watchlist-token2' => "Esta é a chave secreta para o ''feed'' RSS da sua lista de páginas vigiadas.
Qualquer pessoa que conheça a chave será capaz de ler a sua lista de páginas vigiadas, por isso não a divulgue.
[[Special:ResetTokens|Clique aqui para redefini-la]].",
'savedprefs' => 'As suas preferências foram gravadas.',
'timezonelegend' => 'Fuso horário:',
'localtime' => 'Hora local:',
'timezoneuseserverdefault' => 'Usar padrão da wiki ($1)',
'timezoneuseoffset' => 'Outro (especificar diferença)',
'timezoneoffset' => 'Diferença horária¹:',
'servertime' => 'Hora do servidor:',
'guesstimezone' => 'Preencher a partir do browser',
'timezoneregion-africa' => 'África',
'timezoneregion-america' => 'América',
'timezoneregion-antarctica' => 'Antártida',
'timezoneregion-arctic' => 'Ártico',
'timezoneregion-asia' => 'Ásia',
'timezoneregion-atlantic' => 'Oceano Atlântico',
'timezoneregion-australia' => 'Austrália',
'timezoneregion-europe' => 'Europa',
'timezoneregion-indian' => 'Oceano Índico',
'timezoneregion-pacific' => 'Oceano Pacífico',
'allowemail' => 'Aceitar correio electrónico de outros utilizadores',
'prefs-searchoptions' => 'Pesquisa',
'prefs-namespaces' => 'Espaços nominais',
'defaultns' => 'Por omissão, pesquisar nestes espaços nominais:',
'default' => 'padrão',
'prefs-files' => 'Ficheiros',
'prefs-custom-css' => 'CSS personalizada',
'prefs-custom-js' => 'JS personalizado',
'prefs-common-css-js' => 'CSS/JS partilhado por todos os temas:',
'prefs-reset-intro' => 'Pode usar esta página para repor as configurações padrão das preferências.
As suas preferências serão modificadas para os valores predefinidos do site.
Esta operação não pode ser desfeita.',
'prefs-emailconfirm-label' => 'Confirmação do endereço:',
'youremail' => 'Correio electrónico:',
'username' => 'Nome de {{GENDER:$1|utilizador|utilizadora}}:',
'uid' => 'Identificação de {{GENDER:$1|utilizador|utilizadora}}:',
'prefs-memberingroups' => '{{GENDER:$2|Membro}} {{PLURAL:$1|do grupo|dos grupos}}:',
'prefs-registration' => 'Hora de registo:',
'yourrealname' => 'Nome verdadeiro:',
'yourlanguage' => 'Língua:',
'yourvariant' => 'Variante da língua de conteúdo:',
'prefs-help-variant' => 'A tua variante preferida ou ortografia para mostrar no conteúdo das páginas desta wiki.',
'yournick' => 'Assinatura:',
'prefs-help-signature' => 'Ao inserir comentários em páginas de discussão, assine-os colocando quatro tiles "<nowiki>~~~~</nowiki>" no fim dos comentários. Ao gravar, estes serão convertidos na sua assinatura mais a data e a hora da edição.',
'badsig' => 'Assinatura inválida; verifique o código HTML utilizado.',
'badsiglength' => 'A sua assinatura é demasiado longa.
Não deverá conter mais de $1 {{PLURAL:$1|carácter|caracteres}}.',
'yourgender' => 'Como prefere ser descrito?',
'gender-unknown' => 'Prefiro não dizer',
'gender-male' => 'Ele edita páginas wiki',
'gender-female' => 'Ela edita páginas wiki',
'prefs-help-gender' => 'Esta preferência é opcional.
O software usa o seu valor para o endereçar e para o mencionar a outros usando o género gramatical apropriado.
Esta informação será pública.',
'email' => 'Correio electrónico',
'prefs-help-realname' => 'O fornecimento do nome verdadeiro é opcional.
Se optar por revelá-lo, ele será utilizado para atribuir-lhe crédito pelo seu trabalho.',
'prefs-help-email' => 'Opcional: o endereço de correio electrónico é opcional, mas será necessário para reiniciar a palavra-chave caso esqueça a antiga.',
'prefs-help-email-others' => 'Também pode optar por permitir que outros entrem em contacto consigo por correio electrónico, através de um link nas suas páginas de utilizador ou de discussão, sem revelar o seu endereço de correio electrónico.',
'prefs-help-email-required' => 'É necessário o endereço de correio electrónico.',
'prefs-info' => 'Informações básicas',
'prefs-i18n' => 'Internacionalização',
'prefs-signature' => 'Assinatura',
'prefs-dateformat' => 'Formato de data',
'prefs-timeoffset' => 'Desvio horário',
'prefs-advancedediting' => 'Opções gerais',
'prefs-preview' => 'Antevisão',
'prefs-advancedrc' => 'Opções avançadas',
'prefs-advancedrendering' => 'Opções avançadas',
'prefs-advancedsearchoptions' => 'Opções avançadas',
'prefs-advancedwatchlist' => 'Opções avançadas',
'prefs-displayrc' => 'Opções de visionamento',
'prefs-displaysearchoptions' => 'Opções de apresentação',
'prefs-displaywatchlist' => 'Opções de apresentação',
'prefs-tokenwatchlist' => 'Chave',
'prefs-diffs' => 'Diferenças',

# User preference: email validation using jQuery
'email-address-validity-valid' => 'Parece válido',
'email-address-validity-invalid' => 'Endereço válido necessário!',

# User rights
'userrights' => 'Privilégios dos utilizadores',
'userrights-lookup-user' => 'Gerir grupos de utilizadores',
'userrights-user-editname' => 'Introduza um nome de utilizador:',
'editusergroup' => 'Editar grupos do utilizador',
'editinguser' => "A modificar os privilégios do utilizador '''[[User:$1|$1]]''' $2",
'userrights-editusergroup' => 'Editar grupos do utilizador',
'saveusergroups' => 'Gravar grupos do utilizador',
'userrights-groupsmember' => 'Membro de:',
'userrights-groupsmember-auto' => 'Membro implícito de:',
'userrights-groups-help' => 'É possível alterar os grupos a que este utilizador pertence:
* Uma caixa de seleção marcada significa que o utilizador se encontra no grupo.
* Uma caixa de seleção desmarcada significa que o utilizador não se encontra no grupo.
* Um asterisco (*) indica que não pode remover o grupo depois de o adicionar, ou vice-versa.',
'userrights-reason' => 'Motivo:',
'userrights-no-interwiki' => 'Não tem permissões para alterar os privilégios de utilizadores noutras wikis.',
'userrights-nodatabase' => 'A base de dados $1 não existe ou não é uma base de dados local.',
'userrights-nologin' => 'Precisa [[Special:UserLogin|autenticar-se]] com uma conta de administrador para atribuir privilégios aos utilizadores.',
'userrights-notallowed' => 'A sua conta não tem permissão para adicionar ou remover privilégios a utilizadores.',
'userrights-changeable-col' => 'Grupos que pode alterar',
'userrights-unchangeable-col' => 'Grupos que não pode alterar',
'userrights-conflict' => 'Conflito com os privilégios dos utilizadores! Por favor, aplique as suas mudanças novamente.',
'userrights-removed-self' => 'Você removeu com sucesso os seus privilégios. Como resultado disso, já não consegue aceder a esta página.',

# Groups
'group' => 'Grupo:',
'group-user' => 'Utilizadores',
'group-autoconfirmed' => 'Utilizadores auto-confirmados',
'group-bot' => 'Robôs',
'group-sysop' => 'Administradores',
'group-bureaucrat' => 'Burocratas',
'group-suppress' => 'Supervisores',
'group-all' => '(todos)',

'group-user-member' => '{{GENDER:$1|utilizador|utilizadora}}',
'group-autoconfirmed-member' => '{{GENDER:$1|utilizador autoconfirmado|utilizadora autoconfirmada}}',
'group-bot-member' => '{{GENDER:$1|robô}}',
'group-sysop-member' => '{{GENDER:$1|administrador|administradora}}',
'group-bureaucrat-member' => '{{GENDER:$1|burocrata}}',
'group-suppress-member' => '{{GENDER:$1|supressor|supressora}}',

'grouppage-user' => '{{ns:project}}:Utilizadores',
'grouppage-autoconfirmed' => '{{ns:project}}:Auto-confirmados',
'grouppage-bot' => '{{ns:project}}:Robôs',
'grouppage-sysop' => '{{ns:project}}:Administradores',
'grouppage-bureaucrat' => '{{ns:project}}:Burocratas',
'grouppage-suppress' => '{{ns:project}}:Supervisores',

# Rights
'right-read' => 'Ler páginas',
'right-edit' => 'Editar páginas',
'right-createpage' => 'Criar páginas (que não sejam páginas de discussão)',
'right-createtalk' => 'Criar páginas de discussão',
'right-createaccount' => 'Criar novas contas de utilizador',
'right-minoredit' => 'Marcar edições como menores',
'right-move' => 'Mover páginas',
'right-move-subpages' => 'Mover páginas com as suas subpáginas',
'right-move-rootuserpages' => 'Mover páginas raiz de utilizadores',
'right-movefile' => 'Mover ficheiros',
'right-suppressredirect' => 'Não criar um redirecionamento do nome antigo quando uma página é movida',
'right-upload' => 'Carregar ficheiros',
'right-reupload' => 'Sobrescrever um ficheiro existente',
'right-reupload-own' => 'Sobrescrever um ficheiro existente carregado pelo mesmo utilizador',
'right-reupload-shared' => 'Sobrescrever localmente ficheiros no repositório partilhado de imagens',
'right-upload_by_url' => 'Carregar um ficheiro de um endereço URL',
'right-purge' => "Purgar a ''cache'' de uma página no servidor sem confirmação",
'right-autoconfirmed' => 'Editar páginas semi-protegidas',
'right-bot' => 'Ser tratado como um processo automatizado',
'right-nominornewtalk' => 'Não despoletar o aviso de mensagens novas ao fazer edições menores a páginas de discussão',
'right-apihighlimits' => "Usar limites superiores nas consultas ''(queries)'' via API",
'right-writeapi' => 'Usar a API de escrita',
'right-delete' => 'Eliminar páginas',
'right-bigdelete' => 'Eliminar páginas com histórico grande',
'right-deletelogentry' => 'Eliminar e restaurar entradas específicas de registos',
'right-deleterevision' => 'Eliminar e restaurar edições específicas de páginas',
'right-deletedhistory' => 'Ver entradas de histórico eliminadas, sem o texto associado',
'right-deletedtext' => 'Ver texto eliminado e mudanças entre revisões eliminadas',
'right-browsearchive' => 'Pesquisar páginas eliminadas',
'right-undelete' => 'Restaurar uma página',
'right-suppressrevision' => 'Rever e restaurar revisões ocultadas dos administradores',
'right-suppressionlog' => 'Ver registos privados',
'right-block' => 'Impedir outros utilizadores de editarem',
'right-blockemail' => 'Impedir um utilizador de enviar correio electrónico',
'right-hideuser' => 'Bloquear um nome de utilizador, escondendo-o do público',
'right-ipblock-exempt' => 'Contornar bloqueios de IP, bloqueios automáticos e bloqueios de gamas de IPs',
'right-proxyunbannable' => 'Contornar bloqueios automáticos de proxies',
'right-unblockself' => 'Desbloquearem-se a si próprios',
'right-protect' => 'Mudar níveis de proteção e editar páginas protegidas em cascata',
'right-editprotected' => 'Editar páginas protegidas como "{{int:protect-level-sysop}}"',
'right-editinterface' => 'Editar a interface de utilizador',
'right-editusercssjs' => 'Editar os ficheiros CSS e JS de outros utilizadores',
'right-editusercss' => 'Editar os ficheiros CSS de outros utilizadores',
'right-edituserjs' => 'Editar os ficheiros JS de outros utilizadores',
'right-editmyusercss' => 'Editar os seus próprios ficheiros CSS de utilizador',
'right-editmyuserjs' => 'Editar os seus próprios ficheiros JavaScript de utilizador',
'right-rollback' => 'Reverter rapidamente as edições do último utilizador que editou uma página em particular',
'right-markbotedits' => 'Marcar edições revertidas como edições de bot',
'right-noratelimit' => 'Não ser afetado pelos limites de velocidade de operação',
'right-import' => 'Importar páginas de outras wikis',
'right-importupload' => 'Importar páginas de um ficheiro xml',
'right-patrol' => 'Marcar edições de outros utilizadores como patrulhadas',
'right-autopatrol' => 'Ter edições automaticamente marcadas como patrulhadas',
'right-patrolmarks' => 'Usar funcionalidades de patrulhagem das mudanças recentes',
'right-unwatchedpages' => 'Ver uma lista de páginas não vigiadas',
'right-mergehistory' => 'Fundir o histórico de edições de páginas',
'right-userrights' => 'Editar todos os privilégios de utilizador',
'right-userrights-interwiki' => 'Editar privilégios de utilizadores noutras wikis',
'right-siteadmin' => 'Bloquear e desbloquear a base de dados',
'right-override-export-depth' => 'Exportar páginas incluindo páginas ligadas até uma profundidade de 5',
'right-sendemail' => 'Enviar correio electrónico a outros utilizadores',
'right-passwordreset' => 'Ver emails de reposição de palavras-chave',

# Special:Log/newusers
'newuserlogpage' => 'Registo de criação de utilizadores',
'newuserlogpagetext' => 'Este é um registo de novas contas de utilizador',

# User rights log
'rightslog' => 'Registo de privilégios de utilizador',
'rightslogtext' => 'Este é um registo de mudanças nos privilégios dos utilizadores.',

# Associated actions - in the sentence "You do not have permission to X"
'action-read' => 'ler esta página',
'action-edit' => 'editar esta página',
'action-createpage' => 'criar páginas',
'action-createtalk' => 'criar páginas de discussão',
'action-createaccount' => 'criar esta conta de utilizador',
'action-minoredit' => 'marcar esta edição como uma edição menor',
'action-move' => 'mover esta página',
'action-move-subpages' => 'mover esta página e as respectivas subpáginas',
'action-move-rootuserpages' => 'mover páginas raiz de utilizadores',
'action-movefile' => 'mover este ficheiro',
'action-upload' => 'enviar este ficheiro',
'action-reupload' => 'sobrepor este ficheiro existente',
'action-reupload-shared' => 'sobrepor este ficheiro num repositório partilhado',
'action-upload_by_url' => 'enviar este ficheiro através de uma URL',
'action-writeapi' => 'utilizar o modo de escrita da API',
'action-delete' => 'eliminar esta página',
'action-deleterevision' => 'eliminar esta edição',
'action-deletedhistory' => 'ver o histórico de edições eliminadas desta página',
'action-browsearchive' => 'pesquisar páginas eliminadas',
'action-undelete' => 'restaurar esta página',
'action-suppressrevision' => 'rever e restaurar esta edição oculta',
'action-suppressionlog' => 'ver este registo privado',
'action-block' => 'impedir este utilizador de editar',
'action-protect' => 'alterar os níveis de proteção desta página',
'action-rollback' => 'reverter rapidamente as edições do último utilizador que editou uma dada página',
'action-import' => 'importar esta página a partir de outra wiki',
'action-importupload' => 'importar esta página a partir de um ficheiro xml',
'action-patrol' => 'marcar as edições de outros utilizadores como patrulhadas',
'action-autopatrol' => 'marcar como patrulhadas as suas próprias edições',
'action-unwatchedpages' => 'ver a lista de páginas não-vigiadas',
'action-mergehistory' => 'fundir o histórico de edições desta página',
'action-userrights' => 'editar os privilégios de utilizadores',
'action-userrights-interwiki' => 'editar privilégios de utilizadores de outras wikis',
'action-siteadmin' => 'bloquear ou desbloquear a base de dados',
'action-sendemail' => 'enviar e-mails',

# Recent changes
'nchanges' => '$1 {{PLURAL:$1|alteração|alterações}}',
'recentchanges' => 'Mudanças recentes',
'recentchanges-legend' => 'Opções das mudanças recentes',
'recentchanges-summary' => 'Acompanhe nesta página as mudanças mais recentes da wiki.',
'recentchanges-noresult' => 'Nenhuma alteração correspondente a esses critérios foi realizada durante o período selecionado.',
'recentchanges-feed-description' => "Acompanhe neste ''feed'' as mudanças mais recentes da wiki.",
'recentchanges-label-newpage' => 'Esta edição criou uma página nova',
'recentchanges-label-minor' => 'Esta é uma edição menor',
'recentchanges-label-bot' => 'Esta edição foi feita por um robô',
'recentchanges-label-unpatrolled' => 'Esta edição ainda não foi patrulhada',
'rcnote' => "A seguir {{PLURAL:$1|está listada '''uma''' alteração ocorrida|estão listadas '''$1''' alterações ocorridas}} {{PLURAL:$2|no último dia|nos últimos '''$2''' dias}}, a partir das $5 de $4.",
'rcnotefrom' => 'Abaixo estão as mudanças desde <b>$2</b> (mostradas até <b>$1</b>).',
'rclistfrom' => 'Mostrar as novas mudanças a partir das $1',
'rcshowhideminor' => '$1 edições menores',
'rcshowhidebots' => '$1 robôs',
'rcshowhideliu' => '$1 utilizadores registados',
'rcshowhideanons' => '$1 utilizadores anónimos',
'rcshowhidepatr' => '$1 edições patrulhadas',
'rcshowhidemine' => '$1 as minhas edições',
'rclinks' => 'Mostrar as últimas $1 mudanças nos últimos $2 dias<br />$3',
'diff' => 'dif',
'hist' => 'his',
'hide' => 'Esconder',
'show' => 'Mostrar',
'minoreditletter' => 'm',
'newpageletter' => 'N',
'boteditletter' => 'b',
'number_of_watching_users_pageview' => '[{{PLURAL:$1|$1 utilizador|$1 utilizadores}} a vigiar]',
'rc_categories' => 'Limitar às categorias (separar com "|")',
'rc_categories_any' => 'Qualquer',
'rc-change-size-new' => '$1 {{PLURAL:$1|byte|bytes}} após mudança',
'newsectionsummary' => '/* $1 */ nova seção',
'rc-enhanced-expand' => 'Mostrar detalhes',
'rc-enhanced-hide' => 'Esconder detalhes',
'rc-old-title' => 'originalmente criado como "$1"',

# Recent changes linked
'recentchangeslinked' => 'Alterações relacionadas',
'recentchangeslinked-feed' => 'Alterações relacionadas',
'recentchangeslinked-toolbox' => 'Alterações relacionadas',
'recentchangeslinked-title' => 'Alterações relacionadas com "$1"',
'recentchangeslinked-summary' => "Lista das mudanças recentes a todas as páginas para as quais a página fornecida contém links (ou de todas as que pertencem à categoria fornecida).
As suas [[Special:Watchlist|páginas vigiadas]] aparecem a '''negrito'''.",
'recentchangeslinked-page' => 'Nome da página:',
'recentchangeslinked-to' => 'Inversamente, mostrar mudanças às páginas que contêm links para esta',

# Upload
'upload' => 'Carregar ficheiro',
'uploadbtn' => 'Carregar ficheiro',
'reuploaddesc' => 'Cancelar o envio e voltar ao formulário de carregamento',
'upload-tryagain' => 'Submeta a descrição do ficheiro modificado',
'uploadnologin' => 'Não autenticado',
'uploadnologintext' => 'Tem de $1 para enviar ficheiros.',
'upload_directory_missing' => 'O diretório de carregamento de ficheiros ($1) não existe e o servidor de internet não conseguiu criá-lo.',
'upload_directory_read_only' => 'O servidor de internet não possui permissão de escrita no diretório de carregamento de ficheiros ($1).',
'uploaderror' => 'Erro ao carregar',
'upload-recreate-warning' => "'''Aviso: Um ficheiro com esse nome foi eliminado ou movido.'''

Para sua conveniência, é apresentado de seguida o registo de eliminação e de movimento da página:",
'uploadtext' => "Utilize o formulário abaixo para fazer upload de ficheiros novos.
Para ver ou pesquisar ficheiros anteriormente enviados, consulte a [[Special:FileList|lista de ficheiros]].
Os reenvios de um ficheiro são também registrados no [[Special:Log/upload|registro de uploads]] e as eliminações no [[Special:Log/delete|registro de eliminações]].

Para utilizar um ficheiro numa página, depois de ter feito o upload, insira um link com um dos seguintes formatos:
* '''<code><nowiki>[[</nowiki>{{ns:file}}<nowiki>:ficheiro.jpg]]</nowiki></code>''' para mostrar uma imagem nas suas dimensões originais;
* '''<code><nowiki>[[</nowiki>{{ns:file}}<nowiki>:ficheiro.png|200px|thumb|left|texto]]</nowiki></code>''' para mostrar uma imagem com a dimensão horizontal de 200 pixels, dentro de uma caixa, na margem esquerda, contendo 'texto' como descrição (pode usar subconjuntos destas características);
* '''<code><nowiki>[[</nowiki>{{ns:media}}<nowiki>:ficheiro.ogg]]</nowiki></code>''' para apresentar um link direto para o ficheiro em vez de mostrá-lo, quer este tenha por conteúdo uma imagem ou outros dados.",
'upload-permitted' => 'Tipos de ficheiro permitidos: $1.',
'upload-preferred' => 'Tipos de ficheiro preferidos: $1.',
'upload-prohibited' => 'Tipos de ficheiro proibidos: $1.',
'uploadlog' => 'registo de carregamento',
'uploadlogpage' => 'Registo de carregamento',
'uploadlogpagetext' => 'Segue-se uma lista dos carregamentos mais recentes.
Consulte a [[Special:NewFiles|galeria de novos ficheiros]] para visioná-los.',
'filename' => 'Nome do ficheiro',
'filedesc' => 'Descrição do ficheiro',
'fileuploadsummary' => 'Resumo:',
'filereuploadsummary' => 'Alterações ao ficheiro:',
'filestatus' => 'Estado dos direitos de autor:',
'filesource' => 'Fonte:',
'uploadedfiles' => 'Ficheiros carregados',
'ignorewarning' => 'Ignorar o aviso e gravar o ficheiro.',
'ignorewarnings' => 'Ignorar todos os avisos',
'minlength1' => 'Os nomes de ficheiros devem de ter pelo menos uma letra.',
'illegalfilename' => 'O nome do ficheiro "$1" contém caracteres que não são permitidos no título das páginas.
Altere o nome do ficheiro e tente enviá-lo novamente, por favor.',
'filename-toolong' => 'Os nomes de ficheiros não podem ter mais de 240 bytes.',
'badfilename' => 'O nome do ficheiro foi alterado para "$1".',
'filetype-mime-mismatch' => 'A extensão ".$1" não corresponde ao tipo MIME do ficheiro ($2).',
'filetype-badmime' => 'Não é permitido carregar ficheiros do tipo MIME "$1".',
'filetype-bad-ie-mime' => 'Não é possível carregar este ficheiro porque o Internet Explorer o detectaria como "$1", que é um tipo de ficheiro não permitido e potencialmente perigoso.',
'filetype-unwanted-type' => "'''\".\$1\"''' não é um tipo de ficheiro desejado.
{{PLURAL:\$3|O tipo preferido é|Os tipos preferidos são}} \$2.",
'filetype-banned-type' => '\'\'\'".$1"\'\'\' {{PLURAL:$4|não é um tipo de ficheiro permitido|não são tipos de ficheiro permitidos}}.
{{PLURAL:$3|O tipo de ficheiro permitido é|Os tipos de ficheiro permitidos são}} $2.',
'filetype-missing' => 'O ficheiro não possui uma extensão (como, por exemplo, ".jpg").',
'empty-file' => 'O ficheiro que enviou estava vazio.',
'file-too-large' => 'O ficheiro que enviou era demasiado grande.',
'filename-tooshort' => 'O nome do ficheiro é demasiado curto.',
'filetype-banned' => 'Este tipo de ficheiro é proibido.',
'verification-error' => 'O ficheiro não passou a verificação de ficheiros.',
'hookaborted' => 'A modificação que pretendia foi abortada pelo hook de uma extensão.',
'illegal-filename' => 'O nome do ficheiro não é permitido.',
'overwrite' => 'Não é permitido sobrescrever um ficheiro existente.',
'unknown-error' => 'Ocorreu um erro desconhecido.',
'tmp-create-error' => 'Não foi possível criar o ficheiro temporário.',
'tmp-write-error' => 'Erro na escrita do ficheiro temporário.',
'large-file' => 'É recomendável que os ficheiros não sejam maiores que $1;
este tem $2.',
'largefileserver' => 'O tamanho deste ficheiro é superior ao permitido pela configuração do servidor.',
'emptyfile' => 'O ficheiro que enviou parece estar vazio.
Isto pode dever-se a um erro no nome do ficheiro.
Verifique se é realmente este o ficheiro que deseja carregar, por favor.',
'windows-nonascii-filename' => 'A wiki não aceita nomes de ficheiros com caracteres especiais.',
'fileexists' => 'Já existe um ficheiro com este nome.
Verifique <strong>[[:$1]]</strong> caso não tenha a certeza de que quer alterar o ficheiro atual, por favor.
[[$1|thumb]]',
'filepageexists' => 'A página de descrição deste ficheiro já foi criada em <strong>[[:$1]]</strong>, mas neste momento não existe nenhum ficheiro com este nome.
O resumo que introduzir não aparecerá na página de descrição.
Para fazê-lo aparecer, terá de editar a página manualmente.
[[$1|thumb]]',
'fileexists-extension' => 'Já existe um ficheiro de nome semelhante: [[$2|thumb]]
* Nome do ficheiro que está sendo carregado: <strong>[[:$1]]</strong>
* Nome do ficheiro existente: <strong>[[:$2]]</strong>
Escolha um nome diferente, por favor.',
'fileexists-thumbnail-yes' => "O ficheiro aparenta ser uma imagem de tamanho reduzido (''miniatura'', ou ''thumbnail)''. [[$1|thumb]]
Verifique o ficheiro <strong>[[:$1]]</strong>, por favor.
Se este ficheiro é a mesma imagem mas no tamanho original, não é necessário carregar uma miniatura.",
'file-thumbnail-no' => "O nome do ficheiro começa por <strong>$1</strong>.
Parece ser uma imagem de tamanho reduzido (uma ''miniatura'' ou ''thumbnail)''.
Se tiver a imagem original de maior dimensão, envie-a em vez desta. Se não, altere o nome do ficheiro, por favor.",
'fileexists-forbidden' => 'Já existe um ficheiro com este nome, e não pode ser reescrito.
Se ainda pretende carregar o seu ficheiro volte atrás e use outro nome, por favor. [[File:$1|thumb|center|$1]]',
'fileexists-shared-forbidden' => 'Já existe um ficheiro com este nome no repositório de ficheiros partilhados.
Caso deseje, mesmo assim, carregar o seu ficheiro, volte atrás e envie-o com um novo nome. [[File:$1|thumb|center|$1]]',
'file-exists-duplicate' => 'Este ficheiro é um duplicado {{PLURAL:$1|do seguinte|dos seguintes}}:',
'file-deleted-duplicate' => 'Um ficheiro idêntico a este ([[:$1]]) foi eliminado anteriormente.
Verifique o motivo da eliminação do ficheiro antes de prosseguir com o re-envio.',
'uploadwarning' => 'Aviso de envio',
'uploadwarning-text' => 'Modifique a descrição do ficheiro abaixo e tente novamente, por favor.',
'savefile' => 'Gravar ficheiro',
'uploadedimage' => 'carregou "[[$1]]"',
'overwroteimage' => 'enviou uma nova versão de "[[$1]]"',
'uploaddisabled' => 'Carregamentos impossibilitados',
'copyuploaddisabled' => 'Upload por URL impossibilitado.',
'uploadfromurl-queued' => 'O seu upload foi adicionado à fila.',
'uploaddisabledtext' => 'O carregamento de ficheiros está impossibilitado.',
'php-uploaddisabledtext' => 'O carregamento de ficheiros está impossibilitado no PHP.
Verifique a configuração file_uploads, por favor.',
'uploadscripted' => 'Este ficheiro contém HTML ou código que pode ser erradamente interpretado por um browser.',
'uploadvirus' => 'O ficheiro contém um vírus! Detalhes: $1',
'uploadjava' => 'Este é um ficheiro ZIP que contém um ficheiro .class de Java.
Não é permitido o upload de ficheiros Java, porque estes podem contornar as restrições de segurança.',
'upload-source' => 'Ficheiro de origem',
'sourcefilename' => 'Nome do ficheiro de origem:',
'sourceurl' => 'URL fonte:',
'destfilename' => 'Nome do ficheiro de destino:',
'upload-maxfilesize' => 'Tamanho máximo do ficheiro: $1',
'upload-description' => 'Descrição do ficheiro',
'upload-options' => 'Opções de carregamento',
'watchthisupload' => 'Vigiar este ficheiro',
'filewasdeleted' => 'Um ficheiro com este nome foi carregado anteriormente e subsequentemente eliminado.
Deverá verificar o $1 antes de voltar a enviá-lo.',
'filename-bad-prefix' => "O nome do ficheiro que está a enviar começa por '''\"\$1\"''', um nome pouco explicativo, normalmente originado de forma automática por câmaras digitais. Escolha um nome de ficheiro mais explicativo, por favor.",
'filename-prefix-blacklist' => ' #<!-- deixe esta linha exactamente como está --> <pre>
# A sintaxe é a seguinte:
#   * Tudo a partir do carácter "#" até ao fim da linha é um comentário
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
'upload-success-subj' => 'Envio efetuado com sucesso',
'upload-success-msg' => 'O seu upload de [$2] foi bem sucedido. Está disponível aqui: [[:{{ns:file}}:$1]]',
'upload-failure-subj' => 'Problema no upload',
'upload-failure-msg' => 'Ocorreu um problema com o seu upload de [$2]:

$1',
'upload-warning-subj' => 'Aviso de upload',
'upload-warning-msg' => 'Ocorreu um problema no seu upload de [$2]. Pode voltar ao [[Special:Upload/stash/$1|formulário de upload]] para resolver o problema.',

'upload-proto-error' => 'Protocolo incorreto',
'upload-proto-error-text' => 'O carregamento remoto de ficheiros requer endereços URL começados por <code>http://</code> ou <code>ftp://</code>.',
'upload-file-error' => 'Erro interno',
'upload-file-error-text' => 'Ocorreu um erro interno ao tentar criar um ficheiro temporário no servidor.
Contacte um [[Special:ListUsers/sysop|administrador]], por favor.',
'upload-misc-error' => 'Erro de carregamento desconhecido',
'upload-misc-error-text' => 'Ocorreu um erro desconhecido durante o envio.
Verifique se o endereço (URL) é válido e acessível e tente novamente.
Caso o problema persista, contacte um [[Special:ListUsers/sysop|administrador]].',
'upload-too-many-redirects' => 'A URL continha demasiados redirecionamentos',
'upload-unknown-size' => 'Tamanho desconhecido',
'upload-http-error' => 'Ocorreu um erro HTTP: $1',
'upload-copy-upload-invalid-domain' => 'Não é possível realizar carregamentos remotos neste domínio.',

# File backend
'backend-fail-stream' => 'Não foi possível transmitir o ficheiro $1.',
'backend-fail-backup' => 'Não foi possível fazer backup do ficheiro $1.',
'backend-fail-notexists' => 'O ficheiro $1 não existe.',
'backend-fail-hashes' => 'Não foi possível obter os hashes do ficheiro para comparação.',
'backend-fail-notsame' => 'Já existe um ficheiro não idêntico em $1 .',
'backend-fail-invalidpath' => '$1 não é um caminho de armazenamento válido.',
'backend-fail-delete' => 'Não foi possível excluir o ficheiro $1.',
'backend-fail-describe' => 'Não foi possível mudar metadados para o ficheiro "$1".',
'backend-fail-alreadyexists' => 'O ficheiro $1 já existe.',
'backend-fail-store' => 'Não foi possível armazenar o ficheiro $1 em $2.',
'backend-fail-copy' => 'Não foi possível copiar o ficheiro $1 para $2.',
'backend-fail-move' => 'Não é possível mover o ficheiro $1 para $2.',
'backend-fail-opentemp' => 'Não foi possível abrir o ficheiro temporário.',
'backend-fail-writetemp' => 'Não foi possível escrever no ficheiro temporário.',
'backend-fail-closetemp' => 'Não foi possível fechar o ficheiro temporário.',
'backend-fail-read' => 'Não foi possível ler o ficheiro $1.',
'backend-fail-create' => 'Não foi possível gravar o ficheiro $1.',
'backend-fail-maxsize' => 'Não foi possível gravar o ficheiro $1 porque tem mais do que {{PLURAL:$2|um byte|$2 bytes}}.',
'backend-fail-readonly' => 'O servidor de armazenamento "$1" está atualmente no modo "somente leitura". A razão dada foi: "$2"',
'backend-fail-synced' => 'O ficheiro "$1" está num estado inconsistente nos servidores de armazenamento interno',
'backend-fail-connect' => 'Não foi possível estabelecer ligação com o servidor de armazenamento "$1".',
'backend-fail-internal' => 'Ocorreu um erro desconhecido no servidor de armazenamento "$1".',
'backend-fail-contenttype' => 'Não foi possível determinar o tipo de conteúdo do ficheiro para armazenar em "$1".',
'backend-fail-batchsize' => 'Foi fornecido um bloco de $1 {{PLURAL:$1|operação|operações}} sobre ficheiros ao servidor de armazenamento; o limite é de $2 {{PLURAL:$2|operação|operações}}.',
'backend-fail-usable' => 'Não foi possível ler ou gravar o ficheiro "$1" devido a permissões insuficientes ou a diretórios/repositórios inexistentes.',

# File journal errors
'filejournal-fail-dbconnect' => 'Não foi possível estabelecer ligação à base de dados de registos no servidor de armazenamento "$1".',
'filejournal-fail-dbquery' => 'Não foi possível atualizar a base de dados de registos do servidor de armazenamento "$1".',

# Lock manager
'lockmanager-notlocked' => 'Não foi possível desbloquear "$1" porque não se encontra bloqueado.',
'lockmanager-fail-closelock' => 'Não foi possível encerrar a referência de bloqueio para "$1".',
'lockmanager-fail-deletelock' => 'Não foi possível eliminar a referência de bloqueio para "$1".',
'lockmanager-fail-acquirelock' => 'Não foi possível adquirir bloqueio para "$1".',
'lockmanager-fail-openlock' => 'Não foi possível abrir o ficheiro de bloqueio de "$1".',
'lockmanager-fail-releaselock' => 'Não foi possível libertar o bloqueio de "$1".',
'lockmanager-fail-db-bucket' => 'Não foi possível contactar bases de dados de bloqueio suficientes no "bucket" $1.',
'lockmanager-fail-db-release' => 'Não foi possível libertar bloqueios na base de dados $1.',
'lockmanager-fail-svr-acquire' => 'Não foi possível obter bloqueios no servidor $1.',
'lockmanager-fail-svr-release' => 'Não foi possível libertar bloqueios no servidor $1.',

# ZipDirectoryReader
'zip-file-open-error' => 'Foi encontrado um erro ao abrir o ficheiro ZIP para verificação.',
'zip-wrong-format' => 'O ficheiro especificado não é um ficheiro ZIP.',
'zip-bad' => 'O ficheiro ZIP encontra-se corrompido ou não é legível.
A segurança do mesmo não pode ser devidamente verificada.',
'zip-unsupported' => 'Este ficheiro ZIP usa funcionalidades ZIP não suportadas pelo MediaWiki.
A sua segurança não pode ser devidamente verificada.',

# Special:UploadStash
'uploadstash' => 'Ficheiros escondidos',
'uploadstash-summary' => 'Esta página dá acesso aos ficheiros enviados (ou que estão no processo de envio) mas que ainda não foram publicados na wiki. Estes ficheiros não são visíveis para ninguém, exceto para o utilizador que os enviou.',
'uploadstash-clear' => 'Apagar os ficheiros escondidos',
'uploadstash-nofiles' => 'Não tem ficheiros escondidos.',
'uploadstash-badtoken' => 'Não foi possível executar essa operação, talvez porque as suas credenciais de edição expiraram. Tente novamente.',
'uploadstash-errclear' => 'Não foi possível apagar os ficheiros.',
'uploadstash-refresh' => 'Atualizar a lista de ficheiros',
'invalid-chunk-offset' => 'Deslocamento de fragmento inválido',

# img_auth script messages
'img-auth-accessdenied' => 'Acesso negado',
'img-auth-nopathinfo' => 'PATH_INFO em falta.
O seu servidor não está configurado para passar esta informação.
Pode ser baseado em CGI e não consegue suportar img_auth.
Consulte a documentação em https://www.mediawiki.org/wiki/Manual:Image_Authorization.',
'img-auth-notindir' => 'O endereço especificado não conduz ao diretório de carregamento de ficheiros configurado.',
'img-auth-badtitle' => 'Não é possível construir um título válido a partir de "$1".',
'img-auth-nologinnWL' => 'Não está autenticado e o ficheiro "$1" não está na lista branca.',
'img-auth-nofile' => 'O ficheiro "$1" não existe.',
'img-auth-isdir' => 'Está tentando aceder ao diretório "$1".
Só é permitido o acesso a ficheiros.',
'img-auth-streaming' => "A fazer o ''streaming'' de \"\$1\".",
'img-auth-public' => 'A função do img_auth.php é produzir ficheiros a partir de uma wiki privada.
Esta wiki está configurada como uma wiki pública.
Para optimizar a segurança, o img_auth.php está impossibilitado de executar.',
'img-auth-noread' => 'O utilizador não tem acesso de leitura ao ficheiro "$1".',
'img-auth-bad-query-string' => 'A URL tem um texto de consulta inválido.',

# HTTP errors
'http-invalid-url' => 'URL inválida: $1',
'http-invalid-scheme' => 'URLs iniciadas pelo prefixo "$1" não são aceites.',
'http-request-error' => 'O pedido HTTP falhou devido a um erro desconhecido.',
'http-read-error' => 'Erro de leitura HTTP.',
'http-timed-out' => 'O pedido HTTP expirou.',
'http-curl-error' => 'Ocorreu um erro ao aceder à URL: $1',
'http-bad-status' => 'Ocorreu um problema durante o pedido HTTP: $1 $2',

# Some likely curl errors. More could be added from <http://curl.haxx.se/libcurl/c/libcurl-errors.html>
'upload-curl-error6' => 'Não foi possível aceder à URL',
'upload-curl-error6-text' => 'Não foi possível aceder à URL.
Verifique se o endereço está correto e o site disponível, por favor.',
'upload-curl-error28' => 'Tempo limite para o envio do ficheiro excedido',
'upload-curl-error28-text' => 'O site demorou muito tempo a responder. Verifique que o site está disponível, aguarde alguns momentos e tente novamente, por favor. Talvez queira tentar num horário menos congestionado.',

'license' => 'Licença:',
'license-header' => 'Licenciamento',
'nolicense' => 'Nenhuma selecionada',
'license-nopreview' => '(Antevisão indisponível)',
'upload_source_url' => ' (uma URL válida, publicamente acessível)',
'upload_source_file' => ' (um ficheiro no seu computador)',

# Special:ListFiles
'listfiles-summary' => 'Esta página especial mostra todos os ficheiros carregados.',
'listfiles_search_for' => 'Pesquisar por nome de imagem:',
'imgfile' => 'ficheiro',
'listfiles' => 'Ficheiros',
'listfiles_thumb' => 'Miniatura',
'listfiles_date' => 'Data',
'listfiles_name' => 'Nome',
'listfiles_user' => 'Utilizador',
'listfiles_size' => 'Tamanho',
'listfiles_description' => 'Descrição',
'listfiles_count' => 'Versões',
'listfiles-show-all' => 'Incluir versões antigas de imagens',
'listfiles-latestversion-no' => 'Não',

# File description page
'file-anchor-link' => 'Ficheiro',
'filehist' => 'Histórico do ficheiro',
'filehist-help' => 'Clique numa data/hora para ver o ficheiro tal como se encontrava nesse momento.',
'filehist-deleteall' => 'eliminar todas',
'filehist-deleteone' => 'eliminar',
'filehist-revert' => 'restaurar',
'filehist-current' => 'atual',
'filehist-datetime' => 'Data/Hora',
'filehist-thumb' => 'Miniatura',
'filehist-thumbtext' => "Miniatura ''(thumbnail)'' da versão das $1",
'filehist-nothumb' => 'Miniatura indisponível',
'filehist-user' => 'Utilizador',
'filehist-dimensions' => 'Dimensões',
'filehist-filesize' => 'Tamanho do ficheiro',
'filehist-comment' => 'Comentário',
'filehist-missing' => 'Ficheiro em falta',
'imagelinks' => 'Uso do ficheiro',
'linkstoimage' => '{{PLURAL:$1|A seguinte página contém|As seguintes $1 páginas contêm}} links para este ficheiro:',
'linkstoimage-more' => 'Mais de {{PLURAL:$1|uma página contém|$1 páginas contêm}} links para este ficheiro.
A lista abaixo apresenta apenas {{PLURAL:$1|a primeira página|as primeiras $1 páginas}}.
Encontra-se disponível uma [[Special:WhatLinksHere/$2|lista completa]].',
'nolinkstoimage' => 'Nenhuma página contém links para este ficheiro.',
'morelinkstoimage' => 'Ver a [[Special:WhatLinksHere/$1|lista completa]] de páginas que contêm links para este ficheiro.',
'linkstoimage-redirect' => '$1 (redirecionamento de ficheiro) $2',
'duplicatesoffile' => '{{PLURAL:$1|O seguinte ficheiro é duplicado|Os seguintes $1 ficheiros são duplicados}} deste ficheiro ([[Special:FileDuplicateSearch/$2|mais detalhes]]):',
'sharedupload' => 'Este ficheiro provém de $1 e pode ser usado por outros projetos.',
'sharedupload-desc-there' => 'Este ficheiro provém de $1 e pode ser usado por outros projetos.
Consulte a [$2 página de descrição do ficheiro] para mais informações, por favor.',
'sharedupload-desc-here' => 'Este ficheiro provém de $1 e pode ser usado por outros projetos.
A descrição na [$2 página original de descrição do ficheiro] é mostrada abaixo.',
'sharedupload-desc-edit' => 'Este ficheiro provém de $1 e pode ser utilizado por outros projetos.
Talvez queira editar a descrição na [$2 página original de descrição do ficheiro].',
'sharedupload-desc-create' => 'Este ficheiro provém de $1 e pode ser utilizado por outros projetos.
Talvez queira editar a descrição na [$2 página original de descrição do ficheiro].',
'filepage-nofile' => 'Não existe nenhum ficheiro com este nome.',
'filepage-nofile-link' => 'Não existe nenhum ficheiro com este nome, mas pode [$1 carregá-lo].',
'uploadnewversion-linktext' => 'Carregar uma nova versão deste ficheiro',
'shared-repo-from' => 'de $1',
'shared-repo' => 'um repositório partilhado',
'upload-disallowed-here' => 'Você não pode substituir este ficheiro.',

# File reversion
'filerevert' => 'Reverter $1',
'filerevert-legend' => 'Reverter ficheiro',
'filerevert-intro' => "Está prestes a reverter o ficheiro '''[[Media:$1|$1]]''' para a [$4 versão de $2 às $3].",
'filerevert-comment' => 'Motivo:',
'filerevert-defaultcomment' => 'Revertido para a versão de $1 - $2',
'filerevert-submit' => 'Reverter',
'filerevert-success' => "'''[[Media:$1|$1]]''' foi revertida para a [$4 versão das $3 de $2].",
'filerevert-badversion' => 'Não há uma versão local anterior deste ficheiro no período de tempo especificado.',

# File deletion
'filedelete' => 'Eliminar $1',
'filedelete-legend' => 'Eliminar ficheiro',
'filedelete-intro' => "Está prestes a eliminar o ficheiro '''[[Media:$1|$1]]''' e todo o seu histórico.",
'filedelete-intro-old' => "Está prestes a eliminar a versão de '''[[Media:$1|$1]]''' tal como se encontrava em [$4 $3, $2].",
'filedelete-comment' => 'Motivo:',
'filedelete-submit' => 'Eliminar',
'filedelete-success' => "'''$1''' foi eliminado.",
'filedelete-success-old' => "A versão de '''[[Media:$1|$1]]''' tal como $3, $2 foi eliminada.",
'filedelete-nofile' => "'''$1''' não existe.",
'filedelete-nofile-old' => "Não há nenhuma versão de '''$1''' em arquivo com os parâmetros especificados.",
'filedelete-otherreason' => 'Outro/motivo adicional:',
'filedelete-reason-otherlist' => 'Outro motivo',
'filedelete-reason-dropdown' => '*Motivos comuns para eliminação
** Violação de direitos de autor
** Ficheiro duplicado',
'filedelete-edit-reasonlist' => 'Editar motivos de eliminação',
'filedelete-maintenance' => 'Eliminação e restauro de ficheiros foram temporariamente impossibilitadas durante a manutenção.',
'filedelete-maintenance-title' => 'Não é possível eliminar o ficheiro',

# MIME search
'mimesearch' => 'Pesquisa MIME',
'mimesearch-summary' => 'Esta página permite pesquisar os ficheiros da wiki, filtrando-os a partir do seu tipo MIME. O tipo MIME deve ser especificado na forma: tipo/subtipo. Alguns exemplos de tipos frequentes: <code>image/jpeg</code>, <code>image/gif</code>, <code>image/png</code>, <code>application/pdf</code>, <code>application/vnd.ms-excel</code>, <code>application/zip</code>, <code>application/vnd.ms-powerpoint</code>.',
'mimetype' => 'Tipo MIME:',
'download' => 'download',

# Unwatched pages
'unwatchedpages' => 'Páginas não vigiadas',

# List redirects
'listredirects' => 'Lista de redirecionamentos',

# Unused templates
'unusedtemplates' => 'Predefinições não utilizadas',
'unusedtemplatestext' => 'Esta página lista todas as páginas no espaço nominal {{ns:template}} que não são incluídas em nenhuma outra página. Lembre-se de verificar a existência de outros links para as predefinições, antes de eliminá-las.',
'unusedtemplateswlh' => 'outros links',

# Random page
'randompage' => 'Página aleatória',
'randompage-nopages' => 'Não há páginas {{PLURAL:$2|no seguinte espaço nominal|nos seguintes espaços nominais}}: $1.',

# Random page in category
'randomincategory-nopages' => 'Não há páginas na categoria [[:Category:$1|$1]].',
'randomincategory-selectcategory-submit' => 'Ir',

# Random redirect
'randomredirect' => 'Redirecionamento aleatório',
'randomredirect-nopages' => 'Não há redirecionamentos no espaço nominal "$1".',

# Statistics
'statistics' => 'Estatísticas',
'statistics-header-pages' => 'Estatísticas de páginas',
'statistics-header-edits' => 'Estatísticas de edições',
'statistics-header-views' => 'Ver estatísticas',
'statistics-header-users' => 'Estatísticas de utilizadores',
'statistics-header-hooks' => 'Outras estatísticas',
'statistics-articles' => 'Páginas de conteúdo',
'statistics-pages' => 'Páginas',
'statistics-pages-desc' => 'Todas as páginas da wiki, incluindo páginas de discussão, redirecionamentos, etc.',
'statistics-files' => 'Ficheiros carregados',
'statistics-edits' => 'Edições de páginas desde que a {{SITENAME}} foi instalada',
'statistics-edits-average' => 'Média de edições por página',
'statistics-views-total' => 'Total de visionamentos',
'statistics-views-total-desc' => 'Não estão incluídos os visionamentos de páginas inexistentes e páginas especiais',
'statistics-views-peredit' => 'Visionamentos por edição',
'statistics-users' => '[[Special:ListUsers|Utilizadores]] registados',
'statistics-users-active' => 'Utilizadores ativos',
'statistics-users-active-desc' => 'Utilizadores que efectuaram uma operação {{PLURAL:$1|no último dia|nos últimos $1 dias}}',
'statistics-mostpopular' => 'Páginas mais vistas',

'pageswithprop' => 'Páginas com uma propriedade',
'pageswithprop-legend' => 'Páginas com uma propriedade',
'pageswithprop-text' => 'Esta página lista páginas que usam uma propriedade em particular.',
'pageswithprop-prop' => 'Nome da propriedade:',
'pageswithprop-submit' => 'Avançar',
'pageswithprop-prophidden-long' => 'foi ocultado o valor da propriedade por ser um texto muito longo ($1 kilobytes)',
'pageswithprop-prophidden-binary' => 'foi ocultado o valor da propriedade por ser binário ($1 kilobytes)',

'doubleredirects' => 'Redirecionamentos duplos',
'doubleredirectstext' => 'Esta página lista todas as páginas que redirecionam para outras páginas de redirecionamento.
Cada linha contém links para o primeiro e segundo redirecionamentos, bem como o destino do segundo redirecionamento, geralmente contendo a verdadeira página de destino, que devia ser o destino do primeiro redirecionamento.
<del>Entradas cortadas</del> já foram solucionadas.',
'double-redirect-fixed-move' => '[[$1]] foi movido.
Agora redirecciona para [[$2]].',
'double-redirect-fixed-maintenance' => 'A corrigir redirecionamento duplo de [[$1]] para [[$2]].',
'double-redirect-fixer' => 'Corretor de redirecionamentos',

'brokenredirects' => 'Redirecionamentos quebrados',
'brokenredirectstext' => 'Os seguintes redirecionamentos ligam para páginas inexistentes:',
'brokenredirects-edit' => 'editar',
'brokenredirects-delete' => 'eliminar',

'withoutinterwiki' => 'Páginas sem links interlínguas',
'withoutinterwiki-summary' => 'As seguintes páginas não têm links para versões noutras línguas.',
'withoutinterwiki-legend' => 'Prefixo',
'withoutinterwiki-submit' => 'Mostrar',

'fewestrevisions' => 'Páginas com menos revisões',

# Miscellaneous special pages
'nbytes' => '$1 {{PLURAL:$1|byte|bytes}}',
'ncategories' => '$1 {{PLURAL:$1|categoria|categorias}}',
'ninterwikis' => '$1 {{PLURAL:$1|interwiki|interwikis}}',
'nlinks' => '$1 {{PLURAL:$1|ligação|ligações}}',
'nmembers' => '$1 {{PLURAL:$1|membro|membros}}',
'nrevisions' => '$1 {{PLURAL:$1|edição|edições}}',
'nviews' => '$1 {{PLURAL:$1|visita|visitas}}',
'nimagelinks' => 'Usada {{PLURAL:$1|numa página|em $1 páginas}}',
'ntransclusions' => 'usada {{PLURAL:$1|numa página|em $1 páginas}}',
'specialpage-empty' => 'Não existem dados para apresentar.',
'lonelypages' => 'Páginas órfãs',
'lonelypagestext' => 'As seguintes páginas não são destino de links nem são transcluídas a partir de outras páginas na {{SITENAME}}.',
'uncategorizedpages' => 'Páginas não categorizadas',
'uncategorizedcategories' => 'Categorias não categorizadas',
'uncategorizedimages' => 'Ficheiros não categorizados',
'uncategorizedtemplates' => 'Predefinições não categorizadas',
'unusedcategories' => 'Categorias não utilizadas',
'unusedimages' => 'Ficheiros não utilizados',
'popularpages' => 'Páginas populares',
'wantedcategories' => 'Categorias desejadas',
'wantedpages' => 'Páginas desejadas',
'wantedpages-badtitle' => 'Título inválido no conjunto de resultados: $1',
'wantedfiles' => 'Ficheiros desejados',
'wantedfiletext-cat' => 'Os seguintes ficheiros são usados, mas não existem. Ficheiros de repositórios externos podem ser listados apesar de existirem. Tais falsos positivos aparecerão <del>riscados</del>. Adicionalmente, as páginas que incorporam ficheiros que não existem estão listadas em [[:$1]].',
'wantedfiletext-nocat' => 'Os seguintes ficheiros são usados, mas não existem. Ficheiros de repositórios externos podem ser listados apesar de existirem. Tais falsos positivos aparecerão <del>riscados</del>.',
'wantedtemplates' => 'Predefinições desejadas',
'mostlinked' => 'Páginas com mais afluentes',
'mostlinkedcategories' => 'Categorias com mais membros',
'mostlinkedtemplates' => 'Predefinições com mais afluentes',
'mostcategories' => 'Páginas com mais categorias',
'mostimages' => 'Ficheiros com mais afluentes',
'mostinterwikis' => 'Páginas com mais interwikis',
'mostrevisions' => 'Páginas com mais revisões',
'prefixindex' => 'Todas as páginas iniciadas por',
'prefixindex-namespace' => 'Todas as páginas com prefixo (espaço nominal $1)',
'prefixindex-strip' => 'Remover prefixo',
'shortpages' => 'Páginas curtas',
'longpages' => 'Páginas longas',
'deadendpages' => 'Páginas sem saída',
'deadendpagestext' => 'As seguintes páginas não contêm links para outras páginas na {{SITENAME}}.',
'protectedpages' => 'Páginas protegidas',
'protectedpages-indef' => 'Apenas proteções indefinidas',
'protectedpages-cascade' => 'Apenas proteções em cascata',
'protectedpagestext' => 'As seguintes páginas estão protegidas contra edição ou movimentação',
'protectedpagesempty' => 'Neste momento, nenhuma das páginas está protegida com estes parâmetros.',
'protectedtitles' => 'Títulos protegidos',
'protectedtitlestext' => 'Os títulos a seguir encontram-se protegidos contra criação',
'protectedtitlesempty' => 'Neste momento, nenhum dos títulos está protegido com estes parâmetros.',
'listusers' => 'Utilizadores',
'listusers-editsonly' => 'Mostrar apenas utilizadores com edições',
'listusers-creationsort' => 'Ordenar por data de criação',
'usereditcount' => '$1 {{PLURAL:$1|edição|edições}}',
'usercreated' => '{{GENDER:$3|Criado|Criada}} em $1 às $2',
'newpages' => 'Páginas recentes',
'newpages-username' => 'Nome de utilizador:',
'ancientpages' => 'Páginas mais antigas',
'move' => 'Mover',
'movethispage' => 'Mover esta página',
'unusedimagestext' => 'Os seguintes ficheiros existem mas não são usados na wiki.
No entanto, outros sites na internet podem ter links para um ficheiro através de uma URL direta e, por isso, podem estar listados ficheiros que estão a ser ativamente usados por entidades externas.',
'unusedcategoriestext' => 'As seguintes categorias existem, embora nenhuma página ou categoria faça uso delas.',
'notargettitle' => 'Sem alvo',
'notargettext' => 'Especifique sobre que página alvo ou utilizador pretende executar esta função.',
'nopagetitle' => 'A página de destino não existe',
'nopagetext' => 'A página de destino que especificou não existe.',
'pager-newer-n' => '{{PLURAL:$1|posterior|$1 posteriores}}',
'pager-older-n' => '{{PLURAL:$1|1 anterior|$1 anteriores}}',
'suppress' => 'Supervisor',
'querypage-disabled' => 'Esta página especial está desativada para não prejudicar o desempenho.',

# Book sources
'booksources' => 'Fontes bibliográficas',
'booksources-search-legend' => 'Pesquisar referências bibliográficas',
'booksources-go' => 'Prosseguir',
'booksources-text' => 'É apresentada abaixo uma lista de links para outros sites na internet que vendem livros novos e usados e talvez possuam informações adicionais sobre os livros que procura:',
'booksources-invalid-isbn' => 'O número ISBN fornecido não parece ser válido; verifique a existência de erros ao copiar da fonte original.',

# Special:Log
'specialloguserlabel' => 'Executante:',
'speciallogtitlelabel' => 'Alvo (página ou utilizador):',
'log' => 'Registos',
'all-logs-page' => 'Todos os registos públicos',
'alllogstext' => 'Apresentação combinada de todos os registos disponíveis na wiki {{SITENAME}}.
Pode reduzir a lista escolhendo um tipo de registo, um nome de utilizador ou um título de página. Respeite maiúsculas e minúsculas.',
'logempty' => 'Não há dados a apresentar.',
'log-title-wildcard' => 'Procurar títulos iniciados por este texto',
'showhideselectedlogentries' => 'Mostrar ou ocultar as entradas selecionadas',

# Special:AllPages
'allpages' => 'Todas as páginas',
'alphaindexline' => '$1 até $2',
'nextpage' => 'Página seguinte ($1)',
'prevpage' => 'Página anterior ($1)',
'allpagesfrom' => 'Apresentar páginas desde:',
'allpagesto' => 'Apresentar páginas até:',
'allarticles' => 'Todas as páginas',
'allinnamespace' => 'Todas as páginas (espaço nominal $1)',
'allnotinnamespace' => 'Todas as páginas (exceto as do espaço nominal $1)',
'allpagesprev' => 'Anterior',
'allpagesnext' => 'Próximo',
'allpagessubmit' => 'Ver',
'allpagesprefix' => 'Apresentar páginas iniciadas por:',
'allpagesbadtitle' => 'O título de página fornecido era inválido ou tinha um prefixo interlínguas ou interwikis.
Talvez contenha um ou mais caracteres que não podem ser usados nos títulos.',
'allpages-bad-ns' => 'A {{SITENAME}} não possui o espaço nominal "$1".',
'allpages-hide-redirects' => 'Ocultar redirecionamentos',

# SpecialCachedPage
'cachedspecial-viewing-cached-ttl' => 'Está a ver uma versão desta página guardada na cache há pelo menos $1.',
'cachedspecial-viewing-cached-ts' => 'Está a ver uma versão da página guardada na cache, que pode estar desatualizada.',
'cachedspecial-refresh-now' => 'Ver mais recente.',

# Special:Categories
'categories' => 'Categorias',
'categoriespagetext' => '{{PLURAL:$1|A seguinte categoria contém páginas ou ficheiros multimédia|As seguintes categorias contêm páginas ou ficheiros multimédia}}.
As [[Special:UnusedCategories|categorias não utilizadas]] não são apresentadas nesta lista.
Veja também as [[Special:WantedCategories|categorias desejadas]].',
'categoriesfrom' => 'Listar categorias começando por:',
'special-categories-sort-count' => 'ordenar por contagem',
'special-categories-sort-abc' => 'ordenar alfabeticamente',

# Special:DeletedContributions
'deletedcontributions' => 'Edições eliminadas',
'deletedcontributions-title' => 'Edições eliminadas',
'sp-deletedcontributions-contribs' => 'contribuições',

# Special:LinkSearch
'linksearch' => 'Pesquisa de ligações externas',
'linksearch-pat' => 'Padrão de busca:',
'linksearch-ns' => 'Espaço nominal:',
'linksearch-ok' => 'Prosseguir',
'linksearch-text' => 'É possível usar caracteres de substituição \'\'(wildcards)\'\', tais como "*.wikipedia.org".
É necessário, pelo menos, um domínio de topo, por exemplo "*.org".<br />
{{PLURAL:$2|Protocolo suportado|Protocolos suportados}}: <code>$1</code> (será utilizado http:// se não for especificado um protocolo).',
'linksearch-line' => 'Link para $1 na página $2',
'linksearch-error' => "Caracteres de substituição ''(wildcards)'' só podem ser usados no início do endereço.",

# Special:ListUsers
'listusersfrom' => 'Mostrar utilizadores começando por:',
'listusers-submit' => 'Mostrar',
'listusers-noresult' => 'Não foram encontrados utilizadores.',
'listusers-blocked' => '(bloqueado)',

# Special:ActiveUsers
'activeusers' => 'Lista de utilizadores ativos',
'activeusers-intro' => 'Esta é uma lista dos utilizadores com qualquer tipo de atividade {{PLURAL:$1|no último dia|nos últimos $1 dias}}.',
'activeusers-count' => '$1 {{PLURAL:$1|ação|ações}} {{PLURAL:$3|no último dia|nos últimos $3 dias}}',
'activeusers-from' => 'Mostrar utilizadores começando por:',
'activeusers-hidebots' => 'Esconder robôs',
'activeusers-hidesysops' => 'Esconder administradores',
'activeusers-noresult' => 'Nenhum utilizador encontrado.',

# Special:ListGroupRights
'listgrouprights' => 'Privilégios dos grupos de utilizadores',
'listgrouprights-summary' => 'A seguinte lista contém os grupos de utilizadores definidos nesta wiki, com os respectivos privilégios de acesso.
Encontram-se disponíveis [[{{MediaWiki:Listgrouprights-helppage}}|informações adicionais]] sobre privilégios individuais.',
'listgrouprights-key' => 'Legenda:
* <span class="listgrouprights-granted">Privilégio concedido</span>
* <span class="listgrouprights-revoked">Privilégio revogado</span>',
'listgrouprights-group' => 'Grupo',
'listgrouprights-rights' => 'Privilégios',
'listgrouprights-helppage' => 'Help:Privilégios de grupo',
'listgrouprights-members' => '(lista de membros)',
'listgrouprights-addgroup' => 'Adicionar utilizadores {{PLURAL:$2|ao grupo|aos grupos}}: $1',
'listgrouprights-removegroup' => 'Remover utilizadores {{PLURAL:$2|do grupo|dos grupos}}: $1',
'listgrouprights-addgroup-all' => 'Adicionar utilizadores a todos os grupos',
'listgrouprights-removegroup-all' => 'Remover utilizadores de todos os grupos',
'listgrouprights-addgroup-self' => 'Adicionar a própria conta {{PLURAL:$2|ao grupo|aos grupos}}: $1',
'listgrouprights-removegroup-self' => 'Remover a própria conta {{PLURAL:$2|do grupo|dos grupos}}: $1',
'listgrouprights-addgroup-self-all' => 'Adicionar a própria conta a todos os grupos',
'listgrouprights-removegroup-self-all' => 'Remover a própria conta de todos os grupos',

# Email user
'mailnologin' => 'Não existe endereço de envio',
'mailnologintext' => 'Precisa de estar [[Special:UserLogin|autenticado]] e possuir um endereço de correio válido nas suas [[Special:Preferences|preferências]], para poder enviar correio electrónico a outros utilizadores.',
'emailuser' => 'Enviar correio electrónico a este utilizador',
'emailuser-title-target' => 'Enviar correio eletrónico a {{GENDER:$1|este utilizador|esta utilizadora}}',
'emailuser-title-notarget' => 'Enviar correio electrónico ao utilizador',
'emailpage' => 'Enviar correio electrónico ao utilizador',
'emailpagetext' => 'Pode usar o formulário abaixo para enviar uma mensagem por correio eletrónico para {{GENDER:$1|este utilizador|esta utilizadora}}.
O endereço de correio que introduziu nas [[Special:Preferences|suas preferências]] irá aparecer no campo do remetente da mensagem "De:", para que o destinatário lhe possa responder diretamente.',
'usermailererror' => 'O sistema de correio devolveu o erro:',
'defemailsubject' => 'Correio electrónico da {{SITENAME}}, do utilizador "$1"',
'usermaildisabled' => 'Correio eletrónico do utilizador foi desativado',
'usermaildisabledtext' => 'Não pode enviar correio electrónico aos outros utilizadores desta wiki',
'noemailtitle' => 'Sem endereço de correio electrónico',
'noemailtext' => 'Este utilizador não especificou um endereço de correio electrónico válido.',
'nowikiemailtitle' => 'Correio electrónico não é permitido',
'nowikiemailtext' => 'Este utilizador optou por não receber correio electrónico de outros utilizadores.',
'emailnotarget' => 'O nome do destinatário não existe ou é inválido.',
'emailtarget' => 'Introduza o nome de utilizador do destinatário.',
'emailusername' => 'Utilizador:',
'emailusernamesubmit' => 'Enviar',
'email-legend' => 'Enviar correio electrónico para outro utilizador da {{SITENAME}}',
'emailfrom' => 'De:',
'emailto' => 'Para:',
'emailsubject' => 'Assunto:',
'emailmessage' => 'Mensagem:',
'emailsend' => 'Enviar',
'emailccme' => 'Enviar uma cópia desta mensagem para o meu correio electrónico.',
'emailccsubject' => 'Cópia da sua mensagem para $1: $2',
'emailsent' => 'Mensagem enviada',
'emailsenttext' => 'A sua mensagem foi enviada.',
'emailuserfooter' => 'Esta mensagem foi enviada por $1 para $2 usando a opção "Contactar este utilizador" da {{SITENAME}}.',

# User Messenger
'usermessage-summary' => 'Deixar mensagem de sistema.',
'usermessage-editor' => 'Editor de mensagens de sistema',

# Watchlist
'watchlist' => 'Páginas vigiadas',
'mywatchlist' => 'Páginas vigiadas',
'watchlistfor2' => 'Para $1 $2',
'nowatchlist' => 'A sua lista de páginas vigiadas está vazia.',
'watchlistanontext' => 'Precisa de $1 para ver ou editar a sua lista de páginas vigiadas, por favor.',
'watchnologin' => 'Não está autenticado(a)',
'watchnologintext' => 'Precisa de [[Special:UserLogin|autenticar-se]] para modificar a sua lista de páginas vigiadas.',
'addwatch' => 'Adicionar às páginas vigiadas',
'addedwatchtext' => 'A página "[[:$1]]" foi adicionada à sua [[Special:Watchlist|lista de páginas vigiadas]].
Modificações futuras desta página e da respetiva página de discussão serão listadas lá.',
'removewatch' => 'Remover das páginas vigiadas',
'removedwatchtext' => 'A página "[[:$1]]" foi removida da sua lista de [[Special:Watchlist|páginas vigiadas]].',
'watch' => 'Vigiar',
'watchthispage' => 'Vigiar esta página',
'unwatch' => 'Desinteressar-se',
'unwatchthispage' => 'Parar de vigiar esta página',
'notanarticle' => 'Não é uma página de conteúdo',
'notvisiblerev' => 'Edição eliminada',
'watchlist-details' => '{{PLURAL:$1|Existe $1 página|Existem $1 páginas}} na sua lista de páginas vigiadas, excluindo páginas de discussão.',
'wlheader-enotif' => 'A notificação por correio electrónico está activa.',
'wlheader-showupdated' => "As páginas modificadas desde a última vez que as visitou aparecem destacadas a '''negrito'''.",
'watchmethod-recent' => 'a procurar páginas vigiadas nas mudanças recentes',
'watchmethod-list' => 'a procurar mudanças recentes nas páginas vigiadas',
'watchlistcontains' => 'A sua lista de páginas vigiadas contém $1 {{PLURAL:$1|página|páginas}}.',
'iteminvalidname' => "Problema com item '$1', nome inválido...",
'wlnote' => "A seguir {{PLURAL:$1|está a última alteração ocorrida|estão as últimas '''$1''' alterações ocorridas}} {{PLURAL:$2|na última hora|nas últimas '''$2''' horas}} até $3, $4.",
'wlshowlast' => 'Ver últimas $1 horas $2 dias $3',
'watchlist-options' => 'Opções da lista de páginas vigiadas',

# Displayed when you click the "watch" button and it is in the process of watching
'watching' => 'A vigiar...',
'unwatching' => 'Deixando de vigiar...',
'watcherrortext' => 'Ocorreu um erro ao alterar a configuração da sua lista de páginas vigiadas para "$1".',

'enotif_mailer' => 'Gerador de Notificações da {{SITENAME}}',
'enotif_reset' => 'Marcar todas as páginas como visitadas',
'enotif_impersonal_salutation' => 'Utilizador da "{{SITENAME}}"',
'enotif_subject_deleted' => 'A página  $1 de {{SITENAME}} foi {{GENDER:$2|eliminada}} por $2',
'enotif_subject_created' => 'A página $1 de {{SITENAME}} foi {{GENDER:$2|criada}} por $2',
'enotif_subject_moved' => 'A página $1 de {{SITENAME}} foi {{GENDER:$2|movida}} por $2',
'enotif_subject_restored' => 'A página $1 de {{SITENAME}} foi {{GENDER:$2|restaurada}} por $2',
'enotif_subject_changed' => 'A página $1 de {{SITENAME}} foi {{GENDER:$2|alterada}} por $2',
'enotif_body_intro_deleted' => 'A página $1 de {{SITENAME}} foi {{GENDER:$2|eliminada}} em $PAGEEDITDATE por $2, ver $3.',
'enotif_body_intro_created' => 'A página $1 em {{SITENAME}} foi {{GENDER:$2| criada}} em $PAGEEDITDATE por $2, ver $3 para a versão atual.',
'enotif_body_intro_moved' => 'A página $1 em {{SITENAME}} foi {{GENDER:$2|movida}} em $PAGEEDITDATE por $2, ver $3 para a versão atual.',
'enotif_body_intro_restored' => 'A página $1 em {{SITENAME}} foi {{GENDER:$2|restaurada}} em $PAGEEDITDATE por $2, ver $3 para a versão atual.',
'enotif_body_intro_changed' => 'A página $1 em {{SITENAME}} foi {{GENDER:$2|alterada}} em $PAGEEDITDATE por $2, ver $3 para a versão atual.',
'enotif_lastvisited' => 'Consulte $1 para todas as alterações efetuadas desde a sua última visita.',
'enotif_lastdiff' => 'Consulte $1 para ver esta alteração.',
'enotif_anon_editor' => 'utilizador anónimo $1',
'enotif_body' => '{{GENDER:$WATCHINGUSERNAME|Caro|Cara|Caro(a)}},

$PAGEINTRO $NEWPAGE

Resumo da edição: $PAGESUMMARY $PAGEMINOREDIT

Contacte o editor:
correio eletrónico: $PAGEEDITOR_EMAIL
wiki: $PAGEEDITOR_WIKI

Até que visite esta página, não receberá mais notificações das alterações futuras.
Pode também reativar as notificações para todas páginas na sua lista de páginas vigiadas.

             O seu sistema de notificação amigável da {{SITENAME}}

--
Para alterar as suas preferências das notificações por correio eletrónico, visite
{{canonicalurl:{{#special:Preferences}}}}

Para alterar as suas preferências das páginas vigiadas, visite
{{canonicalurl:{{#special:EditWatchlist}}}}

Para retirar a página da lista de páginas vigiadas, visite
$UNWATCHURL

Para comentários e pedidos de ajuda:
{{canonicalurl:{{MediaWiki:Helppage}}}}',
'created' => 'criada',
'changed' => 'alterada',

# Delete
'deletepage' => 'Eliminar página',
'confirm' => 'Confirmar',
'excontent' => 'o conteúdo era: "$1"',
'excontentauthor' => 'o conteúdo era: "$1" (e o único editor era [[Special:Contributions/$2|$2]]")',
'exbeforeblank' => 'o conteúdo antes de esvaziar era: "$1"',
'exblank' => 'página esvaziada',
'delete-confirm' => 'Eliminar "$1"',
'delete-legend' => 'Eliminar',
'historywarning' => "'''Aviso:''' A página que está prestes a eliminar tem um histórico com aproximadamente $1 {{PLURAL:$1|revisão|revisões}}:",
'confirmdeletetext' => 'Está prestes a eliminar permanentemente uma página ou uma imagem e todo o seu histórico.
Confirme que é realmente esta a sua intenção, que compreende as consequências e que o faz de acordo com as [[{{MediaWiki:Policy-url}}|políticas e recomendações]] do projeto, por favor.',
'actioncomplete' => 'Operação executada',
'actionfailed' => 'Operação falhou',
'deletedtext' => '"$1" foi eliminada.
Consulte $2 para um registo de eliminações recentes.',
'dellogpage' => 'Registo de eliminações',
'dellogpagetext' => 'Abaixo uma lista das eliminações mais recentes.',
'deletionlog' => 'registo de eliminações',
'reverted' => 'Revertido para versão anterior',
'deletecomment' => 'Motivo:',
'deleteotherreason' => 'Outro/motivo adicional:',
'deletereasonotherlist' => 'Outro motivo',
'deletereason-dropdown' => '* Motivos de eliminação comuns
** Spam
** Vandalismo
** Violação de direitos de autor
** Pedido do autor
** Redirecionamento quebrado',
'delete-edit-reasonlist' => 'Editar motivos de eliminação',
'delete-toobig' => 'Esta página tem um histórico longo, com mais de $1 {{PLURAL:$1|edição|edições}}.
A eliminação de páginas como esta foi restringida na {{SITENAME}}, para evitar problemas acidentais.',
'delete-warning-toobig' => 'Esta página tem um histórico de edições longo, com mais de $1 {{PLURAL:$1|edição|edições}}.
Eliminá-la poderá causar problemas na base de dados da {{SITENAME}};
prossiga com precaução.',

# Rollback
'rollback' => 'Reverter edições',
'rollback_short' => 'Voltar',
'rollbacklink' => 'voltar',
'rollbacklinkcount' => 'reverter $1 {{PLURAL:$1|edição|edições}}',
'rollbacklinkcount-morethan' => 'reverter mais do que $1 {{PLURAL:$1|edição|edições}}',
'rollbackfailed' => 'A reversão falhou',
'cantrollback' => 'Não foi possível reverter a edição; o último contribuidor é o único autor desta página',
'alreadyrolled' => 'Não foi possível reverter as edições de [[:$1]] por [[User:$2|$2]] ([[User talk:$2|discussão]]{{int:pipe-separator}}[[Special:Contributions/$2|{{int:contribslink}}]]);
alguém editou ou já reverteu a página.

A última edição foi de [[User:$3|$3]] ([[User talk:$3|discussão]]{{int:pipe-separator}}[[Special:Contributions/$3|{{int:contribslink}}]]).',
'editcomment' => "O resumo da edição era: \"''\$1''\".",
'revertpage' => 'Foram revertidas as edições de [[Special:Contributions/$2|$2]] ([[User talk:$2|disc]]) para a última revisão de [[User:$1|$1]]',
'revertpage-nouser' => 'Foram revertidas as edições de um utilizador oculto para a última revisão de {{GENDER:$1|[[User:$1|$1]]}}',
'rollback-success' => 'Foram revertidas as edições de $1, com o conteúdo passando a estar como na última edição de $2.',

# Edit tokens
'sessionfailure-title' => 'Erro de sessão',
'sessionfailure' => 'Foram detectados problemas com a sua sessão;
a operação foi cancelada como medida de proteção contra a interceptação de sessões.
Volte à página anterior, refresque-a e tente novamente.',

# Protect
'protectlogpage' => 'Registo de proteção',
'protectlogtext' => 'Encontra abaixo o registo de proteção e desproteção de páginas.
Consulte a [[Special:ProtectedPages|lista de páginas protegidas]] para ver as páginas que se encontram protegidas neste momento.',
'protectedarticle' => 'protegeu "[[$1]]"',
'modifiedarticleprotection' => 'alterou o nível de proteção para "[[$1]]"',
'unprotectedarticle' => 'desprotegeu "[[$1]]"',
'movedarticleprotection' => 'moveu as configurações de proteção de "[[$2]]" para "[[$1]]"',
'protect-title' => 'Alterar o nível de proteção de "$1"',
'protect-title-notallowed' => 'Ver o nível de proteção de "$1"',
'prot_1movedto2' => 'moveu [[$1]] para [[$2]]',
'protect-badnamespace-title' => 'Espaço nominal não passível de proteção',
'protect-badnamespace-text' => 'Páginas neste espaço nominal não podem ser protegidas.',
'protect-norestrictiontypes-text' => 'Esta página não pode ser protegida porque não há nenhum tipo de restrição disponível.',
'protect-norestrictiontypes-title' => 'Página não passível de proteção',
'protect-legend' => 'Confirmar proteção',
'protectcomment' => 'Motivo:',
'protectexpiry' => 'Expiração:',
'protect_expiry_invalid' => 'O tempo de expiração fornecido é inválido.',
'protect_expiry_old' => 'O tempo de expiração fornecido situa-se no passado.',
'protect-unchain-permissions' => 'Desbloquear outras opções de proteção',
'protect-text' => "Pode ver e alterar aqui o nível de proteção da página '''$1'''.",
'protect-locked-blocked' => "Não pode alterar níveis de proteção enquanto estiver bloqueado.
Esta é a configuração presente para a página '''$1''':",
'protect-locked-dblock' => "Não é possível alterar os níveis de proteção, porque a base de dados está bloqueada.
Esta é a configuração atual para a página '''$1''':",
'protect-locked-access' => "A sua conta não tem permissões para alterar os níveis de proteção de uma página.
Esta é a configuração atual da página '''$1''':",
'protect-cascadeon' => 'Esta página está protegida porque se encontra incluída {{PLURAL:$1|na página listada a seguir, protegida|nas páginas listadas a seguir, protegidas}} com proteção em cascata.
Pode alterar o nível de proteção desta página, mas isso não afetará a proteção em cascata.',
'protect-default' => 'Permitir todos os utilizadores',
'protect-fallback' => 'Permitir apenas utilizadores com o privilégio de "$1"',
'protect-level-autoconfirmed' => 'Permitir apenas utilizadores auto-confirmados',
'protect-level-sysop' => 'Permitir apenas administradores',
'protect-summary-cascade' => 'em cascata',
'protect-expiring' => 'expira a $1 (UTC)',
'protect-expiring-local' => 'expira a $1',
'protect-expiry-indefinite' => 'indefinidamente',
'protect-cascade' => 'Proteja quaisquer páginas que estejam incluídas nesta (proteção em cascata)',
'protect-cantedit' => 'Não pode alterar o nível de proteção desta página, porque não tem permissão para editá-la.',
'protect-othertime' => 'Outra duração:',
'protect-othertime-op' => 'outra duração',
'protect-existing-expiry' => 'A proteção atual expirará às $3 de $2',
'protect-otherreason' => 'Outro motivo/motivo adicional:',
'protect-otherreason-op' => 'Outro motivo',
'protect-dropdown' => "*Motivos comuns para proteção
** Vandalismo excessivo
** ''Spam'' excessivo
** Guerra de edições improdutiva
** Página muito visitada",
'protect-edit-reasonlist' => 'Editar motivos de proteção',
'protect-expiry-options' => '1 hora:1 hour,1 dia:1 day,1 semana:1 week,2 semanas:2 weeks,1 mês:1 month,3 meses:3 months,6 meses:6 months,1 ano:1 year,indefinido:infinite',
'restriction-type' => 'Permissão:',
'restriction-level' => 'Nível de restrição:',
'minimum-size' => 'Tamanho mín.',
'maximum-size' => 'Tamanho máx.:',
'pagesize' => '(bytes)',

# Restrictions (nouns)
'restriction-edit' => 'Editar',
'restriction-move' => 'Mover',
'restriction-create' => 'Criar',
'restriction-upload' => 'Carregar',

# Restriction levels
'restriction-level-sysop' => 'totalmente protegida',
'restriction-level-autoconfirmed' => 'semi-protegida',
'restriction-level-all' => 'qualquer nível',

# Undelete
'undelete' => 'Ver páginas eliminadas',
'undeletepage' => 'Ver e restaurar páginas eliminadas',
'undeletepagetitle' => "'''Seguem-se as edições eliminadas de [[:$1]]'''.",
'viewdeletedpage' => 'Ver páginas eliminadas',
'undeletepagetext' => '{{PLURAL:$1|A seguinte página foi eliminada|As seguintes páginas foram eliminadas}}, mas ainda {{PLURAL:$1|permanece|permanecem}} em arquivo e podem ser restauradas. O arquivo pode ser limpo periodicamente.',
'undelete-fieldset-title' => 'Restaurar edições',
'undeleteextrahelp' => "Para restaurar o histórico de edições completo desta página, desmarque todas as caixas de seleção e clique '''''{{int:undeletebtn}}'''''.
Para efetuar uma restauração seletiva, marque as caixas correspondentes às edições que pretende restaurar e clique '''''{{int:undeletebtn}}'''''.",
'undeleterevisions' => '$1 {{PLURAL:$1|edição disponível|edições disponíveis}}',
'undeletehistory' => 'Se restaurar uma página, todas as edições serão restauradas para o histórico.
Se uma nova página foi criada com o mesmo nome desde a eliminação, as edições restauradas aparecerão no histórico anterior.',
'undeleterevdel' => 'O restauro não será efetuado se resulta na remoção parcial da versão mais recente da página ou ficheiro.
Nestes casos, deverá desmarcar ou revelar a versão eliminada mais recente.',
'undeletehistorynoadmin' => 'Esta página foi eliminada. O motivo de eliminação é apresentado no sumário abaixo, junto dos detalhes do utilizador que editou esta página antes de eliminar. O texto atual destas edições eliminadas encontra-se agora apenas disponível para administradores.',
'undelete-revision' => 'Edição eliminada da página $1 (das $5 de $4), por $3:',
'undeleterevision-missing' => 'Edição inválida ou não encontrada.
Pode ter usado um link incorreto ou talvez a revisão tenha sido restaurada ou removida do arquivo.',
'undelete-nodiff' => 'Não foram encontradas edições anteriores.',
'undeletebtn' => 'Restaurar',
'undeletelink' => 'ver/restaurar',
'undeleteviewlink' => 'ver',
'undeletereset' => 'Limpar',
'undeleteinvert' => 'Inverter seleção',
'undeletecomment' => 'Motivo:',
'undeletedrevisions' => '$1 {{PLURAL:$1|edição restaurada|edições restauradas}}',
'undeletedrevisions-files' => '$1 {{PLURAL:$1|edição restaurada|edições restauradas}} e $2 {{PLURAL:$2|ficheiro restaurado|ficheiros restaurados}}',
'undeletedfiles' => '{{PLURAL:$1|ficheiro restaurado|$1 ficheiros restaurados}}',
'cannotundelete' => 'Restauração falhada:
$1',
'undeletedpage' => "'''$1 foi restaurada'''

Consulte o [[Special:Log/delete|registo de eliminações]] para um registo das eliminações e restaurações mais recentes.",
'undelete-header' => 'Consulte o [[Special:Log/delete|registo de eliminações]] para ver as páginas eliminadas recentemente.',
'undelete-search-title' => 'Pesquisar páginas eliminadas',
'undelete-search-box' => 'Pesquisar páginas eliminadas',
'undelete-search-prefix' => 'Mostrar páginas que começam por:',
'undelete-search-submit' => 'Pesquisar',
'undelete-no-results' => 'Não foram encontradas páginas eliminadas, para esse critério de pesquisa, no arquivo de eliminações.',
'undelete-filename-mismatch' => 'Não foi possível restaurar a versão do ficheiro de $1: nome de ficheiro não combina',
'undelete-bad-store-key' => 'Não foi possível restaurar a versão do ficheiro de $1: já não existia antes da eliminação.',
'undelete-cleanup-error' => 'Erro ao eliminar o ficheiro não utilizado "$1".',
'undelete-missing-filearchive' => 'Não é possível restaurar o ficheiro de ID $1, uma vez que ele não se encontra na base de dados. Isso pode significar que já tenha sido restaurado.',
'undelete-error' => 'Erro ao restaurar a página',
'undelete-error-short' => 'Erro ao restaurar ficheiro: $1',
'undelete-error-long' => 'Foram encontrados erros ao tentar restaurar o ficheiro:

$1',
'undelete-show-file-confirm' => 'Tem a certeza de que quer ver a revisão eliminada do ficheiro "<nowiki>$1</nowiki>" de $2 às $3?',
'undelete-show-file-submit' => 'Sim',

# Namespace form on various pages
'namespace' => 'Espaço nominal:',
'invert' => 'Inverter seleção',
'tooltip-invert' => 'Marque esta caixa para esconder as alterações a páginas no espaço nominal selecionado (e no espaço nominal associado, se escolheu fazê-lo)',
'namespace_association' => 'Espaço nominal associado',
'tooltip-namespace_association' => 'Marque esta caixa para incluir também o espaço nominal de conteúdo ou de discussão associado à sua seleção',
'blanknamespace' => '(Principal)',

# Contributions
'contributions' => 'Contribuições {{GENDER:$1|do utilizador|da utilizadora}}',
'contributions-title' => 'Contribuições {{GENDER:$1|do utilizador|da utilizadora}} $1',
'mycontris' => 'Contribuições',
'contribsub2' => 'Para {{GENDER:$3|$1}} ($2)',
'nocontribs' => 'Não foram encontradas alterações com este critério.',
'uctop' => '(atual)',
'month' => 'Até o mês:',
'year' => 'Até o ano:',

'sp-contributions-newbies' => 'Mostrar só as contribuições das contas recentes',
'sp-contributions-newbies-sub' => 'Para contas novas',
'sp-contributions-newbies-title' => 'Contribuições de contas novas',
'sp-contributions-blocklog' => 'registo de bloqueios',
'sp-contributions-deleted' => 'contribuições eliminadas',
'sp-contributions-uploads' => 'uploads',
'sp-contributions-logs' => 'registos',
'sp-contributions-talk' => 'discussão',
'sp-contributions-userrights' => 'gestão de privilégios de utilizador',
'sp-contributions-blocked-notice' => 'Este utilizador está bloqueado neste momento.
Para referência é apresentado abaixo o último registo de bloqueio:',
'sp-contributions-blocked-notice-anon' => 'Este endereço IP está bloqueado neste momento.
Para referência é apresentado abaixo o último registo de bloqueio:',
'sp-contributions-search' => 'Pesquisar contribuições',
'sp-contributions-username' => 'Endereço IP ou utilizador:',
'sp-contributions-toponly' => 'Mostrar somente as revisões mais recentes',
'sp-contributions-submit' => 'Pesquisar',

# What links here
'whatlinkshere' => 'Páginas afluentes',
'whatlinkshere-title' => 'Páginas que têm links para "$1"',
'whatlinkshere-page' => 'Página:',
'linkshere' => "As seguintes páginas têm links para '''[[:$1]]''':",
'nolinkshere' => "Não existem afluentes para '''[[:$1]]''' com as condições especificadas.",
'nolinkshere-ns' => "Não existem links para '''[[:$1]]''' no espaço nominal selecionado.",
'isredirect' => 'página de redirecionamento',
'istemplate' => 'inclusão',
'isimage' => 'link para o ficheiro',
'whatlinkshere-prev' => '{{PLURAL:$1|anterior|$1 anteriores}}',
'whatlinkshere-next' => '{{PLURAL:$1|próximo|próximos $1}}',
'whatlinkshere-links' => '← links',
'whatlinkshere-hideredirs' => '$1 redirecionamentos',
'whatlinkshere-hidetrans' => '$1 transclusões',
'whatlinkshere-hidelinks' => '$1 links',
'whatlinkshere-hideimages' => '$1 links para ficheiros',
'whatlinkshere-filters' => 'Filtros',

# Block/unblock
'autoblockid' => 'Bloqueio automático nº$1',
'block' => 'Bloquear utilizador',
'unblock' => 'Desbloquear utilizador',
'blockip' => 'Bloquear utilizador',
'blockip-title' => 'Bloquear utilizador',
'blockip-legend' => 'Bloquear utilizador',
'blockiptext' => 'Utilize o formulário abaixo para bloquear o acesso de escrita a um endereço IP específico ou a um nome de utilizador.
Isto só deve ser feito para prevenir vandalismo e de acordo com a [[{{MediaWiki:Policy-url}}|política]]. Indique a seguir um motivo de bloqueio específico (por exemplo, indicando as páginas que foram alvo de vandalismo).',
'ipadressorusername' => 'Endereço IP ou nome de utilizador:',
'ipbexpiry' => 'Expiração:',
'ipbreason' => 'Motivo:',
'ipbreasonotherlist' => 'Outro motivo',
'ipbreason-dropdown' => '*Razões comuns para um bloqueio
** Inserção de informações falsas
** Remoção de conteúdos de páginas
** Inserção de "spam" para sites externos
** Inserção de conteúdo sem sentido/incompreensível nas páginas
** Comportamento intimidador/inoportuno
** Uso abusivo de contas múltiplas
** Nome de utilizador inaceitável',
'ipb-hardblock' => 'Impedir que utilizadores autenticados editem a partir deste endereço IP',
'ipbcreateaccount' => 'Impedir criação de contas de utilizador',
'ipbemailban' => 'Impedir utilizador de enviar correio electrónico',
'ipbenableautoblock' => 'Bloquear automaticamente o endereço IP mais recente deste utilizador e todos os endereços IP subsequentes a partir dos quais ele tente editar',
'ipbsubmit' => 'Bloquear este utilizador',
'ipbother' => 'Outro período:',
'ipboptions' => '2 horas:2 hours,1 dia:1 day,3 dias:3 days,1 semana:1 week,2 semanas:2 weeks,1 mês:1 month,3 meses:3 months,6 meses:6 months,1 ano:1 year,indefinido:infinite',
'ipbotheroption' => 'outro',
'ipbotherreason' => 'Outro motivo/motivo adicional:',
'ipbhidename' => 'Ocultar nome de utilizador nas edições e listas',
'ipbwatchuser' => 'Vigiar as páginas de utilizador e de discussão deste utilizador',
'ipb-disableusertalk' => 'Impedir que este utilizador edite a sua página de discussão enquanto estiver bloqueado',
'ipb-change-block' => 'Voltar a bloquear o utilizador com estes parâmetros',
'ipb-confirm' => 'Confirmar o bloqueio',
'badipaddress' => 'Endereço IP inválido',
'blockipsuccesssub' => 'Bloqueio bem sucedido',
'blockipsuccesstext' => '[[Special:Contributions/$1|$1]] foi {{GENDER:$1|bloqueado|bloqueada}}.<br />
Consulte a [[Special:BlockList|lista de bloqueios]] para rever os bloqueios.',
'ipb-blockingself' => 'Está prestes a bloquear-se a si próprio. Tem a certeza de que pretende fazê-lo?',
'ipb-confirmhideuser' => 'Está prestes a bloquear um utilizador com "Ocultar nome de utilizador/IP" ativado. Isto irá suprimir o nome do utilizador de todas as listas e entradas dos registros. Tem a certeza de que pretende fazê-lo?',
'ipb-edit-dropdown' => 'Editar motivos de bloqueio',
'ipb-unblock-addr' => 'Desbloquear $1',
'ipb-unblock' => 'Desbloquear um utilizador ou endereço IP',
'ipb-blocklist' => 'Ver bloqueios em vigência',
'ipb-blocklist-contribs' => 'Contribuições de $1',
'unblockip' => 'Desbloquear utilizador',
'unblockiptext' => 'Utilize o formulário abaixo para restaurar o acesso de escrita de um endereço IP ou utilizador previamente bloqueado.',
'ipusubmit' => 'Remover este bloqueio',
'unblocked' => '[[User:$1|$1]] foi desbloqueado',
'unblocked-range' => 'A gama $1 foi desbloqueada',
'unblocked-id' => 'O bloqueio de $1 foi removido com sucesso',
'blocklist' => 'Utilizadores bloqueados',
'ipblocklist' => 'Utilizadores bloqueados',
'ipblocklist-legend' => 'Procurar um utilizador bloqueado',
'blocklist-userblocks' => 'Esconder bloqueios de contas',
'blocklist-tempblocks' => 'Esconder bloqueios temporários',
'blocklist-addressblocks' => 'Esconder bloqueios de IP único',
'blocklist-rangeblocks' => 'Ocultar bloqueios de faixas',
'blocklist-timestamp' => 'Data e hora',
'blocklist-target' => 'Destinatário',
'blocklist-expiry' => 'Duração',
'blocklist-by' => 'Administrador que realizou o bloqueio',
'blocklist-params' => 'Parâmetros do bloqueio',
'blocklist-reason' => 'Motivo',
'ipblocklist-submit' => 'Pesquisar',
'ipblocklist-localblock' => 'Bloqueio local',
'ipblocklist-otherblocks' => '{{PLURAL:$1|Outro bloqueio|Outros bloqueios}}',
'infiniteblock' => 'infinito',
'expiringblock' => 'expira em $1 às $2',
'anononlyblock' => 'apenas anón.',
'noautoblockblock' => 'bloqueio automático desativado',
'createaccountblock' => 'criação de conta bloqueada',
'emailblock' => 'correio electrónico bloqueado',
'blocklist-nousertalk' => 'impedido de editar a própria página de discussão',
'ipblocklist-empty' => 'A lista de bloqueios encontra-se vazia.',
'ipblocklist-no-results' => 'O endereço IP ou nome de utilizador procurado não se encontra bloqueado.',
'blocklink' => 'bloquear',
'unblocklink' => 'desbloquear',
'change-blocklink' => 'alterar bloqueio',
'contribslink' => 'contribs',
'emaillink' => 'enviar correio electrónico',
'autoblocker' => 'Foi automaticamente bloqueado, pois o seu endereço IP foi recentemente usado por "[[User:$1|$1]]". O motivo apresentado para o bloqueio de $1 foi: "$2".',
'blocklogpage' => 'Registo de bloqueio',
'blocklog-showlog' => 'Este utilizador foi já bloqueado anteriormente.
O registo de bloqueios é fornecido abaixo para referência:',
'blocklog-showsuppresslog' => 'Este utilizador foi bloqueado e ocultado anteriomente.
O registo de supressão é fornecido abaixo para referência:',
'blocklogentry' => 'bloqueou "[[$1]]" por $2. $3',
'reblock-logentry' => 'modificou parâmetros de bloqueio de [[$1]] com expiração em $2. $3',
'blocklogtext' => 'Este é um registo de ações de bloqueio e desbloqueio.
Endereços IP sujeitos a bloqueio automático não estão listados.
Consulte a [[Special:BlockList|lista de bloqueios]] para obter a lista de bloqueios e banimentos atualmente válidos.',
'unblocklogentry' => 'desbloqueou $1',
'block-log-flags-anononly' => 'apenas utilizadores anónimos',
'block-log-flags-nocreate' => 'criação de contas impossibilitada',
'block-log-flags-noautoblock' => 'bloqueio automático desativado',
'block-log-flags-noemail' => 'correio electrónico bloqueado',
'block-log-flags-nousertalk' => 'impossibilitado de editar a própria página de discussão',
'block-log-flags-angry-autoblock' => 'ativado o bloqueio automático melhorado',
'block-log-flags-hiddenname' => 'nome de utilizador ocultado',
'range_block_disabled' => 'A funcionalidade de administrador para o bloqueio de gamas de IPs está desativada.',
'ipb_expiry_invalid' => 'Tempo de expiração inválido.',
'ipb_expiry_temp' => 'Bloqueios com nome de utilizador ocultado devem ser permanentes.',
'ipb_hide_invalid' => 'Não foi possível suprimir esta conta; ela poderá ter demasiadas edições.',
'ipb_already_blocked' => '"$1" já se encontra bloqueado',
'ipb-needreblock' => '$1 já se encontra bloqueado. Deseja alterar as configurações?',
'ipb-otherblocks-header' => '{{PLURAL:$1|Outro bloqueio|Outros bloqueios}}',
'unblock-hideuser' => 'Não pode desbloquear o utilizador, porque o nome deste utilizador foi ocultado.',
'ipb_cant_unblock' => 'Erro: O bloqueio com ID $1 não foi encontrado. Pode já ter sido desbloqueado.',
'ipb_blocked_as_range' => 'Erro: O IP $1 não se encontra bloqueado de forma direta e não pode ser desbloqueado deste modo. No entanto, está bloqueado como parte da gama $2, a qual pode ser desbloqueada.',
'ip_range_invalid' => 'Gama de IPs inválida.',
'ip_range_toolarge' => 'Não são permitidas gamas de IPs maiores do que /$1.',
'proxyblocker' => 'Bloqueador de proxies',
'proxyblockreason' => "O seu endereço IP foi bloqueado por ser um ''proxy'' público.
Contacte o seu fornecedor de internet ou o serviço de apoio técnico e informe-os deste grave problema de segurança, por favor.",
'sorbsreason' => "O seu endereço IP encontra-se listado como ''proxy'' aberto na DNSBL utilizada pela {{SITENAME}}.",
'sorbs_create_account_reason' => "O seu endereço IP encontra-se listado como ''proxy'' aberto na DNSBL utilizada pela {{SITENAME}}. Não pode criar uma conta",
'xffblockreason' => 'Um endereço IP presente no cabeçalho X-Forwarded-For, seja seu ou de um servidor de proxy que estiver a usar, foi bloqueado. A razão do bloqueio original foi: $1',
'cant-block-while-blocked' => 'Não pode bloquear outros utilizadores enquanto estiver bloqueado.',
'cant-see-hidden-user' => "O utilizador que está tentando bloquear já está bloqueado e oculto.
Como não tem o privilégio para ocultar utilizadores ''(hideuser)'', não pode ver ou editar o bloqueio deste utilizador.",
'ipbblocked' => 'Não pode bloquear ou desbloquear outros, porque está bloqueado',
'ipbnounblockself' => 'Não lhe é permitido desbloquear-se a si mesmo',

# Developer tools
'lockdb' => 'Bloquear a base de dados',
'unlockdb' => 'Desbloquear a base de dados',
'lockdbtext' => 'Bloquear a base de dados impede todos os utilizadores de editar páginas, mudar as suas preferências, editar a lista de páginas vigiadas e executar qualquer outra operação que altere a base de dados.
Confirme que é precisamente isso que pretende fazer e que vai desbloquear a base de dados quando a manutenção estiver concluída, por favor.',
'unlockdbtext' => 'Desbloquear a base de dados vai possibilitar a todos os utilizadores editar páginas, mudar as suas preferências, alterar as suas listas de páginas vigiadas e executar qualquer outra operação que altere a base de dados. Confirme que é isso que pretende fazer, por favor.',
'lockconfirm' => 'Sim, pretendo realmente bloquear a base de dados.',
'unlockconfirm' => 'Sim, pretendo realmente desbloquear a base de dados.',
'lockbtn' => 'Bloquear a base de dados',
'unlockbtn' => 'Desbloquear a base de dados',
'locknoconfirm' => 'Não marcou a caixa de confirmação.',
'lockdbsuccesssub' => 'Base de dados foi bloqueada',
'unlockdbsuccesssub' => 'Base de dados foi desbloqueada',
'lockdbsuccesstext' => 'A base de dados da {{SITENAME}} foi bloqueada.<br />
Lembre-se de [[Special:UnlockDB|remover o bloqueio]] após a manutenção.',
'unlockdbsuccesstext' => 'A base de dados foi desbloqueada.',
'lockfilenotwritable' => 'O ficheiro de bloqueio da base de dados não pode ser escrito.
Para bloquear ou desbloquear a base de dados, este precisa de poder ser escrito pelo servidor de internet.',
'databasenotlocked' => 'A base de dados não está bloqueada.',
'lockedbyandtime' => '(por $1 em $2 às $3)',

# Move page
'move-page' => 'Mover $1',
'move-page-legend' => 'Mover página',
'movepagetext' => "Usando o formulário abaixo pode mover esta página e todo o seu histórico de edições para uma página nova com outro nome.
A página original será transformada num redirecionamento para a página nova.
Pode corrigir de forma automática os redirecionamentos existentes que apontam para a página original.
Caso escolha não o fazer, após a operação certifique-se de que dela não resultaram  [[Special:DoubleRedirects|redirecionamentos duplos]] ou [[Special:BrokenRedirects|quebrados]].
É da sua responsabilidade verificar que os links continuam a apontar para onde é suposto que apontem.

Note que a página '''não''' será movida se já existir uma página com o novo título, a menos que esta última seja um redirecionamento sem qualquer histórico de edições.
Isto significa que pode mover uma página de volta para o seu nome original se a tiver movido por engano e que não pode mover uma página para cima de outra já existente.

'''CUIDADO!'''
Numa página popular esta operação pode representar uma mudança drástica e inesperada;
certifique-se de que compreende as consequências da mudança antes de prosseguir, por favor.",
'movepagetext-noredirectfixer' => "Usando o formulário abaixo, pode alterar o nome de uma página e mover todo o histórico desta para o nome novo.
A página antiga é transformada numa página de redirecionamento para a nova.
Verifique a existência de [[Special:DoubleRedirects|redirecionamentos duplos]] ou [[Special:BrokenRedirects|quebrados]].
É da sua responsabilidade certificar-se de que os links continuam a apontar para onde é suposto.

Note que a página '''não''' será movida se já existir uma página com o nome novo, a menos que esta página já existente esteja vazia ou seja uma página de redirecionamento e não tenha um histórico de edições.
Isto também significa que, se se tiver enganado, pode alterar o nome da página movida de volta para o seu nome original; e que não pode sobrescrever o conteúdo de uma página existente.

'''Aviso!'''
Para páginas populares, esta operação pode representar uma mudança drástica e inesperada;
certifique-se de que compreende as consequências da operação antes de continuar.",
'movepagetalktext' => "Se existir uma página de discussão associada, ela será automaticamente movida, '''a não ser que:'''
*já exista uma página de discussão com o novo título que não esteja vazia, ou
*desmarque a correspondente caixa de seleção abaixo.

Nestes casos, terá de mover a página de discussão manualmente, ou fundi-la com a existente, se assim desejar.",
'movearticle' => 'Mover página',
'moveuserpage-warning' => "'''Aviso:''' Está prestes a mover uma página de utilizador. Note que a página será apenas movida, ''sem'' alterar o nome do utilizador.",
'movenologin' => 'Não autenticado',
'movenologintext' => 'Precisa de ser um utilizador registado e [[Special:UserLogin|autenticado]] para poder mover uma página.',
'movenotallowed' => 'Não tem permissão para mover páginas.',
'movenotallowedfile' => 'Não possui permissão para mover ficheiros.',
'cant-move-user-page' => 'Não tem permissão para mover páginas de utilizador (pode mover sub-páginas).',
'cant-move-to-user-page' => 'Não tem permissão para mover uma página para uma página de utilizador (pode movê-la para uma subpágina de utilizador).',
'newtitle' => 'Para novo título',
'move-watch' => 'Vigiar esta página',
'movepagebtn' => 'Mover página',
'pagemovedsub' => 'Página movida com sucesso',
'movepage-moved' => '\'\'\'"$1" foi movida para "$2"\'\'\'',
'movepage-moved-redirect' => 'Foi criado um redirecionamento.',
'movepage-moved-noredirect' => 'A criação de um redirecionamento foi suprimida.',
'articleexists' => 'Uma página com este nome já existe, ou o nome que escolheu é inválido.
Escolha outro nome, por favor.',
'cantmove-titleprotected' => 'Não pode mover uma página para esse destino, porque o novo título foi protegido para evitar a sua criação',
'talkexists' => "'''A página em si foi movida com sucesso, mas a página de discussão não foi movida porque já existia uma com o mesmo título.
Faça a fusão manual das páginas de discussão, por favor.'''",
'movedto' => 'movido para',
'movetalk' => 'Mover também a página de discussão associada.',
'move-subpages' => 'Mover subpáginas (até $1)',
'move-talk-subpages' => 'Mover subpáginas da página de discussão (até $1)',
'movepage-page-exists' => 'A página $1 já existe e não pode ser substituída.',
'movepage-page-moved' => 'A página $1 foi movida para $2.',
'movepage-page-unmoved' => 'Não foi possível mover a página $1 para $2.',
'movepage-max-pages' => 'O limite de $1 {{PLURAL:$1|página movida|páginas movidas}} foi atingido; não será possível mover mais páginas de forma automática.',
'movelogpage' => 'Registo de movimento',
'movelogpagetext' => 'Abaixo encontra-se uma lista de páginas movidas.',
'movesubpage' => '{{PLURAL:$1|Subpágina|Subpáginas}}',
'movesubpagetext' => 'Esta página tem $1 {{PLURAL:$1|subpágina mostrada|subpáginas mostradas}} abaixo.',
'movenosubpage' => 'Esta página não tem subpáginas.',
'movereason' => 'Motivo:',
'revertmove' => 'reverter',
'delete_and_move' => 'Eliminar e mover',
'delete_and_move_text' => '==Eliminação necessária==
A página de destino ("[[:$1]]") já existe. Deseja eliminá-la de modo a poder mover?',
'delete_and_move_confirm' => 'Sim, eliminar a página',
'delete_and_move_reason' => 'Eliminada para poder mover "[[$1]]" para este título',
'selfmove' => 'Os títulos de origem e destino são iguais;
não é possível mover uma página para ela mesma.',
'immobile-source-namespace' => 'Não é possível mover páginas no espaço nominal "$1"',
'immobile-target-namespace' => 'Não é possível mover páginas para o espaço nominal "$1"',
'immobile-target-namespace-iw' => 'Um link interwikis não é um destino válido para uma movimentação de página.',
'immobile-source-page' => 'Esta página não pode ser movida.',
'immobile-target-page' => 'Não é possível mover para esse título de destino.',
'bad-target-model' => 'O destino pretendido usa um modelo de conteúdo diferente. Não é possível converter de $1 para $2.',
'imagenocrossnamespace' => 'Não é possível mover imagem para espaço nominal que não de imagens',
'nonfile-cannot-move-to-file' => 'Não é possível mover algo que não é um ficheiro para o espaço nominal de ficheiros',
'imagetypemismatch' => 'A extensão do novo ficheiro não corresponde ao seu tipo',
'imageinvalidfilename' => 'O nome do ficheiro alvo é inválido',
'fix-double-redirects' => 'Atualizar todos os redirecionamentos que apontem para o título original',
'move-leave-redirect' => 'Criar um redirecionamento',
'protectedpagemovewarning' => "'''Aviso:''' Esta página foi protegida de maneira a que apenas utilizadores com privilégio de administrador possam movê-la.
O último registo é apresentado abaixo para referência:",
'semiprotectedpagemovewarning' => "'''Nota:''' Esta página protegida de maneira a que apenas utilizadores registados possam movê-la.
O último registo é apresentado abaixo para referência:",
'move-over-sharedrepo' => '== O ficheiro existe ==
[[:$1]] já existe num repositório partilhado. Mover um ficheiro para o título [[:$1]] irá sobrepô-lo ao ficheiro partilhado.',
'file-exists-sharedrepo' => 'O nome de ficheiro que escolheu já é utilizado num repositório partilhado.
Escolha outro nome, por favor.',

# Export
'export' => 'Exportar páginas',
'exporttext' => 'Pode exportar o texto e o histórico de edições de uma página em particular para um ficheiro XML. Poderá então importar esse conteúdo noutra wiki que utilize o programa MediaWiki, através da [[Special:Import|página de importações]].

Para exportar páginas, introduza os títulos na caixa de texto abaixo (um título por linha) e selecione se deseja todas as versões, com as linhas de histórico de edições, ou apenas a edição atual e informações sobre a mais recente das edições.

Se desejar, pode utilizar um link (por exemplo, [[{{#Special:Export}}/{{MediaWiki:Mainpage}}]] para a [[{{MediaWiki:Mainpage}}]]).',
'exportall' => 'Exportar todas as páginas',
'exportcuronly' => 'Incluir apenas a edição atual, não o histórico completo',
'exportnohistory' => "----
'''Nota:''' a exportação do histórico completo de páginas através deste formulário foi desativada por afetar o desempenho do sistema.",
'exportlistauthors' => 'Incluir uma lista completa de colaboradores para cada página',
'export-submit' => 'Exportar',
'export-addcattext' => 'Adicionar à lista páginas da categoria:',
'export-addcat' => 'Adicionar',
'export-addnstext' => 'Adicionar páginas do espaço nominal:',
'export-addns' => 'Adicionar',
'export-download' => 'Gravar em ficheiro',
'export-templates' => 'Incluir predefinições',
'export-pagelinks' => 'Incluir páginas ligadas, até uma profundidade de:',

# Namespace 8 related
'allmessages' => 'Mensagens de sistema',
'allmessagesname' => 'Nome',
'allmessagesdefault' => 'Texto padrão',
'allmessagescurrent' => 'Texto atual',
'allmessagestext' => 'Esta é a lista das mensagens de sistema disponíveis no espaço nominal MediaWiki.
Se deseja colaborar na localização genérica do MediaWiki, visite [//www.mediawiki.org/wiki/Localisation MediaWiki Localisation] e a [//translatewiki.net translatewiki.net].',
'allmessagesnotsupportedDB' => "Esta página não pode ser utilizada, uma vez que '''\$wgUseDatabaseMessages''' foi desativado.",
'allmessages-filter-legend' => 'Filtro',
'allmessages-filter' => 'Filtrar pelo estado de personalização:',
'allmessages-filter-unmodified' => 'Não modificadas',
'allmessages-filter-all' => 'Todas',
'allmessages-filter-modified' => 'Modificadas',
'allmessages-prefix' => 'Filtrar pelos caracteres iniciais:',
'allmessages-language' => 'Língua:',
'allmessages-filter-submit' => 'Filtrar',

# Thumbnails
'thumbnail-more' => 'Ampliar',
'filemissing' => 'Ficheiro não encontrado',
'thumbnail_error' => 'Erro ao criar miniatura: $1',
'thumbnail_error_remote' => 'Mensagem de erro de $1 :
$2',
'djvu_page_error' => 'página DjVu inacessível',
'djvu_no_xml' => 'Não foi possível aceder ao XML para o ficheiro DjVU',
'thumbnail-temp-create' => 'Não foi possível criar o ficheiro temporário da miniatura',
'thumbnail-dest-create' => 'Não é possível gravar a miniatura no destino',
'thumbnail_invalid_params' => 'Parâmetros de miniatura inválidos',
'thumbnail_dest_directory' => 'Não foi possível criar o diretório de destino',
'thumbnail_image-type' => 'Tipo de imagem não suportado',
'thumbnail_gd-library' => 'Configuração da biblioteca GD incompleta: função $1 em falta',
'thumbnail_image-missing' => 'Ficheiro em falta: $1',

# Special:Import
'import' => 'Importar páginas',
'importinterwiki' => 'Importação transwikis',
'import-interwiki-text' => 'Selecione uma wiki e um título de página a importar.
As datas das edições e os seus editores serão mantidos.
Todas as ações de importação transwikis são registadas no [[Special:Log/import|Registo de importações]].',
'import-interwiki-source' => 'Wiki ou página de origem:',
'import-interwiki-history' => 'Copiar todo o histórico de revisões desta página',
'import-interwiki-templates' => 'Incluir todas as predefinições',
'import-interwiki-submit' => 'Importar',
'import-interwiki-namespace' => 'Espaço nominal de destino:',
'import-interwiki-rootpage' => 'Raiz da página de destino (opcional):',
'import-upload-filename' => 'Nome do ficheiro:',
'import-comment' => 'Comentário:',
'importtext' => 'Exporte o ficheiro da wiki de origem utilizando a página especial [[Special:Export|exportação de páginas]].
Grave o ficheiro no seu computador e importe-o aqui.',
'importstart' => 'Importando páginas...',
'import-revision-count' => '{{PLURAL:$1|uma edição|$1 edições}}',
'importnopages' => 'Não existem páginas a importar.',
'imported-log-entries' => '{{PLURAL:$1|Foi importada $1 entrada|Foram importadas $1 entradas}} de registo.',
'importfailed' => 'A importação falhou: $1',
'importunknownsource' => 'Tipo da fonte de importação desconhecido',
'importcantopen' => 'Não foi possível abrir o ficheiro a importar',
'importbadinterwiki' => 'Link interwikis incorrecto',
'importnotext' => 'Vazio ou sem texto',
'importsuccess' => 'Importação completa!',
'importhistoryconflict' => 'Existem conflitos de edições no histórico (talvez esta página já tenha sido importada anteriormente)',
'importnosources' => 'Não foram definidas fontes de importação transwikis e o carregamento direto de históricos encontra-se desativado.',
'importnofile' => 'Nenhum ficheiro de importação foi carregado.',
'importuploaderrorsize' => 'O carregamento do ficheiro importado falhou.
O ficheiro é maior do que o tamanho máximo permitido.',
'importuploaderrorpartial' => 'O carregamento do ficheiro importado falhou.
O ficheiro foi recebido parcialmente.',
'importuploaderrortemp' => 'O carregamento do ficheiro importado falhou.
Não há um diretório temporário.',
'import-parse-failure' => 'Falha ao importar dados XML',
'import-noarticle' => 'Sem páginas para importar!',
'import-nonewrevisions' => 'Todas as revisões já tinham sido importadas anteriormente.',
'xml-error-string' => '$1 na linha $2, coluna $3 (byte $4): $5',
'import-upload' => 'Enviar dados em XML',
'import-token-mismatch' => 'Perda dos dados da sessão. Tente novamente, por favor.',
'import-invalid-interwiki' => 'Não é possível importar da wiki especificada.',
'import-error-edit' => 'A página "$1" não foi importada porque você não tem permissão para editá-la.',
'import-error-create' => 'A página "$1" não foi importada porque você não tem permissão para criá-la.',
'import-error-interwiki' => 'A página "$1" não pode ser importada pois seu nome está reservado para um link externo (interwiki).',
'import-error-special' => 'A página "$1" não pode ser importada porque ela pertence a um espaço nominal especial que não permite páginas.',
'import-error-invalid' => 'A página "$1" não pode ser importada porque seu nome é inválido.',
'import-error-unserialize' => 'Revisão $2 da página "$1" não pode ser desserializada. Foi relatado que a revisão usava o modelo de conteúdo $3 serializado como $4.',
'import-options-wrong' => '{{PLURAL:$2|Opção errada|Opções erradas}}: <nowiki>$1</nowiki>',
'import-rootpage-invalid' => 'A raiz da página dada é um título inválido.',
'import-rootpage-nosubpage' => 'O domínio "$1" da página de raiz não permite subpáginas.',

# Import log
'importlogpage' => 'Registo de importações',
'importlogpagetext' => 'Importações administrativas de páginas com a preservação do histórico de edição de outras wikis.',
'import-logentry-upload' => 'importou [[$1]] por upload de ficheiro',
'import-logentry-upload-detail' => '{{PLURAL:$1|uma edição|$1 edições}}',
'import-logentry-interwiki' => 'transwikis $1',
'import-logentry-interwiki-detail' => '{{PLURAL:$1|$1 edição|$1 edições}} de $2',

# JavaScriptTest
'javascripttest' => 'Teste de JavaScript',
'javascripttest-title' => 'Executando os testes $1',
'javascripttest-pagetext-noframework' => 'Esta página é reservada para a execução de testes de JavaScript.',
'javascripttest-pagetext-unknownframework' => 'Estrutura de testes "$1" desconhecido.',
'javascripttest-pagetext-frameworks' => 'Escolha, por favor, uma das seguintes estruturas de teste: $1',
'javascripttest-pagetext-skins' => 'Escolher um tema para executar os testes com:',
'javascripttest-qunit-intro' => 'Consulte a [ $1 documentação de testes] no mediawiki.org.',
'javascripttest-qunit-heading' => 'Pacote de ferramentas de teste de JavaScript QUnit do MediaWiki',

# Tooltip help for the actions
'tooltip-pt-userpage' => 'A sua página de utilizador',
'tooltip-pt-anonuserpage' => 'A página de utilizador para o endereço IP que está a usar',
'tooltip-pt-mytalk' => 'A sua página de discussão',
'tooltip-pt-anontalk' => 'Discussão sobre edições feitas a partir deste endereço IP',
'tooltip-pt-preferences' => 'Configuração dos comportamentos que prefere da wiki',
'tooltip-pt-watchlist' => 'Lista de mudanças nas páginas que está a vigiar',
'tooltip-pt-mycontris' => 'Lista das suas contribuições',
'tooltip-pt-login' => 'É encorajado a autenticar-se, apesar de não ser obrigatório.',
'tooltip-pt-anonlogin' => 'É encorajado a autenticar-se, apesar de não ser obrigatório.',
'tooltip-pt-logout' => 'Terminar esta sessão na wiki',
'tooltip-ca-talk' => 'Discussão sobre o conteúdo da página',
'tooltip-ca-edit' => 'Pode editar esta página.
Utilize o botão "Antever resultado" antes de gravar, por favor.',
'tooltip-ca-addsection' => 'Iniciar uma nova seção',
'tooltip-ca-viewsource' => 'Esta página está protegida; só pode ver o conteúdo.',
'tooltip-ca-history' => 'Edições anteriores desta página.',
'tooltip-ca-protect' => 'Proteger esta página',
'tooltip-ca-unprotect' => 'Alterar a proteção desta página',
'tooltip-ca-delete' => 'Apagar esta página',
'tooltip-ca-undelete' => 'Restaurar edições feitas a esta página antes da eliminação',
'tooltip-ca-move' => 'Mover esta página',
'tooltip-ca-watch' => 'Adicionar esta página à lista de páginas vigiadas',
'tooltip-ca-unwatch' => 'Remover esta página da lista de páginas vigiadas',
'tooltip-search' => 'Pesquisar nesta wiki',
'tooltip-search-go' => 'Ir para uma página com este nome exacto, caso exista',
'tooltip-search-fulltext' => 'Procurar páginas que contêm este texto',
'tooltip-p-logo' => 'Visite a página principal',
'tooltip-n-mainpage' => 'Visitar a página principal',
'tooltip-n-mainpage-description' => 'Visitar a página principal',
'tooltip-n-portal' => 'Sobre o projeto, o que se pode fazer e onde achar as coisas',
'tooltip-n-currentevents' => 'Informação temática sobre acontecimentos atuais',
'tooltip-n-recentchanges' => 'A lista de mudanças recentes nesta wiki.',
'tooltip-n-randompage' => 'Carregar página aleatória',
'tooltip-n-help' => 'Um local reservado para auxílio.',
'tooltip-t-whatlinkshere' => 'Lista de todas as páginas que contêm links para esta',
'tooltip-t-recentchangeslinked' => 'Mudanças recentes nas páginas para as quais esta contém links',
'tooltip-feed-rss' => "''Feed'' RSS desta página",
'tooltip-feed-atom' => "''Feed'' Atom desta página",
'tooltip-t-contributions' => 'Ver as contribuições deste utilizador',
'tooltip-t-emailuser' => 'Enviar uma mensagem de correio a este utilizador',
'tooltip-t-upload' => 'Upload de ficheiros',
'tooltip-t-specialpages' => 'Lista de páginas especiais',
'tooltip-t-print' => 'Versão para impressão desta página',
'tooltip-t-permalink' => 'Link permanente para esta versão desta página',
'tooltip-ca-nstab-main' => 'Ver a página de conteúdo',
'tooltip-ca-nstab-user' => 'Ver a página de utilizador',
'tooltip-ca-nstab-media' => 'Ver a página de multimédia',
'tooltip-ca-nstab-special' => 'Esta é uma página especial, não pode ser editada.',
'tooltip-ca-nstab-project' => 'Ver a página de projeto',
'tooltip-ca-nstab-image' => 'Ver a página do ficheiro',
'tooltip-ca-nstab-mediawiki' => 'Ver a mensagem de sistema',
'tooltip-ca-nstab-template' => 'Ver a predefinição',
'tooltip-ca-nstab-help' => 'Ver a página de ajuda',
'tooltip-ca-nstab-category' => 'Ver a página da categoria',
'tooltip-minoredit' => 'Marcar como edição menor',
'tooltip-save' => 'Gravar as alterações',
'tooltip-preview' => 'Antever as suas alterações. Use antes de gravar, por favor!',
'tooltip-diff' => 'Mostrar alterações que fez a este texto.',
'tooltip-compareselectedversions' => 'Ver as diferenças entre as duas versões selecionadas desta página.',
'tooltip-watch' => 'Adicionar esta página à lista de páginas vigiadas',
'tooltip-watchlistedit-normal-submit' => 'Remover títulos',
'tooltip-watchlistedit-raw-submit' => 'Atualizar a lista de vigiados',
'tooltip-recreate' => 'Recriar a página apesar de ter sido eliminada',
'tooltip-upload' => 'Iniciar o carregamento',
'tooltip-rollback' => '"{{int:rollbacklink}}" reverte, com um só clique, as edições do último editor desta página.',
'tooltip-undo' => '"desfazer" reverte esta edição e apresenta a página de edição no modo de antevisão.
Permite colocar uma justificação no resumo da edição.',
'tooltip-preferences-save' => 'Gravar preferências',
'tooltip-summary' => 'Introduza um resumo breve',

# Stylesheets
'common.css' => '/* Código CSS colocado aqui será aplicado a todos os temas */',
'cologneblue.css' => '/* Código CSS colocado aqui afectará os utilizadores do tema Azul colonial */',
'monobook.css' => '/* Código CSS colocado aqui afectará os utilizadores do tema Monobook */',
'modern.css' => '/* Código CSS colocado aqui afectará os utilizadores do tema Moderno */',
'vector.css' => '/* Código CSS colocado aqui afectará os utilizadores do tema Vector */',
'print.css' => '/* Código CSS colocado aqui afectará as impressões */',

# Scripts
'common.js' => '/* Código Javascript colocado aqui será carregado para todos os utilizadores em cada carregamento de página */',
'cologneblue.js' => '/* Código Javascript colocado aqui será carregado para utilizadores do tema Azul colonial */',
'monobook.js' => '/* Código Javascript colocado aqui será carregado para utilizadores do tema Monobook */',
'modern.js' => '/* Código Javascript colocado aqui será carregado para utilizadores do tema Moderno */',
'vector.js' => '/* Código Javascript colocado aqui será carregado para utilizadores do tema Vector */',

# Metadata
'notacceptable' => 'O servidor não pode fornecer os dados num formato que o seu cliente possa ler.',

# Attribution
'anonymous' => '{{PLURAL:$1|Utilizador anónimo|Utilizadores anónimos}} da {{SITENAME}}',
'siteuser' => '$1 da {{SITENAME}}',
'anonuser' => 'utilizador anónimo $1 da {{SITENAME}}',
'lastmodifiedatby' => 'Esta página foi modificada pela última vez à(s) $2 de $1 por $3.',
'othercontribs' => 'Baseado no trabalho de $1.',
'others' => 'outros',
'siteusers' => '{{PLURAL:$2|um utilizador|$2 utilizadores}} da {{SITENAME}} ($1)',
'anonusers' => '{{PLURAL:$2|utilizador anónimo|utilizadores anónimos}} da {{SITENAME}} ($1)',
'creditspage' => 'Créditos da página',
'nocredits' => 'Não há informação disponível sobre os créditos desta página.',

# Spam protection
'spamprotectiontitle' => 'Filtro de proteção contra spam',
'spamprotectiontext' => "A página que deseja gravar foi bloqueada pelo filtro de ''spam''.
Este bloqueio foi provavelmente causado por um link para um site externo que consta da lista negra.",
'spamprotectionmatch' => 'O seguinte texto activou o filtro de spam: $1',
'spambot_username' => 'MediaWiki limpeza de spam',
'spam_reverting' => 'A reverter para a última revisão que não contém links para $1',
'spam_blanking' => 'Todas as revisões continham links para $1; a esvaziar',
'spam_deleting' => 'Todas as revisões continham links para $1; a eliminar',

# Info page
'pageinfo-title' => 'Informações sobre "$1"',
'pageinfo-not-current' => 'Desculpe, é impossível fornecer esta informação para revisõe antigas.',
'pageinfo-header-basic' => 'Informação básica',
'pageinfo-header-edits' => 'Histórico de edições',
'pageinfo-header-restrictions' => 'Proteção da página',
'pageinfo-header-properties' => 'Propriedades da página',
'pageinfo-display-title' => 'Título exibido',
'pageinfo-default-sort' => 'Chave de classificação padrão',
'pageinfo-length' => 'Tamanho da página (em bytes)',
'pageinfo-article-id' => 'ID da página',
'pageinfo-language' => 'Idioma do conteúdo da página',
'pageinfo-robot-policy' => 'Indexação por robôs',
'pageinfo-robot-index' => 'Permitida',
'pageinfo-robot-noindex' => 'Não permitida',
'pageinfo-views' => 'Número de visitas',
'pageinfo-watchers' => 'Número de vigilantes da página',
'pageinfo-few-watchers' => 'Menos do que $1 {{PLURAL:$1|vigilante|vigilantes}}',
'pageinfo-redirects-name' => 'Número de redirecionamentos para esta página',
'pageinfo-subpages-name' => 'Subpáginas desta página',
'pageinfo-subpages-value' => '$1 ($2 {{PLURAL:$2|redirecionamento|redirecionamentos}}; $3 {{PLURAL:$3|não-redirecionamento|não-redirecionamentos}})',
'pageinfo-firstuser' => 'Criador da página',
'pageinfo-firsttime' => 'Data de criação da página',
'pageinfo-lastuser' => 'Último editor',
'pageinfo-lasttime' => 'Data da última edição',
'pageinfo-edits' => 'Número total de edições',
'pageinfo-authors' => 'Número total de autores distintos',
'pageinfo-recent-edits' => 'Número de edições recentes (nos últimos $1)',
'pageinfo-recent-authors' => 'Número recente de autores distintos',
'pageinfo-magic-words' => '{{PLURAL:$1|Palavra mágica|Palavras mágicas}} ($1)',
'pageinfo-hidden-categories' => '{{PLURAL:$1|Categoria oculta|Categorias ocultas}} ($1)',
'pageinfo-templates' => '{{PLURAL:$1|Predefinição|Predefinições}} transcluídas ($1)',
'pageinfo-transclusions' => '{{PLURAL:$1|Página|Páginas}} onde é transcluída ($1)',
'pageinfo-toolboxlink' => 'Informações da página',
'pageinfo-redirectsto' => 'Redireciona para',
'pageinfo-redirectsto-info' => 'informação',
'pageinfo-contentpage' => 'Contada como página de conteúdo',
'pageinfo-contentpage-yes' => 'Sim',
'pageinfo-protect-cascading' => 'A protecção é em cascata a partir daqui',
'pageinfo-protect-cascading-yes' => 'Sim',
'pageinfo-protect-cascading-from' => 'As proteções são em cascata a partir de',
'pageinfo-category-info' => 'Informações da categoria',
'pageinfo-category-pages' => 'Número de páginas',
'pageinfo-category-subcats' => 'Número de subcategorias',
'pageinfo-category-files' => 'Número de ficheiros',

# Skin names
'skinname-cologneblue' => 'Azul colonial',
'skinname-monobook' => 'MonoBook',
'skinname-modern' => 'Moderno',
'skinname-vector' => 'Vector',

# Patrolling
'markaspatrolleddiff' => 'Marcar como patrulhada',
'markaspatrolledtext' => 'Marcar esta página como patrulhada',
'markedaspatrolled' => 'Marcada como patrulhada',
'markedaspatrolledtext' => 'A edição selecionada de [[:$1]] foi marcada como patrulhada.',
'rcpatroldisabled' => 'Edições patrulhadas nas Mudanças Recentes desativadas',
'rcpatroldisabledtext' => 'A funcionalidade de edições patrulhadas nas Mudanças Recentes está atualmente desativada.',
'markedaspatrollederror' => 'Não é possível marcar como patrulhada',
'markedaspatrollederrortext' => 'É necessário especificar uma edição a ser marcada como patrulhada.',
'markedaspatrollederror-noautopatrol' => 'Não está autorizado a marcar as suas próprias edições como edições patrulhadas.',
'markedaspatrollednotify' => 'Esta mudança em $1 foi marcada como patrulhada.',
'markedaspatrollederrornotify' => 'A marcação como patrulhada falhou.',

# Patrol log
'patrol-log-page' => 'Registo de edições patrulhadas',
'patrol-log-header' => 'Este é um registo de edições patrulhadas.',
'log-show-hide-patrol' => '$1 registo de edições patrulhadas',

# Image deletion
'deletedrevision' => 'Apagou a versão antiga $1',
'filedeleteerror-short' => 'Erro ao eliminar ficheiro: $1',
'filedeleteerror-long' => 'Foram encontrados erros ao tentar eliminar o ficheiro:

$1',
'filedelete-missing' => 'Não é possível eliminar "$1" já que o ficheiro não existe.',
'filedelete-old-unregistered' => 'A edição de ficheiro especificada para "$1" não se encontra na base de dados.',
'filedelete-current-unregistered' => 'O ficheiro "$1" não se encontra na base de dados.',
'filedelete-archive-read-only' => 'O servidor de internet não é capaz de fazer alterações no diretório "$1".',

# Browsing diffs
'previousdiff' => '← Edição anterior',
'nextdiff' => 'Edição posterior →',

# Media information
'mediawarning' => "'''Aviso''': Este tipo de ficheiro pode conter código malicioso.
Executá-lo poderá comprometer a segurança do seu sistema.",
'imagemaxsize' => "Limite de tamanho de imagens:<br />''(para páginas de descrição)''",
'thumbsize' => 'Tamanho de miniaturas:',
'widthheightpage' => '$1 × $2, $3 {{PLURAL:$3|página|páginas}}',
'file-info' => 'tamanho: $1, tipo MIME: $2',
'file-info-size' => '$1 × $2 pixels, tamanho: $3, tipo MIME: $4',
'file-info-size-pages' => '$1 × $2 pixels, tamanho do ficheiro: $3, tipo MIME: $4, $5 {{PLURAL:$5|página|páginas}}',
'file-nohires' => 'Sem resolução maior disponível.',
'svg-long-desc' => 'ficheiro SVG, de $1 × $2 pixels, tamanho: $3',
'svg-long-desc-animated' => 'ficheiro SVG animado, de $1 × $2 pixels, tamanho: $3',
'svg-long-error' => 'Ficheiro SVG inválido: $1',
'show-big-image' => 'Resolução completa',
'show-big-image-preview' => 'Tamanho desta antevisão: $1.',
'show-big-image-other' => '{{PLURAL:$2|Outra resolução|Outras resoluções}}: $1.',
'show-big-image-size' => '$1 × $2 pixels',
'file-info-gif-looped' => 'cíclico',
'file-info-gif-frames' => '$1 {{PLURAL:$1|quadro|quadros}}',
'file-info-png-looped' => 'ciclo infinito',
'file-info-png-repeat' => 'reproduzido $1 {{PLURAL:$1|vez|vezes}}',
'file-info-png-frames' => '$1 {{PLURAL:$1|fotograma|fotogramas}}',
'file-no-thumb-animation' => "'''Nota: Devido a limitações técnicas, miniaturas deste ficheiro não serão animadas.'''",
'file-no-thumb-animation-gif' => "'''Nota: Devido a limitações técnicas, miniaturas de imagens GIF de alta resolução tais como esta não serão animadas.'''",

# Special:NewFiles
'newimages' => 'Galeria de novos ficheiros',
'imagelisttext' => "Abaixo é apresentada uma lista {{PLURAL:$1|de '''um''' ficheiro, organizado|de '''$1''' ficheiros, organizados}} $2.",
'newimages-summary' => 'Esta página especial mostra os ficheiros mais recentemente enviados.',
'newimages-legend' => 'Filtrar',
'newimages-label' => 'Nome de ficheiro (ou parte dele):',
'showhidebots' => '($1 robôs)',
'noimages' => 'Nada para ver.',
'ilsubmit' => 'Pesquisar',
'bydate' => 'por data',
'sp-newimages-showfrom' => 'Mostrar novos ficheiros a partir das $2 de $1',

# Video information, used by Language::formatTimePeriod() to format lengths in the above messages
'seconds' => '{{PLURAL:$1|um segundo|$1 segundos}}',
'minutes' => '{{PLURAL:$1|um minuto|$1 minutos}}',
'hours' => '{{PLURAL:$1|uma hora|$1 horas}}',
'days' => '{{PLURAL:$1|um dia|$1 dias}}',
'weeks' => '{{PLURAL:$1|$1 semana|$1 semanas}}',
'months' => '{{PLURAL:$1|1 mês|$1 meses}}',
'years' => '{{PLURAL:$1|1 ano|$1 anos}}',
'ago' => '$1 atrás',
'just-now' => 'agora mesmo',

# Human-readable timestamps
'hours-ago' => 'há $1 {{PLURAL:$1|hora|horas}}',
'minutes-ago' => 'há $1 {{PLURAL:$1|minuto|minutos}}',
'seconds-ago' => 'há $1 {{PLURAL:$1|segundo|segundos}}',
'monday-at' => 'Segunda-feira às $1',
'tuesday-at' => 'Terça-feira às $1',
'wednesday-at' => 'Quarta-feira às $1',
'thursday-at' => 'Quinta-feira às $1',
'friday-at' => 'Sexta-feira às $1',
'saturday-at' => 'Sábado às $1',
'sunday-at' => 'Domingo às $1',
'yesterday-at' => 'Ontem às $1',

# Bad image list
'bad_image_list' => 'O formato é o seguinte:

Só são reconhecidos elementos na forma de lista (linhas começadas por *).
O primeiro link em cada linha deve apontar para o ficheiro que se pretende bloquear.
Quaisquer outros links nessa mesma linha são considerados excepções (ou seja, páginas de onde se pode aceder ao ficheiro).',

# Metadata
'metadata' => 'Metadados',
'metadata-help' => 'Este ficheiro contém informação adicional, provavelmente acrescentada pela câmara digital ou pelo digitalizador usados para criá-lo.
Caso o ficheiro tenha sido modificado a partir do seu estado original, alguns detalhes poderão não refletir completamente as mudanças efetuadas.',
'metadata-expand' => 'Mostrar detalhes adicionais',
'metadata-collapse' => 'Esconder detalhes adicionais',
'metadata-fields' => 'Os campos de metadados de imagens listados nesta mensagem serão incluídos na página de descrição da imagem quando a tabela de metadados estiver recolhida. Por omissão, outros campos estarão ocultos.
* make
* model
* datetimeoriginal
* exposuretime
* fnumber
* isospeedratings
* focallength
* artist
* copyright
* imagedescription
* gpslatitude
* gpslongitude
* gpsaltitude',

# Exif tags
'exif-imagewidth' => 'Largura',
'exif-imagelength' => 'Altura',
'exif-bitspersample' => 'Bits por componente',
'exif-compression' => 'Esquema de compressão',
'exif-photometricinterpretation' => 'Composição pixel',
'exif-orientation' => 'Orientação',
'exif-samplesperpixel' => 'Número de componentes',
'exif-planarconfiguration' => 'Arranjo de dados',
'exif-ycbcrsubsampling' => 'Percentagem de submistura do canal amarelo para o ciano',
'exif-ycbcrpositioning' => 'Posicionamento Y e C',
'exif-xresolution' => 'Resolução horizontal',
'exif-yresolution' => 'Resolução vertical',
'exif-stripoffsets' => 'Localização dos dados da imagem',
'exif-rowsperstrip' => 'Número de linhas por tira',
'exif-stripbytecounts' => 'Bytes por tira comprimida',
'exif-jpeginterchangeformat' => 'Desvio para SOI de JPEG',
'exif-jpeginterchangeformatlength' => 'Bytes de dados JPEG',
'exif-whitepoint' => 'Cromatismo do ponto branco',
'exif-primarychromaticities' => 'Cromatismo das cores primárias',
'exif-ycbcrcoefficients' => 'Coeficientes da matriz de transformação do espaço de cores',
'exif-referenceblackwhite' => 'Par de valores de referência de preto e branco',
'exif-datetime' => 'Data e hora de modificação do ficheiro',
'exif-imagedescription' => 'Título',
'exif-make' => 'Fabricante da câmara',
'exif-model' => 'Modelo da câmara',
'exif-software' => 'Software utilizado',
'exif-artist' => 'Autor',
'exif-copyright' => 'Titular dos direitos de autor',
'exif-exifversion' => 'Versão Exif',
'exif-flashpixversion' => 'Versão de Flashpix suportada',
'exif-colorspace' => 'Espaço de cores',
'exif-componentsconfiguration' => 'Significado de cada componente',
'exif-compressedbitsperpixel' => 'Modo de compressão da imagem',
'exif-pixelydimension' => 'Largura válida da imagem',
'exif-pixelxdimension' => 'Altura válida da imagem',
'exif-usercomment' => 'Comentários de utilizadores',
'exif-relatedsoundfile' => 'Ficheiro áudio relacionado',
'exif-datetimeoriginal' => 'Data e hora de geração de dados',
'exif-datetimedigitized' => 'Data e hora de digitalização',
'exif-subsectime' => 'Subsegundos DataHora',
'exif-subsectimeoriginal' => 'Subsegundos DataHoraOriginal',
'exif-subsectimedigitized' => 'Subsegundos DataHoraDigitalizado',
'exif-exposuretime' => 'Tempo de exposição',
'exif-exposuretime-format' => '$1 seg ($2)',
'exif-fnumber' => 'Número F',
'exif-exposureprogram' => 'Programa de exposição',
'exif-spectralsensitivity' => 'Sensibilidade espectral',
'exif-isospeedratings' => 'Taxa de velocidade ISO',
'exif-shutterspeedvalue' => 'Velocidade do obturador',
'exif-aperturevalue' => 'Abertura',
'exif-brightnessvalue' => 'Brilho APEX',
'exif-exposurebiasvalue' => 'Compensação da exposição',
'exif-maxaperturevalue' => 'Abertura máxima',
'exif-subjectdistance' => 'Distância do motivo',
'exif-meteringmode' => 'Modo de medição',
'exif-lightsource' => 'Fonte de luz',
'exif-flash' => 'Flash',
'exif-focallength' => 'Distância focal da lente',
'exif-subjectarea' => 'Área do motivo',
'exif-flashenergy' => 'Energia do flash',
'exif-focalplanexresolution' => 'Resolução do plano focal X',
'exif-focalplaneyresolution' => 'Resolução do plano focal Y',
'exif-focalplaneresolutionunit' => 'Unidade de resolução do plano focal',
'exif-subjectlocation' => 'Localização do motivo',
'exif-exposureindex' => 'Índice de exposição',
'exif-sensingmethod' => 'Tipo de sensor',
'exif-filesource' => 'Fonte do ficheiro',
'exif-scenetype' => 'Tipo de cena',
'exif-customrendered' => 'Processamento de imagem personalizado',
'exif-exposuremode' => 'Modo de exposição',
'exif-whitebalance' => 'Balanço de brancos',
'exif-digitalzoomratio' => 'Proporção do zoom digital',
'exif-focallengthin35mmfilm' => 'Distância focal em filme de 35 mm',
'exif-scenecapturetype' => 'Tipo de captura de cena',
'exif-gaincontrol' => 'Controlo de cena',
'exif-contrast' => 'Contraste',
'exif-saturation' => 'Saturação',
'exif-sharpness' => 'Nitidez',
'exif-devicesettingdescription' => 'Descrição das configurações do dispositivo',
'exif-subjectdistancerange' => 'Distância de alcance do motivo',
'exif-imageuniqueid' => 'Identificação única da imagem',
'exif-gpsversionid' => 'Versão de GPS',
'exif-gpslatituderef' => 'Latitude Norte ou Sul',
'exif-gpslatitude' => 'Latitude',
'exif-gpslongituderef' => 'Longitude Este ou Oeste',
'exif-gpslongitude' => 'Longitude',
'exif-gpsaltituderef' => 'Referência de altitude',
'exif-gpsaltitude' => 'Altitude',
'exif-gpstimestamp' => 'Tempo GPS (relógio atómico)',
'exif-gpssatellites' => 'Satélites utilizados para a medição',
'exif-gpsstatus' => 'Estado do receptor',
'exif-gpsmeasuremode' => 'Modo da medição',
'exif-gpsdop' => 'Precisão da medição',
'exif-gpsspeedref' => 'Unidade da velocidade',
'exif-gpsspeed' => 'Velocidade do receptor GPS',
'exif-gpstrackref' => 'Referência para a direção do movimento',
'exif-gpstrack' => 'Direção do movimento',
'exif-gpsimgdirectionref' => 'Referência para a direção da imagem',
'exif-gpsimgdirection' => 'Direção da imagem',
'exif-gpsmapdatum' => 'Utilizados dados do estudo Geodetic',
'exif-gpsdestlatituderef' => 'Referência para a latitude do destino',
'exif-gpsdestlatitude' => 'Latitude do destino',
'exif-gpsdestlongituderef' => 'Referência para a longitude do destino',
'exif-gpsdestlongitude' => 'Longitude do destino',
'exif-gpsdestbearingref' => 'Referência para o azimute do destino',
'exif-gpsdestbearing' => 'Azimute do destino',
'exif-gpsdestdistanceref' => 'Referência de distância para o destino',
'exif-gpsdestdistance' => 'Distância para o destino',
'exif-gpsprocessingmethod' => 'Nome do método de processamento do GPS',
'exif-gpsareainformation' => 'Nome da área do GPS',
'exif-gpsdatestamp' => 'Data do GPS',
'exif-gpsdifferential' => 'Correcção do diferencial do GPS',
'exif-jpegfilecomment' => 'Comentário de ficheiro JPEG',
'exif-keywords' => 'Termos-chave',
'exif-worldregioncreated' => 'Região do mundo onde a fotografia foi tirada',
'exif-countrycreated' => 'País onde a fotografia foi tirada',
'exif-countrycodecreated' => 'Código do país onde a fotografia foi tirada',
'exif-provinceorstatecreated' => 'Concelho, província ou estado onde a fotografia foi tirada',
'exif-citycreated' => 'Cidade onde a fotografia foi tirada',
'exif-sublocationcreated' => 'Parte da cidade onde a fotografia foi tirada',
'exif-worldregiondest' => 'Região do mundo fotografada',
'exif-countrydest' => 'País fotografado',
'exif-countrycodedest' => 'Código do país fotografado',
'exif-provinceorstatedest' => 'Concelho, província ou estado fotografado',
'exif-citydest' => 'Cidade fotografada',
'exif-sublocationdest' => 'Parte da cidade fotografada',
'exif-objectname' => 'Título curto',
'exif-specialinstructions' => 'Instruções especiais',
'exif-headline' => 'Título',
'exif-credit' => 'Atribuição/Fornecedor',
'exif-source' => 'Fonte',
'exif-editstatus' => 'Estatuto editorial da imagem',
'exif-urgency' => 'Urgência',
'exif-fixtureidentifier' => 'Nome da coluna',
'exif-locationdest' => 'Localização representada',
'exif-locationdestcode' => 'Código da localização representada',
'exif-objectcycle' => 'Altura do dia para a qual o conteúdo está direccionado',
'exif-contact' => 'Informação de contacto',
'exif-writer' => 'Escritor',
'exif-languagecode' => 'Língua',
'exif-iimversion' => 'Versão IIM',
'exif-iimcategory' => 'Categoria',
'exif-iimsupplementalcategory' => 'Categorias suplementares',
'exif-datetimeexpires' => 'Não utilizar após',
'exif-datetimereleased' => 'Publicada a',
'exif-originaltransmissionref' => 'Código original do local de transmissão',
'exif-identifier' => 'Identificador',
'exif-lens' => 'Lente usada',
'exif-serialnumber' => 'Número de série da câmara',
'exif-cameraownername' => 'Proprietário da câmara',
'exif-label' => 'Etiqueta',
'exif-datetimemetadata' => 'Data da última modificação dos metadados',
'exif-nickname' => 'Nome informal da imagem',
'exif-rating' => 'Classificação (max. 5)',
'exif-rightscertificate' => 'Certificado de gestão dos direitos',
'exif-copyrighted' => 'Estado dos direitos de autor:',
'exif-copyrightowner' => 'Titular dos direitos de autor',
'exif-usageterms' => 'Condições de uso',
'exif-webstatement' => 'Declaração na internet dos direitos de autor',
'exif-originaldocumentid' => 'Identificação exclusiva do documento original',
'exif-licenseurl' => 'URL da licença',
'exif-morepermissionsurl' => 'Informações para licenciamento alternativo',
'exif-attributionurl' => 'Ao reutilizar esta obra, coloque um link para',
'exif-preferredattributionname' => 'Ao reutilizar esta obra, faça a atribuição a',
'exif-pngfilecomment' => 'Comentário de ficheiro PNG',
'exif-disclaimer' => 'Exoneração de responsabilidade',
'exif-contentwarning' => 'Aviso sobre o conteúdo',
'exif-giffilecomment' => 'Comentário de ficheiro GIF',
'exif-intellectualgenre' => 'Género de conteúdo',
'exif-subjectnewscode' => 'Código do tema',
'exif-scenecode' => 'Código de cena IPTC',
'exif-event' => 'Evento retratado',
'exif-organisationinimage' => 'Organização retratada',
'exif-personinimage' => 'Pessoa retratada',
'exif-originalimageheight' => 'Altura da imagem antes de ser cortada',
'exif-originalimagewidth' => 'Largura da imagem antes de ser cortada',

# Exif attributes
'exif-compression-1' => 'Descomprimido',
'exif-compression-2' => 'CCITT Grupo 3 1-D Codificação Unidimensional Huffman Modificado e Run Length Encoding',
'exif-compression-3' => 'CCITT Grupo 3 codificação de fax',
'exif-compression-4' => 'CCITT Grupo 4 codificação de fax',

'exif-copyrighted-true' => 'Direitos de autor reservados',
'exif-copyrighted-false' => 'Situação dos direitos autorais não definida',

'exif-unknowndate' => 'Data desconhecida',

'exif-orientation-1' => 'Normal',
'exif-orientation-2' => 'Espelhamento horizontal',
'exif-orientation-3' => 'Rodado 180°',
'exif-orientation-4' => 'Invertido na vertical',
'exif-orientation-5' => 'Rodado 90º no sentido anti-horário e invertido na vertical',
'exif-orientation-6' => 'Rodado 90° no sentido anti-horário',
'exif-orientation-7' => 'Rodado 90° no sentido horário e invertido na vertical',
'exif-orientation-8' => 'Rodado 90° no sentido horário',

'exif-planarconfiguration-1' => 'formato irregular',
'exif-planarconfiguration-2' => 'formato plano',

'exif-colorspace-65535' => 'Cor não calibrada',

'exif-componentsconfiguration-0' => 'não existe',

'exif-exposureprogram-0' => 'Não definido',
'exif-exposureprogram-1' => 'Manual',
'exif-exposureprogram-2' => 'Programa normal',
'exif-exposureprogram-3' => 'Prioridade da abertura',
'exif-exposureprogram-4' => 'Prioridade do obturador',
'exif-exposureprogram-5' => 'Programa criativo (tendência para a profundidade de campo)',
'exif-exposureprogram-6' => 'Programa de movimento (tendência para velocidade de disparo mais rápida)',
'exif-exposureprogram-7' => 'Modo de retrato (para fotografia de perto, com o fundo desfocado)',
'exif-exposureprogram-8' => 'Modo de paisagem (para fotografia de paisagens com o fundo focado)',

'exif-subjectdistance-value' => '$1 metros',

'exif-meteringmode-0' => 'Desconhecido',
'exif-meteringmode-1' => 'Média',
'exif-meteringmode-2' => 'MédiaPonderadaAoCentro',
'exif-meteringmode-3' => 'Ponto',
'exif-meteringmode-4' => 'MultiPonto',
'exif-meteringmode-5' => 'Padrão',
'exif-meteringmode-6' => 'Parcial',
'exif-meteringmode-255' => 'Outro',

'exif-lightsource-0' => 'Desconhecida',
'exif-lightsource-1' => 'Luz do dia',
'exif-lightsource-2' => 'Fluorescente',
'exif-lightsource-3' => 'Tungsténio (luz incandescente)',
'exif-lightsource-4' => 'Flash',
'exif-lightsource-9' => 'Tempo bom',
'exif-lightsource-10' => 'Tempo nublado',
'exif-lightsource-11' => 'Sombra',
'exif-lightsource-12' => 'Fluorescente luz do dia (D 5700 – 7100K)',
'exif-lightsource-13' => 'Fluorescente branco luz do dia (N 4600 – 5400K)',
'exif-lightsource-14' => 'Fluorescente branco frio (W 3900 – 4500K)',
'exif-lightsource-15' => 'Fluorescente branco (WW 3200 – 3700K)',
'exif-lightsource-17' => 'Luz normal A',
'exif-lightsource-18' => 'Luz normal B',
'exif-lightsource-19' => 'Luz normal C',
'exif-lightsource-24' => 'Tungsténio de estúdio ISO',
'exif-lightsource-255' => 'Outra fonte de luz',

# Flash modes
'exif-flash-fired-0' => 'Flash não disparou',
'exif-flash-fired-1' => 'Flash disparado',
'exif-flash-return-0' => 'nenhuma função de detecção de luz de retorno',
'exif-flash-return-2' => 'luz de retorno não detectada',
'exif-flash-return-3' => 'luz de retorno detectada',
'exif-flash-mode-1' => 'disparo de flash forçado',
'exif-flash-mode-2' => 'disparo de flash suprimido',
'exif-flash-mode-3' => 'modo auto',
'exif-flash-function-1' => 'Sem função de flash',
'exif-flash-redeye-1' => 'modo de redução de olhos vermelhos',

'exif-focalplaneresolutionunit-2' => 'polegadas',

'exif-sensingmethod-1' => 'Indefinido',
'exif-sensingmethod-2' => 'Sensor de áreas de cores de um chip',
'exif-sensingmethod-3' => 'Sensor de áreas de cores de dois chips',
'exif-sensingmethod-4' => 'Sensor de áreas de cores de três chips',
'exif-sensingmethod-5' => 'Sensor de área sequencial de cores',
'exif-sensingmethod-7' => 'Sensor trilinear',
'exif-sensingmethod-8' => 'Sensor linear sequencial de cores',

'exif-filesource-3' => 'Câmara fotográfica digital',

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
'exif-scenecapturetype-3' => 'Cena nocturna',

'exif-gaincontrol-0' => 'Nenhum',
'exif-gaincontrol-1' => 'Ganho positivo baixo',
'exif-gaincontrol-2' => 'Ganho positivo alto',
'exif-gaincontrol-3' => 'Ganho negativo baixo',
'exif-gaincontrol-4' => 'Ganho negativo alto',

'exif-contrast-0' => 'Normal',
'exif-contrast-1' => 'Suave',
'exif-contrast-2' => 'Alto',

'exif-saturation-0' => 'Normal',
'exif-saturation-1' => 'Saturação baixa',
'exif-saturation-2' => 'Saturação alta',

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
'exif-gpslongitude-e' => 'Longitude Este',
'exif-gpslongitude-w' => 'Longitude Oeste',

# Pseudotags used for GPSAltitudeRef
'exif-gpsaltitude-above-sealevel' => '$1 {{PLURAL:$1|metro|metros}} acima do nível do mar',
'exif-gpsaltitude-below-sealevel' => '$1 {{PLURAL:$1|metro|metros}} abaixo do nível do mar',

'exif-gpsstatus-a' => 'Medição em progresso',
'exif-gpsstatus-v' => 'Interoperabilidade de medição',

'exif-gpsmeasuremode-2' => 'Medição bidimensional',
'exif-gpsmeasuremode-3' => 'Medição tridimensional',

# Pseudotags used for GPSSpeedRef
'exif-gpsspeed-k' => 'Quilómetros por hora',
'exif-gpsspeed-m' => 'Milhas por hora',
'exif-gpsspeed-n' => 'Nós',

# Pseudotags used for GPSDestDistanceRef
'exif-gpsdestdistance-k' => 'Quilómetros',
'exif-gpsdestdistance-m' => 'Milhas',
'exif-gpsdestdistance-n' => 'Miles náuticas',

'exif-gpsdop-excellent' => 'Excelente ($1)',
'exif-gpsdop-good' => 'Bom ($1)',
'exif-gpsdop-moderate' => 'Moderado ($1)',
'exif-gpsdop-fair' => 'Aceitável ($1)',
'exif-gpsdop-poor' => 'Fraco ($1)',

'exif-objectcycle-a' => 'Só de manhã',
'exif-objectcycle-p' => 'Só à tarde/noite',
'exif-objectcycle-b' => 'De manhã e à tarde/noite',

# Pseudotags used for GPSTrackRef, GPSImgDirectionRef and GPSDestBearingRef
'exif-gpsdirection-t' => 'Direção real',
'exif-gpsdirection-m' => 'Direção magnética',

'exif-ycbcrpositioning-1' => 'Centradas',
'exif-ycbcrpositioning-2' => 'Co-localizadas',

'exif-dc-contributor' => 'Colaboradores',
'exif-dc-coverage' => 'Âmbito espacial ou temporal do conteúdo',
'exif-dc-date' => 'Data(s)',
'exif-dc-publisher' => 'Editora',
'exif-dc-relation' => 'Conteúdos relacionados',
'exif-dc-rights' => 'Direitos',
'exif-dc-source' => 'Imagem fonte',
'exif-dc-type' => 'Tipo do conteúdo',

'exif-rating-rejected' => 'Rejeitado',

'exif-isospeedratings-overflow' => 'Superior a 65535',

'exif-iimcategory-ace' => 'Artes, cultura e entretenimento',
'exif-iimcategory-clj' => 'Lei e crime',
'exif-iimcategory-dis' => 'Desastres e acidentes',
'exif-iimcategory-fin' => 'Economia e negócios',
'exif-iimcategory-edu' => 'Educação',
'exif-iimcategory-evn' => 'Meio ambiente',
'exif-iimcategory-hth' => 'Saúde',
'exif-iimcategory-hum' => 'Interesse humano',
'exif-iimcategory-lab' => 'Trabalho',
'exif-iimcategory-lif' => 'Estilo de vida e lazer',
'exif-iimcategory-pol' => 'Política',
'exif-iimcategory-rel' => 'Religião e credo',
'exif-iimcategory-sci' => 'Ciência e tecnologia',
'exif-iimcategory-soi' => 'Questões sociais',
'exif-iimcategory-spo' => 'Desporto',
'exif-iimcategory-war' => 'Guerra, conflitos e agitação social',
'exif-iimcategory-wea' => 'Tempo',

'exif-urgency-normal' => 'Normal ($1)',
'exif-urgency-low' => 'Baixa ($1)',
'exif-urgency-high' => 'Alta ($1)',
'exif-urgency-other' => 'Prioridade definida pelo utilizador ($1)',

# External editor support
'edit-externally' => 'Editar este ficheiro utilizando uma aplicação externa',
'edit-externally-help' => '(Consulte as [//www.mediawiki.org/wiki/Manual:External_editors instruções de instalação] para mais informações)',

# 'all' in various places, this might be different for inflected languages
'watchlistall2' => 'todas',
'namespacesall' => 'todos',
'monthsall' => 'todos',
'limitall' => 'tudo',

# Email address confirmation
'confirmemail' => 'Confirmar endereço de correio electrónico',
'confirmemail_noemail' => 'Não tem um endereço de correio electrónico válido nas suas [[Special:Preferences|preferências de utilizador]].',
'confirmemail_text' => 'A {{SITENAME}} requer que valide o seu endereço de correio electrónico antes de usar as funcionalidades de correio.
Clique o botão abaixo para enviar uma mensagem de confirmação para o seu endereço.
A mensagem incluíra uma URL que contém um código;
insira a URL no seu browser para confirmar que o seu endereço de correio electrónico é válido.',
'confirmemail_pending' => 'Um código de confirmação já lhe foi enviado;
caso tenha criado a conta recentemente, é recomendado que aguarde alguns minutos até o receber antes de tentar pedir um novo código.',
'confirmemail_send' => 'Enviar código de confirmação',
'confirmemail_sent' => 'Correio de confirmação enviado.',
'confirmemail_oncreate' => 'Foi enviado um código de confirmação para o seu endereço de correio eletrónico.
Este código não é necessário para se autenticar no sistema, mas será necessário para ativar qualquer funcionalidade baseada no uso de correio na wiki.',
'confirmemail_sendfailed' => 'A {{SITENAME}} não conseguiu enviar a mensagem de confirmação.
Verifique se o seu endereço de correio electrónico tem caracteres inválidos.

O sistema de correio devolveu o erro: $1',
'confirmemail_invalid' => 'Código de confirmação inválido. O código poderá ter expirado.',
'confirmemail_needlogin' => 'Precisa de $1 para confirmar o seu endereço de correio electrónico.',
'confirmemail_success' => 'O seu endereço de correio electrónico foi confirmado.
Pode agora [[Special:UserLogin|autenticar-se]] e desfrutar da wiki.',
'confirmemail_loggedin' => 'O seu endereço de correio electrónico foi confirmado.',
'confirmemail_error' => 'Alguma coisa correu mal ao gravar a sua confirmação.',
'confirmemail_subject' => 'Confirmação de endereço de correio electrónico da {{SITENAME}}',
'confirmemail_body' => 'Alguém, provavelmente você a partir do endereço IP $1,
registrou uma conta "$2" com este endereço de correio eletrónico na {{SITENAME}}.

Para confirmar que esta conta é realmente sua e ativar
as funcionalidades de correio eletrónico na {{SITENAME}},
abra o seguinte link no seu browser:

$3

Se a conta *não* é sua, abra o seguinte link para cancelar
a confirmação do endereço de correio eletrónico:

$5

Este código de confirmação expira a $4.',
'confirmemail_body_changed' => 'Alguém, provavelmente você a partir do endereço IP $1,
alterou o endereço de correio eletrónico da conta "$2" para este na {{SITENAME}}.

Para confirmar que esta conta é realmente sua e reativar
as funcionalidades de correio eletrónico na {{SITENAME}},
abra o seguinte link no seu browser:

$3

Caso a conta *não* lhe pertença, abra o seguinte link
para cancelar a confirmação do endereço de correio eletrónico:

$5

Este código de confirmação expira a $4.',
'confirmemail_body_set' => 'Alguém, provavelmente você a partir do endereço IP $1,
definiu o seu endereço de correio eletrónico como correio da conta "$2" na {{SITENAME}}.

Para confirmar que esta conta é realmente sua e reativar
as funcionalidades de correio eletrónico na {{SITENAME}},
abra o seguinte link no seu browser:

$3

Caso a conta *não* lhe pertença, abra o seguinte link
para cancelar a confirmação do endereço de correio eletrónico:

$5

Este código de confirmação expira a $4.',
'confirmemail_invalidated' => 'Confirmação de endereço de correio electrónico cancelada',
'invalidateemail' => 'Cancelar confirmação de correio electrónico',

# Scary transclusion
'scarytranscludedisabled' => '[Transclusão interwikis foi impossibilitada]',
'scarytranscludefailed' => '[Não foi possível obter a predefinição a partir de $1]',
'scarytranscludefailed-httpstatus' => '[Não foi possível obter a predefinição a partir de $1: HTTP $2]',
'scarytranscludetoolong' => '[URL longa demais]',

# Delete conflict
'deletedwhileediting' => "'''Aviso''': Esta página foi eliminada após ter começado a editá-la!",
'confirmrecreate' => "Enquanto você editava esta página, o utilizador [[User:$1|$1]] ([[User talk:$1|Discussão]]) eliminou-a pelo seguinte motivo:
: ''$2''
Confirme que deseja realmente recriar esta página, por favor.",
'confirmrecreate-noreason' => 'O utilizador [[User:$1|$1]] ([[User talk:$1|discussão]]) eliminou esta página depois de você ter começado a editá-la. Confirme que deseja recriar a página, por favor.',
'recreate' => 'Recriar',

# action=purge
'confirm_purge_button' => 'OK',
'confirm-purge-top' => 'Limpar a memória cache desta página?',
'confirm-purge-bottom' => 'Purgar uma página, limpa a cache e força a sua versão mais recente a aparecer.',

# action=watch/unwatch
'confirm-watch-button' => 'OK',
'confirm-watch-top' => 'Adicionar esta página às suas páginas vigiadas?',
'confirm-unwatch-button' => 'OK',
'confirm-unwatch-top' => 'Remover esta página das páginas vigiadas?',

# Multipage image navigation
'imgmultipageprev' => '← página anterior',
'imgmultipagenext' => 'página seguinte →',
'imgmultigo' => 'Ir!',
'imgmultigoto' => 'Ir para a página $1',

# Table pager
'ascending_abbrev' => 'asc',
'descending_abbrev' => 'desc',
'table_pager_next' => 'Página seguinte',
'table_pager_prev' => 'Página anterior',
'table_pager_first' => 'Primeira página',
'table_pager_last' => 'Última página',
'table_pager_limit' => 'Mostrar $1 por página',
'table_pager_limit_label' => 'Entradas por página:',
'table_pager_limit_submit' => 'Ir',
'table_pager_empty' => 'Sem resultados',

# Auto-summaries
'autosumm-blank' => 'Limpou toda a página',
'autosumm-replace' => "Página substituída por '$1'",
'autoredircomment' => 'Redirecionamento para [[$1]]',
'autosumm-new' => "Criou página com: '$1'",

# Live preview
'livepreview-loading' => 'A carregar…',
'livepreview-ready' => 'A carregando… Terminado!',
'livepreview-failed' => 'A antevisão instantânea falhou!
Tente a antevisão normal.',
'livepreview-error' => 'Falha ao ligar: $1 "$2"
Tente a antevisão normal.',

# Friendlier slave lag warnings
'lag-warn-normal' => 'Alterações realizadas {{PLURAL:$1|no último segundo|nos últimos $1 segundos}} podem não constar desta lista.',
'lag-warn-high' => 'Devido a latência elevada no acesso ao servidor da base de dados, as alterações realizadas {{PLURAL:$1|no último segundo|nos últimos $1 segundos}} podem não constar desta lista.',

# Watchlist editor
'watchlistedit-numitems' => 'A sua lista de páginas vigiadas contém {{PLURAL:$1|uma página|$1 páginas}}, excluindo páginas de discussão.',
'watchlistedit-noitems' => 'A sua lista de páginas vigiadas está vazia.',
'watchlistedit-normal-title' => 'Editar lista de páginas vigiadas',
'watchlistedit-normal-legend' => 'Remover páginas da lista de páginas vigiadas',
'watchlistedit-normal-explain' => 'As suas páginas vigiadas são listadas abaixo.
Para remover uma página, marque a caixa de seleção correspondente e clique o botão "{{int:Watchlistedit-normal-submit}}".
Também pode [[Special:EditWatchlist/raw|editar a lista de páginas vigiadas em forma de texto]].',
'watchlistedit-normal-submit' => 'Remover páginas',
'watchlistedit-normal-done' => '{{PLURAL:$1|Foi removida uma página|Foram removidas $1 páginas}} da sua lista de páginas vigiadas:',
'watchlistedit-raw-title' => 'Editar a lista de páginas vigiadas em forma de texto',
'watchlistedit-raw-legend' => 'Editar a lista de páginas vigiadas em forma de texto',
'watchlistedit-raw-explain' => 'A lista de páginas vigiadas é apresentada abaixo.
Pode adicionar ou remover linhas, para aumentar ou reduzir a lista.
Liste uma só página por linha.
Quando terminar, clique "{{int:Watchlistedit-raw-submit}}".
Também pode [[Special:EditWatchlist|editar a lista da maneira convencional]].',
'watchlistedit-raw-titles' => 'Páginas:',
'watchlistedit-raw-submit' => 'Atualizar a lista de páginas vigiadas',
'watchlistedit-raw-done' => 'A sua lista de páginas vigiadas foi atualizada.',
'watchlistedit-raw-added' => '{{PLURAL:$1|Foi adicionada uma página|Foram adicionadas $1 páginas}}:',
'watchlistedit-raw-removed' => '{{PLURAL:$1|Foi removida uma página|Foram removidas $1 páginas}}:',

# Watchlist editing tools
'watchlisttools-view' => 'Ver alterações relevantes',
'watchlisttools-edit' => 'Ver e editar a lista de páginas vigiadas',
'watchlisttools-raw' => 'Editar a lista de páginas vigiadas em forma de texto',

# Signatures
'signature' => '[[{{ns:user}}:$1|$2]] ([[{{ns:user_talk}}:$1|discussão]])',

# Core parser functions
'unknown_extension_tag' => '"$1" é uma tag de extensão desconhecida',
'duplicate-defaultsort' => 'Aviso: A chave de ordenação padrão "$2" sobrepõe-se à anterior chave de ordenação padrão "$1".',

# Special:Version
'version' => 'Versão',
'version-extensions' => 'Extensões instaladas',
'version-specialpages' => 'Páginas especiais',
'version-parserhooks' => "''Hooks'' do analisador sintático",
'version-variables' => 'Variáveis',
'version-antispam' => 'Prevenção contra spam',
'version-skins' => 'Temas',
'version-other' => 'Diversos',
'version-mediahandlers' => 'Leitura e tratamento de multimédia',
'version-hooks' => 'Hooks',
'version-parser-extensiontags' => 'Extensões do analisador sintático',
'version-parser-function-hooks' => "''Hooks'' das funções do analisador sintático",
'version-hook-name' => 'Nome do hook',
'version-hook-subscribedby' => 'Subscrito por',
'version-version' => '(Versão $1)',
'version-license' => 'Licença',
'version-poweredby-credits' => "Esta é uma wiki '''[//www.mediawiki.org/ MediaWiki]''', copyright © 2001-$1 $2.",
'version-poweredby-others' => 'outros',
'version-credits-summary' => 'Gostaríamos de reconhecer as seguintes pessoas pela sua contribuição para o [[Special:Version|MediaWiki]].',
'version-license-info' => 'O MediaWiki é software livre; pode redistribuí-lo e/ou modificá-lo nos termos da licença GNU General Public License, tal como publicada pela Free Software Foundation; tanto a versão 2 da Licença, como (por opção sua) qualquer versão posterior.

O MediaWiki é distribuído na esperança de que seja útil, mas SEM QUALQUER GARANTIA; inclusive, sem a garantia implícita da POSSIBILIDADE DE SER COMERCIALIZADO ou de ADEQUAÇÂO PARA QUALQUER FINALIDADE ESPECÍFICA. Consulte a licença GNU General Public License para mais detalhes.

Em conjunto com este programa deve ter recebido [{{SERVER}}{{SCRIPTPATH}}/COPYING uma cópia da licença GNU General Public License]; se não a recebeu, peça-a por escrito para Free Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA ou [//www.gnu.org/licenses/old-licenses/gpl-2.0.html leia-a na internet].',
'version-software' => 'Software instalado',
'version-software-product' => 'Produto',
'version-software-version' => 'Versão',
'version-entrypoints' => 'URLs de ponto de entrada',
'version-entrypoints-header-entrypoint' => 'Ponto de entrada',
'version-entrypoints-header-url' => 'URL',

# Special:Redirect
'redirect' => 'Redirecionar pelo ID do ficheiro, utilizador ou revisão',
'redirect-legend' => 'Redirecionar para um ficheiro ou página',
'redirect-summary' => 'Esta página especial redireciona a um ficheiro (dado o nome do ficheiro), a uma página (dado um ID de revisão) ou a uma página de utilizador (dado o ID do utilizador).',
'redirect-submit' => 'Ir',
'redirect-lookup' => 'Pesquisa:',
'redirect-value' => 'Valor:',
'redirect-user' => 'Identificador do utilizador',
'redirect-revision' => 'Revisão da página',
'redirect-file' => 'Nome do ficheiro',
'redirect-not-exists' => 'Valor não encontrado',

# Special:FileDuplicateSearch
'fileduplicatesearch' => 'Ficheiros duplicados',
'fileduplicatesearch-summary' => "Procure ficheiros duplicados tendo por base o seu resumo criptográfico ''(hash value)''.",
'fileduplicatesearch-legend' => 'Procurar duplicados',
'fileduplicatesearch-filename' => 'Ficheiro:',
'fileduplicatesearch-submit' => 'Pesquisar',
'fileduplicatesearch-info' => '$1 × $2 pixels<br />Tamanho: $3<br />tipo MIME: $4',
'fileduplicatesearch-result-1' => 'O ficheiro "$1" não possui cópias idênticas.',
'fileduplicatesearch-result-n' => 'O ficheiro "$1" possui {{PLURAL:$2|uma cópia idêntica|$2 cópias idênticas}}.',
'fileduplicatesearch-noresults' => 'Não foi encontrado nenhum ficheiro com o nome "$1".',

# Special:SpecialPages
'specialpages' => 'Páginas especiais',
'specialpages-note' => '----
* Páginas especiais normais.
* <span class="mw-specialpagerestricted">Páginas especiais restritas.</span>',
'specialpages-group-maintenance' => 'Relatórios de manutenção',
'specialpages-group-other' => 'Outras páginas especiais',
'specialpages-group-login' => 'Entrar / criar conta',
'specialpages-group-changes' => 'Mudanças e registos recentes',
'specialpages-group-media' => 'Listas e carregamento de ficheiros',
'specialpages-group-users' => 'Utilizadores e privilégios',
'specialpages-group-highuse' => 'Páginas muito usadas',
'specialpages-group-pages' => 'Listas de páginas',
'specialpages-group-pagetools' => 'Ferramentas de páginas',
'specialpages-group-wiki' => 'Dados e ferramentas',
'specialpages-group-redirects' => 'Pesquisas e aleatoriedade',
'specialpages-group-spam' => 'Ferramentas anti-spam',

# Special:BlankPage
'blankpage' => 'Página em branco',
'intentionallyblankpage' => 'Esta página foi intencionalmente deixada em branco',

# External image whitelist
'external_image_whitelist' => ' # Deixe esta linha exatamente como ela está<pre>
# Coloque fragmentos de expressões regulares (apenas a parte entre //) abaixo
# Estas serão comparadas com as URLs das imagens externas (com link direto)
# As que corresponderem serão apresentadas como imagens, caso contrário apenas será apresentado um link para a imagem
# As linhas que começam com um símbolo de cardinal (#) são tratadas como comentários
# Esta lista não distingue maiúsculas de minúsculas

# Coloque todos os fragmentos de expressões regulares (regex) acima desta linha. Deixe esta linha exatamente como ela está</pre>',

# Special:Tags
'tags' => 'Etiquetas de modificação válidas',
'tag-filter' => 'Filtro de [[Special:Tags|etiquetas]]:',
'tag-filter-submit' => 'Filtrar',
'tag-list-wrapper' => '([[Special:Tags|{{PLURAL:$1|Etiqueta|Etiquetas}}]]: $2)',
'tags-title' => 'Etiquetas',
'tags-intro' => 'Esta página lista as etiquetas com que o software poderá marcar uma edição, e o seu significado.',
'tags-tag' => 'Nome da etiqueta',
'tags-display-header' => 'Aparência nas listas de modificações',
'tags-description-header' => 'Descrição completa do significado',
'tags-active-header' => 'Ativa?',
'tags-hitcount-header' => 'Modificações etiquetadas',
'tags-edit' => 'editar',
'tags-hitcount' => '$1 {{PLURAL:$1|modificação|modificações}}',

# Special:ComparePages
'comparepages' => 'Comparar páginas',
'compare-selector' => 'Comparar edições da página',
'compare-page1' => 'Página 1',
'compare-page2' => 'Página 2',
'compare-rev1' => 'Edição 1',
'compare-rev2' => 'Edição 2',
'compare-submit' => 'Comparar',
'compare-invalid-title' => 'O título que especificou é inválido.',
'compare-title-not-exists' => 'O título que especificou não existe.',
'compare-revision-not-exists' => 'A revisão que especificou não existe.',

# Database error messages
'dberr-header' => 'Esta wiki tem um problema',
'dberr-problems' => 'Desculpe! Este site está com dificuldades técnicas.',
'dberr-again' => 'Experimente esperar alguns minutos e atualizar.',
'dberr-info' => '(Não foi possível contactar o servidor da base de dados: $1)',
'dberr-usegoogle' => 'Pode tentar pesquisar no Google entretanto.',
'dberr-outofdate' => 'Note que os seus índices relativos ao nosso conteúdo podem estar desatualizados.',
'dberr-cachederror' => 'A seguinte página é uma cópia em cache da página pedida e pode não estar atualizada.',

# HTML forms
'htmlform-invalid-input' => 'Existem problemas com alguns dos dados introduzidos',
'htmlform-select-badoption' => 'O valor que especificou não é uma opção válida.',
'htmlform-int-invalid' => 'O valor que especificou não é um inteiro.',
'htmlform-float-invalid' => 'O valor que especificou não é um número.',
'htmlform-int-toolow' => 'O valor que especificou é inferior ao mínimo de $1',
'htmlform-int-toohigh' => 'O valor que especificou é superior ao máximo de $1',
'htmlform-required' => 'Este valor é necessário',
'htmlform-submit' => 'Enviar',
'htmlform-reset' => 'Desfazer alterações',
'htmlform-selectorother-other' => 'Outros',
'htmlform-no' => 'Não',
'htmlform-yes' => 'Sim',
'htmlform-chosen-placeholder' => 'Selecione uma opção',

# SQLite database support
'sqlite-has-fts' => '$1 com suporte de pesquisa de texto completo',
'sqlite-no-fts' => '$1 sem suporte de pesquisa de texto completo',

# New logging system
'logentry-delete-delete' => '$1 apagou a página $3',
'logentry-delete-restore' => '$1 restaurou a página $3',
'logentry-delete-event' => '$1 alterou a visibilidade de {{PLURAL:$5|uma entrada|$5 entradas}} em $3: $4',
'logentry-delete-revision' => '$1 {{GENDER:$2|alterou}} a visibilidade de {{PLURAL:$5|uma revisão|$5 revisões}} em $3: $4',
'logentry-delete-event-legacy' => '$1 {{GENDER:$2|alterou}} a visibilidade de entradas de registo em $3',
'logentry-delete-revision-legacy' => '$1 {{GENDER:$2|alterou}} a visibilidade de revisões na página $3',
'logentry-suppress-delete' => '$1 {{GENDER:$2|suprimiu}} a página $3',
'logentry-suppress-event' => '$1 {{GENDER:$2|alterou}} secretamente a visibilidade de {{PLURAL:$5|uma entrada|$5 entradas}} em $3: $4',
'logentry-suppress-revision' => '$1 secretamente alterou a visibilidade de {{PLURAL:$5|uma revisão|$5 revisões}} em $3: $4',
'logentry-suppress-event-legacy' => '$1 {{GENDER:$2|alterou}} secretamente a visibilidade de entradas de registo em $3',
'logentry-suppress-revision-legacy' => '$1 {{GENDER:$2|alterou}} secretamente a visibilidade de revisões da página $3',
'revdelete-content-hid' => 'conteúdo oculto',
'revdelete-summary-hid' => 'sumário de edição oculto',
'revdelete-uname-hid' => 'utilizador oculto',
'revdelete-content-unhid' => 'conteúdo desocultado',
'revdelete-summary-unhid' => 'sumário de edição desocultado',
'revdelete-uname-unhid' => 'utilizador desocultado',
'revdelete-restricted' => 'restrições a administradores aplicadas',
'revdelete-unrestricted' => 'restrições a administradores removidas',
'logentry-move-move' => '$1 moveu a página $3 para $4',
'logentry-move-move-noredirect' => '$1 moveu a página $3 para $4 sem deixar um redirecionamento',
'logentry-move-move_redir' => '$1 moveu a página $3 para $4 sobre um redirecionamento',
'logentry-move-move_redir-noredirect' => '$1 moveu a página $3 para $4 sobre um redirecionamento sem deixar um redirecionamento',
'logentry-patrol-patrol' => '$1 {{GENDER:$2|marcou}} a revisão $4 da página $3 como patrulhada',
'logentry-patrol-patrol-auto' => '$1 {{GENDER:$2|marcou}} automaticamente a revisão $4 da página $3 como patrulhada',
'logentry-newusers-newusers' => 'A conta de utilizador $1 foi {{GENDER:$2|criada}}',
'logentry-newusers-create' => 'A conta de utilizador $1 foi criada',
'logentry-newusers-create2' => 'A conta de utilizador $3 foi criada por $1',
'logentry-newusers-byemail' => 'A conta de utilizador $3 foi criada por $1 e a senha foi enviada por e-mail',
'logentry-newusers-autocreate' => 'A conta de utilizador $1 foi criada automaticamente',
'logentry-rights-rights' => '$1 modificou os privilégios do utilizador $3 de $4 para $5',
'logentry-rights-rights-legacy' => '$1 alterou os grupos de $3',
'logentry-rights-autopromote' => '$1 foi automaticamente {{GENDER:$2|promovido|promovida}} de $4 a $5',
'rightsnone' => '(nenhum)',

# Feedback
'feedback-bugornote' => 'Se está pronto para descrever um problema técnico em detalhe, por favor, [$1 comunique o defeito].
Caso contrário, pode facilmente usar o formulário abaixo. O seu comentário será adicionado à página "[$3 $2]", junto com o seu nome de utilizador e o navegador que está a usar.',
'feedback-subject' => 'Assunto:',
'feedback-message' => 'Mensagem:',
'feedback-cancel' => 'Cancelar',
'feedback-submit' => 'Enviar Comentários',
'feedback-adding' => 'A acrescentar os comentários à página...',
'feedback-error1' => 'Erro: O resultado da API não foi reconhecido',
'feedback-error2' => 'Erro: A edição falhou',
'feedback-error3' => 'Erro: A API não responde',
'feedback-thanks' => 'Obrigado! O seu comentário foi adicionado à página "[ $2  $1 ]".',
'feedback-close' => 'Feito',
'feedback-bugcheck' => 'Perfeito! Verifique apenas que não é já um dos [$1 defeitos conhecidos].',
'feedback-bugnew' => 'Eu verifiquei. Comunicar um novo defeito.',

# Search suggestions
'searchsuggest-search' => 'Pesquisa',
'searchsuggest-containing' => 'contendo...',

# API errors
'api-error-badaccess-groups' => 'Não tem permissão para enviar ficheiros para esta wiki.',
'api-error-badtoken' => 'Erro interno: Chave incorrecta.',
'api-error-copyuploaddisabled' => 'O recebimento de ficheiros por URL não foi possibilitado neste servidor.',
'api-error-duplicate' => 'Já {{PLURAL:$1|existe [$2 outro ficheiro]|existem [$2 outros ficheiros]}} na wiki com o mesmo conteúdo.',
'api-error-duplicate-archive' => 'Já {{PLURAL:$1|existia no site [$2 outro ficheiro]|existiam no site [$2 alguns outros ficheiros]}} com o mesmo conteúdo, mas {{PLURAL:$1|foi|foram}} eliminados.',
'api-error-duplicate-archive-popup-title' => '{{PLURAL:$1|Ficheiro duplicado que já foi eliminado|Ficheiros duplicados que já foram eliminados}}',
'api-error-duplicate-popup-title' => '{{PLURAL:$1|Ficheiro duplicado|Ficheiros duplicados}}',
'api-error-empty-file' => 'O ficheiro que enviou está vazio.',
'api-error-emptypage' => 'Não é permitido criar páginas novas vazias.',
'api-error-fetchfileerror' => 'Erro interno: Ocorreu um problema indeterminado ao aceder ao ficheiro.',
'api-error-fileexists-forbidden' => 'Já existe um ficheiro com o nome "$1" e não pode ser substituído.',
'api-error-fileexists-shared-forbidden' => 'Já existe um ficheiro com o nome "$1" no repositório de ficheiros partilhados e não pode ser substituído.',
'api-error-file-too-large' => 'O ficheiro que enviou era demasiado grande.',
'api-error-filename-tooshort' => 'O nome do ficheiro é demasiado curto.',
'api-error-filetype-banned' => 'Este tipo de ficheiro é proibido.',
'api-error-filetype-banned-type' => '$1 {{PLURAL:$4|não é um tipo de ficheiro permitido|não são tipos de ficheiro permitidos}}. {{PLURAL:$3|O tipo de ficheiro permitido é|Os tipos de ficheiro permitidos são}} $2.',
'api-error-filetype-missing' => 'Falta a extensão do ficheiro.',
'api-error-hookaborted' => 'A modificação que tentou fazer foi cancelada por uma extensão.',
'api-error-http' => 'Erro interno: Ocorreu um problema na ligação ao servidor.',
'api-error-illegal-filename' => 'Este nome de ficheiro não é permitido.',
'api-error-internal-error' => 'Erro interno: Ocorreu um erro indeterminado na wiki ao processar o ficheiro que enviou.',
'api-error-invalid-file-key' => 'Erro interno: O ficheiro não foi encontrado no armazenamento temporário.',
'api-error-missingparam' => 'Erro interno: Há parâmetros em falta no pedido.',
'api-error-missingresult' => 'Erro interno: Não foi possível determinar se a cópia foi feita.',
'api-error-mustbeloggedin' => 'Tem de estar autenticado para enviar ficheiros.',
'api-error-mustbeposted' => 'Erro interno: O pedido necessita do HTTP POST.',
'api-error-noimageinfo' => 'O envio correu bem, mas o servidor não forneceu nenhuma informação sobre o ficheiro.',
'api-error-nomodule' => 'Erro interno: Não está definido nenhum módulo para upload de ficheiros.',
'api-error-ok-but-empty' => 'Erro interno: o servidor não respondeu.',
'api-error-overwrite' => 'Não é permitido sobrescrever um ficheiro existente.',
'api-error-stashfailed' => 'Erro interno: O servidor não conseguiu armazenar o ficheiro temporário.',
'api-error-publishfailed' => 'Erro interno: Servidor não conseguiu publicar ficheiro temporário.',
'api-error-timeout' => 'O servidor não respondeu no prazo esperado.',
'api-error-unclassified' => 'Ocorreu um erro desconhecido',
'api-error-unknown-code' => 'Erro desconhecido: "$1"',
'api-error-unknown-error' => 'Erro interno: Ocorreu um erro indeterminado ao tentar receber o ficheiro.',
'api-error-unknown-warning' => 'Aviso desconhecido: $1',
'api-error-unknownerror' => 'Erro desconhecido: "$1".',
'api-error-uploaddisabled' => 'Esta wiki não está configurada para poder receber ficheiros.',
'api-error-verification-error' => 'Este ficheiro pode estar corrompido, ou ter a extensão errada.',

# Durations
'duration-seconds' => '$1 {{PLURAL:$1|segundo|segundos}}',
'duration-minutes' => '$1 {{PLURAL:$1|minuto|minutos}}',
'duration-hours' => '$1 {{PLURAL:$1|hora|horas}}',
'duration-days' => '$1 {{PLURAL:$1|dia|dias}}',
'duration-weeks' => '$1 {{PLURAL:$1|semana|semanas}}',
'duration-years' => '$1 {{PLURAL:$1|ano|anos}}',
'duration-decades' => '$1 {{PLURAL:$1|década|décadas}}',
'duration-centuries' => '$1 {{PLURAL:$1|século|séculos}}',
'duration-millennia' => '$1 {{PLURAL:$1|milénio|milénios}}',

# Image rotation
'rotate-comment' => 'Imagem rodada em $1 {{PLURAL:$1|grau|graus}} no sentido dos ponteiros do relógio',

# Limit report
'limitreport-cputime-value' => '$1 {{PLURAL:$1|segundo|segundos}}',
'limitreport-postexpandincludesize-value' => '$1/$2 {{PLURAL:$2|byte|bytes}}',
'limitreport-templateargumentsize' => 'Tamanho dos argumentos da predefinição',
'limitreport-templateargumentsize-value' => '$1/$2 {{PLURAL:$2|byte|bytes}}',

);
