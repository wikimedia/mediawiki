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
 * @author Imperadeiro98
 * @author Indech
 * @author Jens Liebenau
 * @author Jorge Morais
 * @author Josep Maria 15.
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
 * @author Matma Rex
 * @author McDutchie
 * @author MetalBrasil
 * @author Minh Nguyen
 * @author Nemo bis
 * @author Nuno Tavares
 * @author OTAVIO1981
 * @author Opraco
 * @author Paulo Juntas
 * @author Pedroca cerebral
 * @author Polyethylen
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
 * @author Vitorvicentevalente
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
	'DoubleRedirects'           => array( 'Redireccionamentos_duplos', 'Redirecionamentos_duplos' ),
	'EditWatchlist'             => array( 'Editar_lista_de_páginas_vigiadas' ),
	'Emailuser'                 => array( 'Contactar_utilizador', 'Contactar_usuário', 'Contatar_usuário' ),
	'ExpandTemplates'           => array( 'Expandir_predefinições' ),
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
	'Userlogin'                 => array( 'Entrar' ),
	'Userlogout'                => array( 'Sair' ),
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
	'img_page'                  => array( '1', 'página=$1', 'página_$1', 'página $1', 'page=$1', 'page $1' ),
	'img_upright'               => array( '1', 'superiordireito', 'superiordireito=$1', 'superiordireito_$1', 'superiordireito $1', 'upright', 'upright=$1', 'upright $1' ),
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

