<?php
/** Brazilian Portuguese (Português do Brasil)
 *
 * @ingroup Language
 * @file
 *
 * @author Bani
 * @author Brunoy Anastasiya Seryozhenko
 * @author Carla404
 * @author Eduardo.mps
 * @author GKnedo
 * @author Heldergeovane
 * @author Leonardo.stabile
 * @author LeonardoG
 * @author Lijealso
 * @author Rodrigo Calanca Nishino
 * @author Urhixidur
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

	'dmy time' => 'H\hi\m\i\n',
	'dmy date' => 'j \d\e F \d\e Y',
	'dmy both' => 'H\hi\m\i\n \d\e j \d\e F \d\e Y',

);

$separatorTransformTable = array(',' => ' ', '.' => ',' );

$specialPageAliases = array(
	'DoubleRedirects'           => array( 'Redirecionamentos duplos', 'Redireccionamentos duplos' ),
	'BrokenRedirects'           => array( 'Redirecionamentos quebrados', 'Redireccionamentos quebrados' ),
	'Disambiguations'           => array( 'Páginas de desambiguação', 'Desambiguar', 'Desambiguações' ),
	'Userlogin'                 => array( 'Entrar', 'Login' ),
	'Userlogout'                => array( 'Sair', 'Logout' ),
	'CreateAccount'             => array( 'Criar conta' ),
	'Preferences'               => array( 'Preferências' ),
	'Watchlist'                 => array( 'Páginas vigiadas', 'Artigos vigiados', 'Vigiados' ),
	'Recentchanges'             => array( 'Mudanças recentes', 'Recentes' ),
	'Upload'                    => array( 'Carregar imagem', 'Carregar ficheiro', 'Carregar arquivo', 'Enviar' ),
	'Listfiles'                 => array( 'Lista de imagens', 'Lista de ficheiros', 'Lista de arquivos' ),
	'Newimages'                 => array( 'Imagens novas', 'Ficheiros novos', 'Arquivos novos' ),
	'Listusers'                 => array( 'Lista de usuários', 'Lista de utilizadores' ),
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
	'Wantedfiles'               => array( 'Arquivos em falta', 'Ficheiros em falta', 'Imagens em falta' ),
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
	'Ipblocklist'               => array( 'Registro de bloqueios', 'IPs bloqueados', 'Utilizadores bloqueados', 'Usuários bloqueados', 'Registo de bloqueios' ),
	'Specialpages'              => array( 'Páginas especiais' ),
	'Contributions'             => array( 'Contribuições' ),
	'Emailuser'                 => array( 'Contactar usuário', 'Contactar utilizador', 'Contatar usuário' ),
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
	'Listredirects'             => array( 'Redirecionamentos', 'Lista de redireccionamentos', 'Lista de redirecionamentos', 'Redireccionamentos' ),
	'Revisiondelete'            => array( 'Eliminar edição', 'Eliminar revisão', 'Apagar edição', 'Apagar revisão' ),
	'Unusedtemplates'           => array( 'Predefinições sem uso', 'Predefinições não utilizadas' ),
	'Randomredirect'            => array( 'Redirecionamento aleatório', 'Redireccionamento aleatório' ),
	'Mypage'                    => array( 'Minha página' ),
	'Mytalk'                    => array( 'Minha discussão' ),
	'Mycontributions'           => array( 'Minhas contribuições', 'Minhas edições' ),
	'Listadmins'                => array( 'Administradores', 'Admins', 'Lista de administradores', 'Lista de admins' ),
	'Listbots'                  => array( 'Bots', 'Lista de bots' ),
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
);

$magicWords = array(
	'redirect'              => array( '0', '#REDIRECIONAMENTO', '#REDIRECT' ),
	'currentmonth'          => array( '1', 'MESATUAL', 'CURRENTMONTH' ),
	'currentmonthname'      => array( '1', 'NOMEDOMESATUAL', 'CURRENTMONTHNAME' ),
	'currentday'            => array( '1', 'DIAATUAL', 'CURRENTDAY' ),
	'currentday2'           => array( '1', 'DIAATUAL2', 'CURRENTDAY2' ),
	'currentdayname'        => array( '1', 'NOMEDODIAATUAL', 'CURRENTDAYNAME' ),
	'currentyear'           => array( '1', 'ANOATUAL', 'CURRENTYEAR' ),
	'currenttime'           => array( '1', 'HORARIOATUAL', 'CURRENTTIME' ),
	'currenthour'           => array( '1', 'HORAATUAL', 'CURRENTHOUR' ),
	'localmonth'            => array( '1', 'MESLOCAL', 'LOCALMONTH' ),
	'localmonthname'        => array( '1', 'NOMEDOMESLOCAL', 'LOCALMONTHNAME' ),
	'localday'              => array( '1', 'DIALOCAL', 'LOCALDAY' ),
	'localday2'             => array( '1', 'DIALOCAL2', 'LOCALDAY2' ),
	'localdayname'          => array( '1', 'NOMEDODIALOCAL', 'LOCALDAYNAME' ),
	'localyear'             => array( '1', 'ANOLOCAL', 'LOCALYEAR' ),
	'localtime'             => array( '1', 'HORARIOLOCAL', 'LOCALTIME' ),
	'localhour'             => array( '1', 'HORALOCAL', 'LOCALHOUR' ),
	'numberofpages'         => array( '1', 'NUMERODEPAGINAS', 'NUMBEROFPAGES' ),
	'numberofarticles'      => array( '1', 'NUMERODEARTIGOS', 'NUMBEROFARTICLES' ),
	'numberoffiles'         => array( '1', 'NUMERODEARQUIVOS', 'NUMBEROFFILES' ),
	'numberofusers'         => array( '1', 'NUMERODEUSUARIOS', 'NUMBEROFUSERS' ),
	'numberofedits'         => array( '1', 'NUMERODEEDICOES', 'NUMBEROFEDITS' ),
	'numberofviews'         => array( '1', 'NUMERODEEXIBICOES', 'NUMBEROFVIEWS' ),
	'pagename'              => array( '1', 'NOMEDAPAGINA', 'PAGENAME' ),
	'namespace'             => array( '1', 'DOMINIO', 'NAMESPACE' ),
);

$messages = array(
# User preference toggles
'tog-underline'               => 'Sublinhar hiperligações:',
'tog-highlightbroken'         => 'Formatar links quebrados <a href="" class="new">como isto</a> (alternativa: como isto<a href="" class="internal">?</a>).',
'tog-justify'                 => 'Justificar parágrafos',
'tog-hideminor'               => 'Esconder edições secundárias nas mudanças recentes',
'tog-hidepatrolled'           => 'Esconder edições patrulhadas nas mudanças recentes',
'tog-newpageshidepatrolled'   => 'Esconder páginas patrulhadas da lista de páginas novas',
'tog-extendwatchlist'         => 'Expandir a lista de vigiados para mostrar todas as alterações aplicáveis',
'tog-usenewrc'                => 'Mudanças recentes melhoradas (JavaScript)',
'tog-numberheadings'          => 'Auto-numerar cabeçalhos',
'tog-showtoolbar'             => 'Mostrar barra de edição (JavaScript)',
'tog-editondblclick'          => 'Editar páginas quando houver clique duplo (JavaScript)',
'tog-editsection'             => 'Habilitar edição de seção via links [editar]',
'tog-editsectiononrightclick' => 'Habilitar edição de seção por clique com o botão direito no título da seção (JavaScript)',
'tog-showtoc'                 => 'Mostrar Tabela de Conteúdos (para páginas com mais de três cabeçalhos)',
'tog-rememberpassword'        => 'Lembrar senha entre sessões',
'tog-editwidth'               => 'Caixa de edição com largura completa',
'tog-watchcreations'          => 'Adicionar páginas criadas por mim à minha lista de vigiados',
'tog-watchdefault'            => 'Adicionar páginas editadas por mim à minha lista de vigiados',
'tog-watchmoves'              => 'Adicionar páginas movidas por mim à minha lista de vigiados',
'tog-watchdeletion'           => 'Adicionar páginas eliminadas por mim à minha lista de vigiados',
'tog-minordefault'            => 'Marcar todas as edições como secundárias, por padrão',
'tog-previewontop'            => 'Mostrar previsão antes da caixa de edição',
'tog-previewonfirst'          => 'Mostrar previsão na primeira edição',
'tog-nocache'                 => 'Desactivar caching de páginas',
'tog-enotifwatchlistpages'    => 'Enviar-me um email quando uma página da minha lista de vigiados for alterada',
'tog-enotifusertalkpages'     => 'Enviar-me um email quando a minha página de discussão for editada',
'tog-enotifminoredits'        => 'Enviar-me um email também quando forem edições menores',
'tog-enotifrevealaddr'        => 'Revelar o meu endereço de email nas notificações',
'tog-shownumberswatching'     => 'Mostrar o número de usuários a vigiar',
'tog-fancysig'                => 'Assinaturas sem atalhos automáticos',
'tog-externaleditor'          => 'Utilizar editor externo por padrão (apenas para usuários avançados, já que serão necessárias configurações adicionais em seus computadores)',
'tog-externaldiff'            => 'Utilizar diferenças externas por padrão (apenas para usuários avançados, já que serão necessárias configurações adicionais em seus computadores)',
'tog-showjumplinks'           => 'Activar hiperligações de acessibilidade "ir para"',
'tog-uselivepreview'          => 'Utilizar pré-visualização em tempo real (JavaScript) (Experimental)',
'tog-forceeditsummary'        => 'Avisar-me ao introduzir um sumário vazio',
'tog-watchlisthideown'        => 'Esconder as minhas edições da lista de artigos vigiados',
'tog-watchlisthidebots'       => 'Esconder edições efetuadas por robôs da lista de artigos vigiados',
'tog-watchlisthideminor'      => 'Esconder edições menores da lista de artigos vigiados',
'tog-watchlisthideliu'        => 'Ocultar edições de usuários autenticados da lista de vigiados',
'tog-watchlisthideanons'      => 'Ocultar edições de usuários anônimos da lista de vigiados',
'tog-watchlisthidepatrolled'  => 'Esconder edições patrulhadas na lista de artigos vigiados',
'tog-nolangconversion'        => 'Desabilitar conversão de variantes de idioma',
'tog-ccmeonemails'            => 'Enviar para mim cópias de e-mails que eu enviar a outros usuários',
'tog-diffonly'                => 'Não mostrar o conteúdo da página ao comparar duas edições',
'tog-showhiddencats'          => 'Exibir categorias ocultas',
'tog-norollbackdiff'          => 'Omitir diferenças depois de desfazer edições em bloco',

'underline-always'  => 'Sempre',
'underline-never'   => 'Nunca',
'underline-default' => 'Padrão do navegador',

# Dates
'sunday'        => 'Domingo',
'monday'        => 'Segunda-feira',
'tuesday'       => 'Terça-feira',
'wednesday'     => 'Quarta-feira',
'thursday'      => 'Quinta-feira',
'friday'        => 'Sexta-feira',
'saturday'      => 'Sábado',
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
'april'         => 'Abril',
'may_long'      => 'Maio',
'june'          => 'Junho',
'july'          => 'Julho',
'august'        => 'Agosto',
'september'     => 'Setembro',
'october'       => 'Outubro',
'november'      => 'Novembro',
'december'      => 'Dezembro',
'january-gen'   => 'janeiro',
'february-gen'  => 'fevereiro',
'march-gen'     => 'março',
'april-gen'     => 'Abril',
'may-gen'       => 'Maio',
'june-gen'      => 'Junho',
'july-gen'      => 'Julho',
'august-gen'    => 'Agosto',
'september-gen' => 'Setembro',
'october-gen'   => 'Outubro',
'november-gen'  => 'Novembro',
'december-gen'  => 'Dezembro',
'jan'           => 'Jan',
'feb'           => 'Fev',
'mar'           => 'Mar',
'apr'           => 'Abr',
'may'           => 'Mai',
'jun'           => 'Jun',
'jul'           => 'Jul',
'aug'           => 'Ago',
'sep'           => 'Set',
'oct'           => 'Out',
'nov'           => 'Nov',
'dec'           => 'Dez',

# Categories related messages
'pagecategories'                 => '{{PLURAL:$1|Categoria|Categorias}}',
'category_header'                => 'Páginas na categoria "$1"',
'subcategories'                  => 'Subcategorias',
'category-media-header'          => 'Multimídia na categoria "$1"',
'category-empty'                 => "''Esta categoria no momento não possui nenhuma página de conteúdo ou arquivo de multimídia.''",
'hidden-categories'              => '{{PLURAL:$1|Categoria oculta|Categorias ocultas}}',
'hidden-category-category'       => 'Categorias ocultas', # Name of the category where hidden categories will be listed
'category-subcat-count'          => '{{PLURAL:$2|Esta categoria possui apenas a sub-categoria a seguir.|Há, nesta categoria {{PLURAL:$1|uma sub-categoria|$1 sub-categorias}} (dentre um total de $2).}}',
'category-subcat-count-limited'  => 'Esta categoria possui {{PLURAL:$1|a seguinte sub-categoria|as $1 sub-categorias a seguir}}.',
'category-article-count'         => '{{PLURAL:$2|Esta categoria possui apenas a página a seguir.|Há, nesta categoria, {{PLURAL:$1|a página a seguir|as $1 páginas a seguir}} (dentre um total de $2).}}',
'category-article-count-limited' => 'Há, nesta categoria, {{PLURAL:$1|a página a seguir|as $1 páginas a seguir}}.',
'category-file-count'            => '{{PLURAL:$2|Esta categoria possui apenas o arquivo a seguir.|Há, nesta categoria, {{PLURAL:$1|o arquivo a seguir|os $1 seguintes arquivos}} (dentre um total de $2.)}}',
'category-file-count-limited'    => 'Nesta categoria há {{PLURAL:$1|um arquivo|$1 arquivos}}.',
'listingcontinuesabbrev'         => 'cont.',

'mainpagetext'      => "<big>'''MediaWiki instalado com sucesso.'''</big>",
'mainpagedocfooter' => 'Consulte o [http://meta.wikimedia.org/wiki/Help:Contents Manual de Usuário] para informações de como usar o software wiki.

== Começando ==

* [http://www.mediawiki.org/wiki/Manual:Configuration_settings Lista de opções de configuração]
* [http://www.mediawiki.org/wiki/Manual:FAQ FAQ do MediaWiki]
* [https://lists.wikimedia.org/mailman/listinfo/mediawiki-announce Lista de discussão com avisos de novas versões do MediaWiki]',

'about'          => 'Sobre',
'article'        => 'Página de conteúdo',
'newwindow'      => '(abre em uma nova janela)',
'cancel'         => 'Cancelar',
'qbfind'         => 'Procurar',
'qbbrowse'       => 'Navegar',
'qbedit'         => 'Editar',
'qbpageoptions'  => 'Esta página',
'qbpageinfo'     => 'Contexto',
'qbmyoptions'    => 'Minhas páginas',
'qbspecialpages' => 'Páginas especiais',
'moredotdotdot'  => 'Mais...',
'mypage'         => 'Minha página',
'mytalk'         => 'Minha discussão',
'anontalk'       => 'Discussão para este IP',
'navigation'     => 'Navegação',
'and'            => '&#32;e',

# Metadata in edit box
'metadata_help' => 'Metadados:',

'errorpagetitle'    => 'Erro',
'returnto'          => 'Retornar para $1.',
'tagline'           => 'De {{SITENAME}}',
'help'              => 'Ajuda',
'search'            => 'Pesquisa',
'searchbutton'      => 'Pesquisa',
'go'                => 'Ir',
'searcharticle'     => 'Ir',
'history'           => 'Histórico',
'history_short'     => 'História',
'updatedmarker'     => 'atualizado desde a minha última visita',
'info_short'        => 'Informação',
'printableversion'  => 'Versão para impressão',
'permalink'         => 'Link permanente',
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
'talkpagelinktext'  => 'Discussão',
'specialpage'       => 'Página especial',
'personaltools'     => 'Ferramentas pessoais',
'postcomment'       => 'Nova seção',
'articlepage'       => 'Ver página de conteúdo',
'talk'              => 'Discussão',
'views'             => 'Acessos',
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
'lastmodifiedat'    => 'Esta página foi modificada pela última vez às $2, $1.', # $1 date, $2 time
'viewcount'         => 'Esta página foi acessada {{PLURAL:$1|uma vez|$1 vezes}}.',
'protectedpage'     => 'Página protegida',
'jumpto'            => 'Ir para:',
'jumptonavigation'  => 'navegação',
'jumptosearch'      => 'pesquisa',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'            => 'Sobre',
'aboutpage'            => 'Project:Sobre',
'copyright'            => 'Conteúdo disponível sob $1.',
'copyrightpagename'    => 'Direitos de autor de {{SITENAME}}',
'copyrightpage'        => '{{ns:project}}:Direitos_de_autor',
'currentevents'        => 'Eventos atuais',
'currentevents-url'    => 'Project:Eventos atuais',
'disclaimers'          => 'Alerta de Conteúdo',
'disclaimerpage'       => 'Project:Aviso_geral',
'edithelp'             => 'Ajuda de edição',
'edithelppage'         => 'Help:Editar',
'faq'                  => 'FAQ',
'faqpage'              => 'Project:FAQ',
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
'badaccess-groups' => 'A ação que você requisitou está limitada a usuários {{PLURAL:$2|do grupo|de um dos seguintes grupos}}: $1.',

'versionrequired'     => 'É necessária a versão $1 do MediaWiki',
'versionrequiredtext' => 'Esta página requer a versão $1 do MediaWiki para poder ser utilizada. Consulte [[Special:Version|a página sobre a versão do sistema]].',

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
Você deve ter se enganado ao digitar a URL, ou seguiu uma ligação incorreta.
Isto também pode indicar um erro no(a) {{SITENAME}}.',
'nosuchspecialpage' => 'Esta página especial não existe',
'nospecialpagetext' => "<big>'''Você requisitou uma página especial inválida.'''</big>

Uma lista de páginas especiais válidas poderá ser encontrada em [[Special:SpecialPages|{{int:specialpages}}]].",

# General errors
'error'                => 'Erro',
'databaseerror'        => 'Erro no banco de dados',
'dberrortext'          => 'Ocorreu um erro de sintaxe de busca no banco de dados.
A última tentativa de busca no banco de dados foi:
<blockquote><tt>$1</tt></blockquote>
na função "<tt>$2</tt>".
O MySQL retornou o erro "<tt>$3: $4</tt>".',
'dberrortextcl'        => 'Ocorreu um erro de sintaxe de pesquisa no banco de dados.
A última tentativa de pesquisa no banco de dados foi:
"$1"
com a função"$2".
MySQL retornou o erro "$3: $4".',
'noconnect'            => 'Desculpe-nos! {{SITENAME}} está passando por algumas dificuldades técnicas e não pode contatar o servidor de banco de dados. <br />
$1',
'nodb'                 => 'Não foi possível selecionar o banco de dados $1',
'cachederror'          => 'O que se segue é uma cópia em cache da página solicitada podendo, por isso mesmo, estar com os dados defasados.',
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
'filecopyerror'        => 'Não foi possível copiar o arquivo "$1" para "$2".',
'filerenameerror'      => 'Não foi possível renomear o arquivo "$1" para "$2".',
'filedeleteerror'      => 'Não foi possível eliminar o arquivo "$1".',
'directorycreateerror' => 'Não foi possível criar o diretório "$1".',
'filenotfound'         => 'Não foi possível encontrar o arquivo "$1".',
'fileexistserror'      => 'Não foi possível gravar no arquivo "$1": ele já existe',
'unexpected'           => 'Valor não esperado: "$1"="$2".',
'formerror'            => 'Erro: Não foi possível enviar o formulário',
'badarticleerror'      => 'Esta ação não pode ser realizada nesta página.',
'cannotdelete'         => 'Não foi possível eliminar a página ou arquivo especificado (provavelmente por já ter sido eliminada por outra pessoa.)',
'badtitle'             => 'Título inválido',
'badtitletext'         => 'O título de página requisitado é inválido, vazio, ou uma ligação incorreta de inter-linguagem ou título inter-wiki. Pode ser que ele contenha um ou mais caracteres que não podem ser utilizados em títulos.',
'perfcached'           => 'Os dados seguintes encontram-se na cache e podem não estar atualizados.',
'perfcachedts'         => 'Os seguintes dados encontram-se armazenados na cache e foram atualizados pela última vez às $1.',
'querypage-no-updates' => 'Momentaneamente as atualizações para esta página estão desativadas. Por enquanto, os dados aqui presentes não poderão ser atualizados.',
'wrong_wfQuery_params' => 'Parâmetros incorretos para wfQuery()<br />
Function: $1<br />
Query: $2',
'viewsource'           => 'Ver código-fonte',
'viewsourcefor'        => 'para $1',
'actionthrottled'      => 'Ação controlada',
'actionthrottledtext'  => 'Como medida "anti-spam", está impedido de realizar esta operação muitas vezes em um curto espaço de tempo, e já excedeu esse limite. Por favor, tente de novo dentro de alguns minutos.',
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
'logouttitle'                => 'Desautenticar do sistema',
'logouttext'                 => "'''Agora você está desautenticado.'''

Você pode continuar usando o projeto {{SITENAME}} anonimamente, ou pode [[Special:UserLogin|se autenticar novamente]] com o mesmo nome de usuário ou com um nome de usuário diferente.
Tenha em mente que algumas páginas poderão continuar sendo exibidas como se você ainda estivesse autenticado, até que a ''cache'' de seu navegador seja limpa.",
'welcomecreation'            => '== Bem-vindo, $1! ==
A sua conta foi criada.
Não se esqueça de personalizar as suas [[Special:Preferences|preferências na {{SITENAME}}]].',
'loginpagetitle'             => 'Autenticação de usuário',
'yourname'                   => 'Nome de usuário:',
'yourpassword'               => 'Senha:',
'yourpasswordagain'          => 'Redigite sua senha',
'remembermypassword'         => 'Lembrar de minha senha em outras sessões.',
'yourdomainname'             => 'Seu domínio:',
'externaldberror'            => 'Ocorreu um erro externo ao banco de dados durante a autenticação ou não lhe é permitido atualizar a sua conta externa.',
'login'                      => 'Entrar',
'nav-login-createaccount'    => 'Criar uma conta ou entrar',
'loginprompt'                => 'É necessário estar com os <i>cookies</i> ativados para poder autenticar-se na {{SITENAME}}.',
'userlogin'                  => 'Criar uma conta ou entrar',
'logout'                     => 'Sair',
'userlogout'                 => 'Sair',
'notloggedin'                => 'Não autenticado',
'nologin'                    => 'Não possui uma conta? $1.',
'nologinlink'                => 'Criar uma conta',
'createaccount'              => 'Criar nova conta',
'gotaccount'                 => 'Já possui uma conta? $1.',
'gotaccountlink'             => 'Entrar',
'createaccountmail'          => 'por e-mail',
'badretype'                  => 'As senhas que você digitou não são iguais.',
'userexists'                 => 'O nome de usuário que você digitou já existe.
Por favor, escolha um nome diferente.',
'youremail'                  => 'Seu e-mail:',
'username'                   => 'Nome de usuário:',
'uid'                        => 'Número de identificação:',
'prefs-memberingroups'       => 'Membro {{PLURAL:$1|do grupo|dos grupos}}:',
'yourrealname'               => 'Nome verdadeiro:',
'yourlanguage'               => 'Idioma:',
'yourvariant'                => 'Variante',
'yournick'                   => 'Assinatura:',
'badsig'                     => 'Assinatura inválida; verifique o código HTML utilizado.',
'badsiglength'               => 'A sua assinatura é muito longa.
Ela deve ter menos de $1 {{PLURAL:$1|caractere|caracteres}}.',
'yourgender'                 => 'Sexo:',
'gender-unknown'             => 'Não especificado',
'gender-male'                => 'Masculino',
'gender-female'              => 'Feminino',
'prefs-help-gender'          => 'Opcional: usado para endereçamento correto pelo software baseado no sexo. Esta informação será pública.',
'email'                      => 'E-mail',
'prefs-help-realname'        => 'O fornecimento de seu Nome verdadeiro é opcional, mas, caso decida o revelar, este será utilizado para lhe dar crédito pelo seu trabalho.',
'loginerror'                 => 'Erro de autenticação',
'prefs-help-email'           => "O fornecimento de um endereço de ''e-mail'' é opcional, mas permite que uma nova senha lhe seja enviada caso você esqueça sua senha. Você pode ainda preferir deixar que os usuários entrem em contato consigo através de sua página de usuário ou discussão sem ter de revelar sua identidade.",
'prefs-help-email-required'  => 'O endereço de e-mail é requerido.',
'nocookiesnew'               => "A conta do usuário foi criada, mas você não foi autenticado.
{{SITENAME}} utiliza ''cookies'' para autenticar os usuários.
Você tem os ''cookies'' desativados no seu navegador.
Por favor ative-os, depois autentique-se com o seu novo nome de usuário e a sua senha.",
'nocookieslogin'             => 'Você tem os <i>cookies</i> desativados no seu navegador, e a {{SITENAME}} utiliza <i>cookies</i> para ligar os usuários às suas contas. Por favor os ative e tente novamente.',
'noname'                     => 'Você não colocou um nome de usuário válido.',
'loginsuccesstitle'          => 'Login bem sucedido',
'loginsuccess'               => "'''Agora você está ligado à {{SITENAME}} como \"\$1\"'''.",
'nosuchuser'                 => 'Não existe nenhum utilizador com o nome "$1".
Os nomes de utilizador são sensíveis à capitalização.
Verifique a ortografia, ou [[Special:UserLogin/signup|crie uma nova conta]].',
'nosuchusershort'            => 'Não existe um usuário com o nome "<nowiki>$1</nowiki>". Verifique o nome que introduziu.',
'nouserspecified'            => 'Você precisa especificar um nome de usuário.',
'wrongpassword'              => 'A senha que introduziu é inválida. Por favor, tente novamente.',
'wrongpasswordempty'         => 'A senha introduzida está em branco. Por favor, tente novamente.',
'passwordtooshort'           => 'A sua senha é inválida ou muito curta.
Deve de ter no mínimo {{PLURAL:$1|1 caracter|$1 caracteres}} e ser diferente do seu nome de usuário.',
'mailmypassword'             => "Enviar uma nova senha por ''e-mail''",
'passwordremindertitle'      => 'Nova senha temporária em {{SITENAME}}',
'passwordremindertext'       => 'Alguém (provavelmente você, a partir do endereço de IP $1) solicitou uma nova senha para {{SITENAME}} ($4). Foi criada uma senha temporária para o usuário "$2", sendo ela "$3". Se esta era sua intenção, você precisará se autenticar e escolher uma nova senha agora.
A sua senha temporária expirará em {{PLURAL:$5|um dia|$5 dias}}.

Se foi outra pessoa quem fez este pedido, ou se você já lembrou a sua senha, e não quer mais alterá-la, você pode ignorar esta mensagem e continuar utilizando sua senha antiga.',
'noemail'                    => 'Não há um endereço de e-mail associado ao usuário "$1".',
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
'login-throttled'            => 'Você fez muitas tentativas recentes de se autenticar com esta conta.
Por favor aguarde antes de tentar novamente.',
'loginlanguagelabel'         => 'Idioma: $1',

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
'resetpass_bad_temporary'   => 'Senha temporária incorreta. Pode ser que você já tenha conseguido alterar a sua senha ou pedido que uma nova senha temporária fosse gerada.',
'resetpass_forbidden'       => 'As senhas não podem ser alteradas',
'resetpass-no-info'         => 'Você precisa estar autenticado para acessar esta página diretamente.',
'resetpass-submit-loggedin' => 'Alterar senha',
'resetpass-wrong-oldpass'   => 'Senha temporária ou atual inválida. 
Você pode já ter alterado com sucesso a sua senha, ou solicitado uma nova senha temporária.',
'resetpass-temp-password'   => 'Senha temporária:',

# Edit page toolbar
'bold_sample'     => 'Texto em negrito',
'bold_tip'        => 'Texto em negrito',
'italic_sample'   => 'Texto em itálico',
'italic_tip'      => 'Texto em itálico',
'link_sample'     => 'Título do link',
'link_tip'        => 'Ligação interna',
'extlink_sample'  => 'http://www.example.com ligação externa',
'extlink_tip'     => 'Link externo (lembre-se do prefixo http://)',
'headline_sample' => 'Texto de cabeçalho',
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
'preview'                          => 'Prever',
'showpreview'                      => 'Mostrar previsão',
'showlivepreview'                  => 'Pré-visualização em tempo real',
'showdiff'                         => 'Mostrar alterações',
'anoneditwarning'                  => "'''Atenção''': Você não se encontra autenticado. O seu endereço de IP será registrado no histórico de edições desta página.",
'missingsummary'                   => "'''Lembrete:''' Você não introduziu um sumário de edição. Se clicar novamente em Salvar, a sua edição será salva sem um sumário.",
'missingcommenttext'               => 'Por favor, introduzida um comentário abaixo.',
'missingcommentheader'             => "'''Lembrete:''' Você não introduziu um assunto/título para este comentário. Se carregar novamente em Salvar a sua edição será salva sem um título/assunto.",
'summary-preview'                  => 'Previsão de sumário:',
'subject-preview'                  => 'Previsão de assunto/título:',
'blockedtitle'                     => 'O usuário está bloqueado',
'blockedtext'                      => '<big>O seu nome de usuário ou endereço de IP foi bloqueado</big>

O bloqueio foi realizado por $1. O motivo apresentado foi \'\'$2\'\'.

* Início do bloqueio: $8
* Expiração do bloqueio: $6
* Destino do bloqueio: $7

Você pode contatar $1 ou outro [[{{MediaWiki:Grouppage-sysop}}|administrador]] para discutir sobre o bloqueio.

Note que você só poderá utilizar a funcionalidade "Contatar usuário" se um endereço de \'\'email\'\' válido estiver especificado em suas [[Special:Preferences|preferências de usuário]] e você não tiver sido bloqueado de utilizar tal recurso.
O seu endereço de IP atual é $3 e a ID de bloqueio é $5.
Por favor, inclua todos os detalhes acima em quaisquer tentativas de esclarecimento.',
'autoblockedtext'                  => 'O seu endereço de IP foi bloqueado de forma automática, uma vez que foi utilizado recentemente por outro usuário, o qual foi bloqueado por $1.
O motivo apresentado foi:

:\'\'$2\'\'

* Início do bloqueio: $8
* Expiração do bloqueio: $6
* Destino do bloqueio: $7

Você pode contatar $1 ou outro [[{{MediaWiki:Grouppage-sysop}}|administrador]] para discutir sobre o bloqueio.

Note que não poderá utilizar a funcionalidade "Contatar utilizador" se não possuir uma conta neste wiki ({{SITENAME}}) com um endereço de \'\'e-mail\'\' válido indicado nas suas [[Special:Preferences|preferências de usuário]] ou se tiver sido bloqueado de utilizar tal recurso.

Seu endereço de IP no momento é $3 e sua ID de bloqueio é #$5.
Por favor, inclua tais dados em qualquer tentativa de esclarecimentos que for realizar.',
'blockednoreason'                  => 'sem motivo especificado',
'blockedoriginalsource'            => "O código de '''$1''' é mostrado abaixo:",
'blockededitsource'                => "O texto das '''suas edições''' em '''$1''' é mostrado abaixo:",
'whitelistedittitle'               => 'É necessário autenticar-se para editar páginas',
'whitelistedittext'                => 'Você precisa $1 para poder editar páginas.',
'confirmedittitle'                 => 'Confirmação de e-mail requerida para editar',
'confirmedittext'                  => 'Você precisa confirmar o seu endereço de e-mail antes de começar a editar páginas.
Por favor, introduza um e valide-o através das suas [[Special:Preferences|preferências de usuário]].',
'nosuchsectiontitle'               => 'Seção inexistente',
'nosuchsectiontext'                => 'Você tentou editar uma seção que não existe. Uma vez que não há a secção $1, não há um local para salvar a sua edição.',
'loginreqtitle'                    => 'Autenticação Requerida',
'loginreqlink'                     => 'autenticar-se',
'loginreqpagetext'                 => 'Você precisa de $1 para poder visualizar outras páginas.',
'accmailtitle'                     => 'Senha enviada.',
'accmailtext'                      => "Uma palavra-chave gerada aleatoriamente para [[User talk:$1|$1]] foi enviada para $2.

A palavra-chave para este nova conta pode ser alterada na página para ''[[Special:ChangePassword|alterar palavra-chave]]'' após a autenticação.",
'newarticle'                       => '(Nova)',
'newarticletext'                   => "Você seguiu um link para uma página que não existe.
Para criá-la, começe escrevendo na caixa abaixo
(veja [[{{MediaWiki:Helppage}}|a página de ajuda]] para mais informações).
Se você chegou aqui por engano, apenas clique no botão '''voltar''' do seu navegador.",
'anontalkpagetext'                 => "---- ''Esta é a página de discussão para um usuário anônimo que ainda não criou uma conta ou que não a usa, de forma que temos de utilizar o endereço de IP para identificá-lo(a). Tal endereço de IP pode ser compartilhado por vários usuários. Se você é um usuário anônimo e acha que comentários irrelevantes foram direcionados a você, por gentileza, [[Special:UserLogin/signup|crie uma conta]] ou [[Special:UserLogin|autentique-se]], a fim de evitar futuras confusões com outros usuários anônimos.''",
'noarticletext'                    => 'Atualmente não existe  texto nesta página.
Você pode [[Special:Search/{{PAGENAME}}|pesquisar pelo título desta página]] em outras páginas <span class="plainlinks">[{{fullurl:Special:Log|page={{urlencode:{{FULLPAGENAME}}}}}} buscar nos registros relacionados],
ou [{{fullurl:{{FULLPAGENAME}}|action=edit}} editar esta página]</span>.',
'userpage-userdoesnotexist'        => 'A conta "$1" não se encontra registrada. Por gentileza, verifique se deseja mesmo criar/editar esta página.',
'clearyourcache'                   => "'''Nota:''' Após salvar, terá de limpar a cache do seu navegador para ver as alterações.'''
'''Mozilla / Firefox / Safari:''' pressione ''Shift'' enquanto clica em ''Recarregar'', ou pressione ou ''Ctrl-F5'' ou ''Ctrl-R'' (''Command-R'' para Macintosh); '''Konqueror:''': clique no botão ''Recarregar'' ou pressione ''F5''; '''Opera:''' limpe a sua cache em ''Ferramentas → Preferências'' (''Tools → Preferences''); '''Internet Explorer:''' pressione ''Ctrl'' enquanto clica em ''Recarregar'' ou pressione ''Ctrl-F5'';",
'usercssjsyoucanpreview'           => "'''Dica:''' Utilize o botão \"Mostrar previsão\" para testar seu novo CSS/JS antes de salvar.",
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
'nonunicodebrowser'                => "'''AVISO: O seu navegador não é compatível com as especificações unicode. Um contorno terá de ser utilizado para permitir que você possa editar com segurança os artigos: os caracteres não-ASCII aparecerão na caixa de edição no formato de códigos hexadecimais.'''",
'editingold'                       => "'''CUIDADO: Você está editando uma revisão desatualizada deste artigo.
Se você salvá-lo, todas as mudanças feitas a partir desta revisão serão perdidas.'''",
'yourdiff'                         => 'Diferenças',
'copyrightwarning'                 => "Por favor, note que todas as suas contribuições em {{SITENAME}} são consideradas como lançadas nos termos da licença $2 (veja $1 para detalhes). Se não deseja que o seu texto seja inexoravelmente editado e redistribuído de tal forma, não o envie.<br />
Você está, ao mesmo tempo, garantindo-nos que isto é algo escrito por você mesmo ou algo copiado de uma fonte de textos em domínio público ou similarmente de teor livre.
'''NÃO ENVIE TRABALHO PROTEGIDO POR DIREITOS AUTORAIS SEM A DEVIDA PERMISSÃO!'''",
'copyrightwarning2'                => "Por favor, note que todas as suas contribuições em {{SITENAME}} podem ser editadas, alteradas ou removidas por outros contribuidores. Se você não deseja que o seu texto seja inexoravelmente editado, não o envie.<br />
Você está, ao mesmo tempo, a garantir-nos que isto é algo escrito por si, ou algo copiado de alguma fonte de textos em domínio público ou similarmente de teor livre (veja $1 para detalhes).
'''NÃO ENVIE TRABALHO PROTEGIDO POR DIREITOS DE AUTOR SEM A DEVIDA PERMISSÃO!'''",
'longpagewarning'                  => "'''CUIDADO: Esta página tem $1 kilobytes; alguns browsers podem ter problemas ao editar páginas maiores que 32 kb.
Por gentileza, considere quebrar a página em sessões menores.'''",
'longpageerror'                    => "'''ERRO: O texto de página que você submeteu tem mais de $1 kilobytes em tamanho, que é maior que o máximo de $2 kilobytes. A página não pode ser salva.'''",
'readonlywarning'                  => "'''AVISO: O banco de dados foi bloqueado para manutenção; você não poderá salvar a sua edição neste momento.
Pode, no entanto, copiar o seu texto num editor externo e guardá-lo para posterior envio.'''

Quem bloqueou o banco de dados forneceu a seguinte justificativa: $1",
'protectedpagewarning'             => "'''CUIDADO: Apenas os usuários com privilégios de sysop (Administradores) podem editar esta página pois ela foi bloqueada.'''",
'semiprotectedpagewarning'         => "'''Nota:''' Esta página foi protegida, sendo que apenas usuários registrados poderão editá-la.",
'cascadeprotectedwarning'          => "'''Atenção:''' Esta página se encontra protegida; apenas {{int:group-sysop}} podem editá-la, uma vez que se encontra incluída {{PLURAL:\$1|na seguinte página protegida|nas seguintes páginas protegidas}} com a \"proteção progressiva\":",
'titleprotectedwarning'            => "'''ATENÇÃO: Esta página foi protegida, [[Special:ListGroupRights|privilégios específicos]] são necessários para criá-la",
'templatesused'                    => 'Predefinições utilizadas nesta página:',
'templatesusedpreview'             => 'Predefinições utilizadas nesta previsão:',
'templatesusedsection'             => 'Predefinições utilizadas nesta seção:',
'template-protected'               => '(protegida)',
'template-semiprotected'           => '(semi-protegida)',
'hiddencategories'                 => 'Esta página integra {{PLURAL:$1|uma categoria oculta|$1 categorias ocultas}}:',
'edittools'                        => '<!-- O texto aqui disponibilizado será exibido abaixo dos formulários de edição e de envio de arquivos. -->',
'nocreatetitle'                    => 'A criação de páginas se encontra limitada',
'nocreatetext'                     => '{{SITENAME}} tem restringida a habilidade de criar novas páginas.
Volte à tela anterior e edite uma página já existente, ou [[Special:UserLogin|autentique-se ou crie uma conta]].',
'nocreate-loggedin'                => 'Você não possui permissão para criar novas páginas.',
'permissionserrors'                => 'Erros de permissões',
'permissionserrorstext'            => 'Você não possui permissão de fazer isso, {{PLURAL:$1|pelo seguinte motivo|pelos seguintes motivos}}:',
'permissionserrorstext-withaction' => 'Você não possui permissão para $2, {{PLURAL:$1|pelo seguinte motivo|pelos motivos a seguir}}:',
'recreate-deleted-warn'            => "'''Atenção: Você está recriando uma página já eliminada em outra ocasião.'''

Certifique-se de que seja adequado prosseguir editando esta página.
O registro de eliminação desta página é exibido a seguir, para sua comodidade:",
'deleted-notice'                   => 'Esta página foi eliminada. O registro de eliminações para esta página é disponibilizado abaixo, para referência.',
'deletelog-fulllog'                => 'Ver registro completo',
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
'revision-info'          => 'Edição feita às $1 por $2', # Additionally available: $3: revision id
'previousrevision'       => '← Versão anterior',
'nextrevision'           => 'Versão posterior →',
'currentrevisionlink'    => 'ver versão atual',
'cur'                    => 'atu',
'next'                   => 'prox',
'last'                   => 'ult',
'page_first'             => 'primeira',
'page_last'              => 'última',
'histlegend'             => 'Seleção para diferença: marque as caixas em uma das versões que deseja comparar e clique no botão.<br />
Legenda: (atu) = diferenças da versão atual,
(ult) = diferença da versão anterior, m = edição menor',
'history-fieldset-title' => 'Navegar pelo histórico',
'deletedrev'             => '[eliminada]',
'histfirst'              => 'Mais antigas',
'histlast'               => 'Mais recentes',
'historysize'            => '({{PLURAL:$1|1 byte|$1 bytes}})',
'historyempty'           => '(vazio)',

# Revision feed
'history-feed-title'          => 'Histórico de revisão',
'history-feed-description'    => 'Histórico de revisões para esta página nesta wiki',
'history-feed-item-nocomment' => '$1 em $2', # user at time
'history-feed-empty'          => 'A página requisitada não existe.
Poderá ter sido eliminada da wiki ou renomeada.
Tente [[Special:Search|pesquisar na wiki]] por páginas relevantes.',

# Revision deletion
'rev-deleted-comment'            => '(comentário removido)',
'rev-deleted-user'               => '(nome de usuário removido)',
'rev-deleted-event'              => '(entrada removida)',
'rev-deleted-text-permission'    => 'Esta revisão desta página foi removida dos arquivos públicos.
Poderão existir detalhes no [{{fullurl:Special:Log/delete|page={{PAGENAMEE}}}} registro de eliminação].',
'rev-deleted-text-view'          => 'A revisão desta página foi removida dos arquivos públicos.
Como um administrador desta wiki pode visualizá-la;
mais detalhes no [{{fullurl:Special:Log/delete|page={{FULLPAGENAMEE}}}} registro de eliminação].',
'rev-deleted-no-diff'            => 'Você não pode ver esta modificação porque uma das revisões foi removida dos arquivos públicos.
Pode haver detalhes no [{{fullurl:Special:Log/delete|page={{FULLPAGENAMEE}}}} registro de eliminação].',
'rev-delundel'                   => 'mostrar/esconder',
'revisiondelete'                 => 'Eliminar/restaurar edições',
'revdelete-nooldid-title'        => 'Nenhuma revisão selecionada',
'revdelete-nooldid-text'         => 'Você ou não especificou uma(s) edição(ões) de destino, a edição especificada não existe ou, ainda, você está tentando ocultar a edição atual.',
'revdelete-nologtype-title'      => 'Tipo de registo não especificado',
'revdelete-nologtype-text'       => 'Você não especificou um tipo de registro sobre o qual executar esta ação.',
'revdelete-toomanytargets-title' => 'Demasiados alvos',
'revdelete-toomanytargets-text'  => 'Você especificou demasiados tipos de alvos sobre os quais executar esta ação.',
'revdelete-nologid-title'        => 'Entrada de registro inválida',
'revdelete-nologid-text'         => 'Você não especificou um evento de registro alvo para executar esta função ou a entrada especificada não existe.',
'revdelete-selected'             => "'''{{PLURAL:$2|Edição selecionada|Edições selecionadas}} de [[:$1]]:'''",
'logdelete-selected'             => "'''{{PLURAL:$1|Evento de registro selecionado|Eventos de registro selecionados}}:'''",
'revdelete-text'                 => "'''Revisões eliminadas continuarão aparecendo no histórico da página, apesar de o seu conteúdo textual estar inacessível ao público.'''

Outros administradores nesta wiki continuarão podendo acessar ao conteúdo escondido e restaurá-lo através desta mesma ''interface'', a menos que uma restrição adicional seja definida.",
'revdelete-legend'               => 'Definir restrições de visualização',
'revdelete-hide-text'            => 'Ocultar texto da edição',
'revdelete-hide-name'            => 'Ocultar acção e alvo',
'revdelete-hide-comment'         => 'Esconder comentário de edição',
'revdelete-hide-user'            => 'Esconder nome de usuário/IP do editor',
'revdelete-hide-restricted'      => 'Aplicar estas restrições a administradores e trancar esta interface',
'revdelete-suppress'             => 'Suprimir dados de administradores, bem como de outros',
'revdelete-hide-image'           => 'Ocultar conteúdos do arquivo',
'revdelete-unsuppress'           => 'Remover restrições das edições restauradas',
'revdelete-log'                  => 'Comentário de registro:',
'revdelete-submit'               => 'Aplicar à edição selecionada',
'revdelete-logentry'             => 'modificou visibilidade de revisão para [[$1]]',
'logdelete-logentry'             => 'alterada visibilidade de eventos para [[$1]]',
'revdelete-success'              => 'Visibilidade de edição definida com sucesso.',
'logdelete-success'              => "'''Visibilidade de evento definida com sucesso.'''",
'revdel-restore'                 => 'Alterar visibilidade',
'pagehist'                       => 'Histórico da página',
'deletedhist'                    => 'Histórico de eliminações',
'revdelete-content'              => 'conteúdo',
'revdelete-summary'              => 'sumário de edição',
'revdelete-uname'                => 'nome do usuário',
'revdelete-restricted'           => 'restrições a administradores aplicadas',
'revdelete-unrestricted'         => 'restrições a administradores removidas',
'revdelete-hid'                  => 'ocultado $1',
'revdelete-unhid'                => 'desocultado $1',
'revdelete-log-message'          => '$1 para $2 {{PLURAL:$2|revisão|revisões}}',
'logdelete-log-message'          => '$1 para $2 {{PLURAL:$2|evento|eventos}}',

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

# Merge log
'mergelog'           => 'Registro de fusão de históricos',
'pagemerge-logentry' => '[[$1]] foi fundida em [[$2]] (até a edição $3)',
'revertmerge'        => 'Desfazer fusão',
'mergelogpagetext'   => 'Segue-se um registro das mais recentes fusões de históricos de páginas.',

# Diffs
'history-title'           => 'Histórico de edições de "$1"',
'difference'              => '(Diferença entre revisões)',
'lineno'                  => 'Linha $1:',
'compareselectedversions' => 'Compare as versões selecionadas',
'visualcomparison'        => 'Comparação visual',
'wikicodecomparison'      => 'Comparação de wikitexto',
'editundo'                => 'desfazer',
'diff-multi'              => '({{PLURAL:$1|uma edição intermediária não está sendo exibida|$1 edições intermediárias não estão sendo exibidas}}.)',
'diff-movedto'            => 'movido para $1',
'diff-styleadded'         => 'estilo $1 adicionado',
'diff-added'              => '$1 adicionado',
'diff-changedto'          => 'alterado para $1',
'diff-movedoutof'         => 'movido para fora de $1',
'diff-styleremoved'       => 'estilo $1 removido',
'diff-removed'            => '$1 removido',
'diff-changedfrom'        => 'alterado de $1',
'diff-src'                => 'fonte',
'diff-withdestination'    => 'com destino $1',
'diff-with'               => '&#32;com $1 $2',
'diff-with-final'         => '&#32;e $1 $2',
'diff-width'              => 'largura',
'diff-height'             => 'altura',
'diff-p'                  => "um '''parágrafo'''",
'diff-blockquote'         => "uma '''citação'''",
'diff-h1'                 => "um '''cabeçalho (de nível 1)'''",
'diff-h2'                 => "um '''cabeçalho (de nível 2)'''",
'diff-h3'                 => "um '''cabeçalho (de nível 3)'''",
'diff-h4'                 => "um '''cabeçalho (de nível 4)'''",
'diff-h5'                 => "um '''cabeçalho (de nível 5)'''",
'diff-pre'                => "um '''bloco pré-formatado'''",
'diff-div'                => "uma '''divisão'''",
'diff-ul'                 => "uma '''lista sem ordenação'''",
'diff-ol'                 => "uma '''lista ordenada'''",
'diff-li'                 => "um '''item de lista'''",
'diff-table'              => "uma '''tabela'''",
'diff-tbody'              => "um '''conteúdo de tabela'''",
'diff-tr'                 => "uma '''linha'''",
'diff-td'                 => "uma '''célula'''",
'diff-th'                 => "um '''cabeçalho'''",
'diff-br'                 => "uma '''quebra de linha'''",
'diff-hr'                 => "uma '''linha horizontal'''",
'diff-code'               => "um '''bloco de código computacional'''",
'diff-dl'                 => "uma '''lista de definições'''",
'diff-dt'                 => "uma '''definição do termo'''",
'diff-dd'                 => "uma '''definição'''",
'diff-input'              => "uma '''entrada de dados'''",
'diff-form'               => "um '''formulário'''",
'diff-img'                => "uma '''imagem'''",
'diff-span'               => "um '''''span'''''",
'diff-a'                  => "uma '''ligação'''",
'diff-i'                  => "'''itálico'''",
'diff-b'                  => "'''negrito'''",
'diff-strong'             => "'''forte'''",
'diff-em'                 => "'''ênfase'''",
'diff-font'               => "'''fonte'''",
'diff-big'                => "'''grande'''",
'diff-del'                => "'''apagado'''",
'diff-tt'                 => "'''largura fixa'''",
'diff-sub'                => "'''subscrito'''",
'diff-sup'                => "'''sobrescrito'''",
'diff-strike'             => "'''riscado'''",

# Search results
'searchresults'                    => 'Resultados de pesquisa',
'searchresults-title'              => 'Resultados da pesquisa por "$1"',
'searchresulttext'                 => 'Para mais informações de como pesquisar na {{SITENAME}}, consulte [[{{MediaWiki:Helppage}}|{{int:help}}]].',
'searchsubtitle'                   => 'Você pesquisou por \'\'\'[[:$1]]\'\'\' ([[Special:Prefixindex/$1|páginas iniciadas por "$1"]]{{int:pipe-separator}}[[Special:WhatLinksHere/$1|páginas que apontam para "$1"]])',
'searchsubtitleinvalid'            => 'Você pesquisou por "$1"',
'noexactmatch'                     => "'''Não existe uma página com o título \"\$1\".''' Você pode [[:\$1|criar tal página]].",
'noexactmatch-nocreate'            => "'''Não há uma página intitulada como \"\$1\".'''",
'toomanymatches'                   => 'Foram retornados muitos resultados. Por favor, tente um filtro de pesquisa diferente',
'titlematches'                     => 'Resultados nos títulos das páginas',
'notitlematches'                   => 'Nenhum título de página coincide',
'textmatches'                      => 'Resultados dos textos das páginas',
'notextmatches'                    => 'Nenhum texto nas páginas coincide',
'prevn'                            => 'anteriores $1',
'nextn'                            => 'próximos $1',
'prevn-title'                      => '$1 {{PLURAL:$1|resultado anterior|resultados anteriores}}',
'nextn-title'                      => '{{PLURAL:$1|próximo|próximos}} $1 {{PLURAL:$1|resultado|resultados}}',
'shown-title'                      => 'Mostrar $1 {{PLURAL:$1|resultado|resultados}} por página',
'viewprevnext'                     => 'Ver ($1) ($2) ($3).',
'searchmenu-legend'                => 'Opções de pesquisa',
'searchmenu-exists'                => "*'''Há uma página chamada \"[[\$1]]\" nesta wiki'''",
'searchmenu-new'                   => "'''Criar a página \"[[:\$1|\$1]]\" nesta wiki!'''",
'searchhelp-url'                   => 'Help:Conteúdos',
'searchmenu-prefix'                => '[[Special:PrefixIndex/$1|Navegue pelas páginas com este prefixo]]',
'searchprofile-articles'           => 'Páginas de conteúdo',
'searchprofile-articles-and-proj'  => 'Páginas de conteúdo e do projeto',
'searchprofile-project'            => 'Páginas do projeto',
'searchprofile-images'             => 'Arquivos',
'searchprofile-everything'         => 'Tudo',
'searchprofile-advanced'           => 'Avançado',
'searchprofile-articles-tooltip'   => 'Pesquisar em $1',
'searchprofile-project-tooltip'    => 'Pesquisar em $1',
'searchprofile-images-tooltip'     => 'Pesquisar arquivos',
'searchprofile-everything-tooltip' => 'Pesquisar em todo o conteúdo (incluindo páginas de discussão)',
'searchprofile-advanced-tooltip'   => 'Pesquisar nos espaços nominais personalizados',
'prefs-search-nsdefault'           => 'Pesquisar usando as definições padrão:',
'prefs-search-nscustom'            => 'Pesquisar domínios personalizados:',
'search-result-size'               => '$1 ({{PLURAL:$2|1 palavra|$2 palavras}})',
'search-result-score'              => 'Relevância: $1%',
'search-redirect'                  => '(redirecionamento para $1)',
'search-section'                   => '(seção $1)',
'search-suggest'                   => 'Será que quis dizer: $1',
'search-interwiki-caption'         => 'Projetos irmãos',
'search-interwiki-default'         => 'Resultados de $1:',
'search-interwiki-more'            => '(mais)',
'search-mwsuggest-enabled'         => 'com sugestões',
'search-mwsuggest-disabled'        => 'sem sugestões',
'search-relatedarticle'            => 'Relacionado',
'mwsuggest-disable'                => 'Desativar sugestões AJAX',
'searchrelated'                    => 'relacionados',
'searchall'                        => 'todos',
'showingresults'                   => "A seguir {{PLURAL:$1|é mostrado '''um''' resultado|são mostrados até '''$1''' resultados}}, iniciando no '''$2'''º.",
'showingresultsnum'                => "A seguir {{PLURAL:$3|é mostrado '''um''' resultado|são mostrados '''$3''' resultados}}, iniciando com o '''$2'''º.",
'showingresultstotal'              => "Exibindo {{PLURAL:$4|o resultado '''$1''' de '''$3'''|os resultados '''$1 a $2''' de '''$3'''}}",
'nonefound'                        => "'''Nota''': apenas alguns espaços nominais são pesquisados por padrão. Tente utilizar o prefixo ''all:'' em sua busca, para pesquisar por todos os conteúdos desta wiki (inclusive páginas de discussão, predefinições etc), ou mesmo, utilizando o espaço nominal desejado como prefixo.",
'search-nonefound'                 => 'Não houve resultados para a pesquisa.',
'powersearch'                      => 'Pesquisa avançada',
'powersearch-legend'               => 'Pesquisa avançada',
'powersearch-ns'                   => 'Pesquisar nos espaços nominais:',
'powersearch-redir'                => 'Listar redirecionamentos',
'powersearch-field'                => 'Pesquisar',
'search-external'                  => 'Pesquisa externa',
'searchdisabled'                   => 'O motor de pesquisa na {{SITENAME}} foi desativado por motivos de desempenho. Enquanto isso pode fazer a sua pesquisa através do Google ou do Yahoo!.<br />
Note que os índices do conteúdo da {{SITENAME}} destes sites podem estar desatualizados.',

# Preferences page
'preferences'               => 'Preferências',
'mypreferences'             => 'Minhas preferências',
'prefs-edits'               => 'Número de edições:',
'prefsnologin'              => 'Não autenticado',
'prefsnologintext'          => 'É necessário estar <span class="plainlinks">[{{fullurl:Special:UserLogin|returnto=$1}} autenticado]</span> para definir as suas preferências.',
'prefsreset'                => 'As preferências foram restauradas tal como se encontravam no banco de dados.',
'qbsettings'                => 'Configurações da Barra Rápida',
'qbsettings-none'           => 'Nenhuma',
'qbsettings-fixedleft'      => 'Fixo à esquerda',
'qbsettings-fixedright'     => 'Fixo à direita',
'qbsettings-floatingleft'   => 'Flutuando à esquerda',
'qbsettings-floatingright'  => 'Flutuando à direita',
'changepassword'            => 'Alterar senha',
'skin'                      => 'Tema',
'skin-preview'              => 'Pré-visualizar',
'math'                      => 'Matemática',
'dateformat'                => 'Formato da data',
'datedefault'               => 'Sem preferência',
'datetime'                  => 'Data e hora',
'math_failure'              => 'Falhou ao verificar gramática',
'math_unknown_error'        => 'Erro desconhecido',
'math_unknown_function'     => 'Função desconhecida',
'math_lexing_error'         => 'Erro léxico',
'math_syntax_error'         => 'Erro de sintaxe',
'math_image_error'          => 'Falha na conversão para PNG. Verifique a instalação do latex, dvips, gs e convert',
'math_bad_tmpdir'           => 'Ocorreram problemas na criação ou escrita no diretório temporário math',
'math_bad_output'           => 'Ocorreram problemas na criação ou escrita no diretório de resultados math',
'math_notexvc'              => 'O executável texvc não foi encontrado. Consulte math/README para instruções da configuração.',
'prefs-personal'            => 'Perfil de usuário',
'prefs-rc'                  => 'Mudanças recentes',
'prefs-watchlist'           => 'Lista de artigos vigiados',
'prefs-watchlist-days'      => 'Dias a mostrar na lista de artigos vigiados:',
'prefs-watchlist-days-max'  => '(no máximo 7 dias)',
'prefs-watchlist-edits'     => 'Número de edições mostradas na lista de vigiados expandida:',
'prefs-watchlist-edits-max' => '(número máximo: 1000)',
'prefs-misc'                => 'Diversos',
'prefs-resetpass'           => 'Alterar senha',
'saveprefs'                 => 'Salvar',
'resetprefs'                => 'Eliminar as alterações não-salvas',
'restoreprefs'              => 'Restaurar todas as configurações padrão',
'textboxsize'               => 'Opções de edição',
'prefs-edit-boxsize'        => 'Tamanho da janela de edição.',
'rows'                      => 'Linhas:',
'columns'                   => 'Colunas:',
'searchresultshead'         => 'Pesquisa',
'resultsperpage'            => 'Resultados por página:',
'contextlines'              => 'Linhas por resultado:',
'contextchars'              => 'Contexto por linha:',
'stub-threshold'            => 'Links para páginas de conteúdo aparecerão <a href="#" class="stub">desta forma</a> se elas possuírem menos de (bytes):',
'recentchangesdays'         => 'Dias a serem exibidos nas Mudanças recentes:',
'recentchangesdays-max'     => '(máximo: $1 {{PLURAL:$1|dia|dias}})',
'recentchangescount'        => 'Número de edições a serem exibidas nas mudanças recentes, históricos de páginas e páginas de registos, por padrão:',
'savedprefs'                => 'As suas preferências foram salvas.',
'timezonelegend'            => 'Fuso horário',
'timezonetext'              => '¹Número de horas que o seu horário local difere do horário do servidor (UTC).',
'localtime'                 => 'Horário local:',
'timezoneselect'            => 'Fuso horário:',
'timezoneuseserverdefault'  => 'Usa padrão do servidor',
'timezoneuseoffset'         => 'Outro (especifique diferença horária)',
'timezoneoffset'            => 'Diferença horária¹',
'servertime'                => 'Horário do servidor:',
'guesstimezone'             => 'Preencher a partir do navegador (browser)',
'timezoneregion-africa'     => 'África',
'timezoneregion-america'    => 'América',
'timezoneregion-antarctica' => 'Antártida',
'timezoneregion-arctic'     => 'Ártico',
'timezoneregion-asia'       => 'Ásia',
'timezoneregion-atlantic'   => 'Oceano Atlântico',
'timezoneregion-australia'  => 'Austrália',
'timezoneregion-europe'     => 'Europa',
'timezoneregion-indian'     => 'Oceano Índico',
'timezoneregion-pacific'    => 'Oceano Pacífico',
'allowemail'                => 'Permitir email de outros usuários',
'prefs-searchoptions'       => 'Opções de busca',
'prefs-namespaces'          => 'Espaços nominais',
'defaultns'                 => 'Pesquisar por padrão nestes espaços nominais:',
'default'                   => 'padrão',
'files'                     => 'Arquivos',
'prefs-custom-css'          => 'CSS personalizada',
'prefs-custom-js'           => 'JS personalizado',

# User rights
'userrights'                  => 'Gestão de privilégios de usuários', # Not used as normal message but as header for the special page itself
'userrights-lookup-user'      => 'Administrar grupos de usuários',
'userrights-user-editname'    => 'Forneça um nome de usuário:',
'editusergroup'               => 'Editar grupos de usuários',
'editinguser'                 => "Modificando privilégios do usuário '''[[User:$1|$1]]''' ([[User talk:$1|{{int:talkpagelinktext}}]]{{int:pipe-separator}}[[Special:Contributions/$1|{{int:contribslink}}]])",
'userrights-editusergroup'    => 'Editar grupos do usuário',
'saveusergroups'              => 'Salvar grupos do usuário',
'userrights-groupsmember'     => 'Membro de:',
'userrights-groups-help'      => 'É possível alterar os grupos em que este usuário se encontra:
* Uma caixa de seleção selecionada significa que o usuário se encontra no grupo.
* Uma caixa de seleção desselecionada significa que o usuário não se encontra no grupo.
* Um * indica que não pode remover o grupo depois de o adicionar, ou vice-versa.',
'userrights-reason'           => 'Motivo de alterações:',
'userrights-no-interwiki'     => 'Você não tem permissão de alterar privilégios de usuários em outras wikis.',
'userrights-nodatabase'       => 'O banco de dados $1 não existe ou não é um banco de dados local.',
'userrights-nologin'          => 'Você precisa [[Special:UserLogin|autenticar-se]] como um administrador para especificar os privilégios de usuário.',
'userrights-notallowed'       => 'Sua conta não possui permissão para conceder privilégios a usuários.',
'userrights-changeable-col'   => 'Grupos que pode alterar',
'userrights-unchangeable-col' => 'Grupos que não pode alterar',

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
'right-read'                 => 'Ler páginas',
'right-edit'                 => 'Editar páginas',
'right-createpage'           => 'Criar páginas (que não sejam páginas de discussão)',
'right-createtalk'           => 'Criar páginas de discussão',
'right-createaccount'        => 'Criar novas contas de usuário',
'right-minoredit'            => 'Marcar edições como menores',
'right-move'                 => 'Mover páginas',
'right-move-subpages'        => 'Mover páginas com as suas subpáginas',
'right-move-rootuserpages'   => 'Mover páginas raiz de usuários',
'right-movefile'             => 'Mover arquivos',
'right-suppressredirect'     => 'Não criar um redirecionamento do nome antigo quando uma página é movida',
'right-upload'               => 'Carregar arquivos',
'right-reupload'             => 'Sobrescrever um arquivo existente',
'right-reupload-own'         => 'Sobrescrever um arquivo existente carregado pelo mesmo usuário',
'right-reupload-shared'      => 'Sobrescrever localmente arquivos no repositório partilhado de imagens',
'right-upload_by_url'        => 'Carregar um arquivo de um endereço URL',
'right-purge'                => 'Carregar a cache de uma página no site sem página de confirmação',
'right-autoconfirmed'        => 'Editar páginas semi-protegidas',
'right-bot'                  => 'Ser tratado como um processo automatizado',
'right-nominornewtalk'       => 'Não ter o aviso de novas mensagens despoletado quando são feitas edições menores a páginas de discussão',
'right-apihighlimits'        => 'Usar limites superiores em consultas (queries) via API',
'right-writeapi'             => 'Uso da API de escrita',
'right-delete'               => 'Eliminar páginas',
'right-bigdelete'            => 'Eliminar páginas com histórico grande',
'right-deleterevision'       => 'Eliminar e restaurar revisões específicas de páginas',
'right-deletedhistory'       => 'Ver entradas de histórico eliminadas, sem o texto associado',
'right-browsearchive'        => 'Buscar páginas eliminadas',
'right-undelete'             => 'Restaurar uma página',
'right-suppressrevision'     => 'Rever e restaurar revisões ocultadas dos Sysops',
'right-suppressionlog'       => 'Ver registros privados',
'right-block'                => 'Impedir outros usuários de editarem',
'right-blockemail'           => 'Impedir um usuário de enviar email',
'right-hideuser'             => 'Bloquear um nome de usuário, escondendo-o do público',
'right-ipblock-exempt'       => 'Contornar bloqueios de IP, automáticos e de intervalo',
'right-proxyunbannable'      => 'Contornar bloqueios automáticos de proxies',
'right-protect'              => 'Mudar níveis de proteção e editar páginas protegidas',
'right-editprotected'        => 'Editar páginas protegidas (sem proteção em cascata)',
'right-editinterface'        => 'Editar a interface de usuário',
'right-editusercssjs'        => 'Editar os arquivos CSS e JS de outros usuários',
'right-rollback'             => 'Reverter rapidamente o último usuário que editou uma página em particular',
'right-markbotedits'         => 'Marcar edições revertidas como edições de bot',
'right-noratelimit'          => 'Não afetado pelos limites de velocidade de operação',
'right-import'               => 'Importar páginas de outros wikis',
'right-importupload'         => 'Importar páginas de um arquivo carregado',
'right-patrol'               => 'Marcar edições como patrulhadas',
'right-autopatrol'           => 'Ter edições automaticamente marcadas como patrulhadas',
'right-patrolmarks'          => 'Usar funcionalidades de patrulhagem das mudanças recentes',
'right-unwatchedpages'       => 'Ver uma lista de páginas não vigiadas',
'right-trackback'            => "Submeter um 'trackback'",
'right-mergehistory'         => 'Fundir o histórico de páginas',
'right-userrights'           => 'Editar todos os direitos de usuário',
'right-userrights-interwiki' => 'Editar direitos de usuário de usuários outros sites wiki',
'right-siteadmin'            => 'Bloquear e desbloquear o banco de dados',

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
'rcnote'                            => "A seguir {{PLURAL:$1|está listada '''uma''' alteração ocorrida|estão listadas '''$1''' alterações ocorridas}} {{PLURAL:$2|no último dia|nos últimos '''$2''' dias}}, a partir das $5 de $4.",
'rcnotefrom'                        => 'Abaixo estão as mudanças desde <b>$2</b> (mostradas até <b>$1</b>).',
'rclistfrom'                        => 'Mostrar as novas alterações a partir de $1',
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
'recentchangeslinked-title'    => 'Alterações relacionadas com "$1"',
'recentchangeslinked-noresult' => 'Não ocorreram alterações em páginas relacionadas no intervalo de tempo fornecido.',
'recentchangeslinked-summary'  => "Esta página especial lista as alterações mais recentes de páginas que possuam um link a outra (ou de membros de uma categoria especificada).
Páginas que estejam em [[Special:Watchlist|sua lista de vigiados]] são exibidas em '''negrito'''.",
'recentchangeslinked-page'     => 'Nome da página:',
'recentchangeslinked-to'       => 'Mostrar alterações a páginas relacionadas com a página fornecida',

# Upload
'upload'                      => 'Carregar arquivo',
'uploadbtn'                   => 'Carregar arquivo',
'reupload'                    => 'Re-enviar',
'reuploaddesc'                => 'Cancelar o envio e retornar ao formulário de upload',
'uploadnologin'               => 'Não autenticado',
'uploadnologintext'           => 'Você necessita estar [[Special:UserLogin|autenticado]] para enviar arquivos.',
'upload_directory_missing'    => 'O diretório de upload ($1) está em falta e não pôde ser criada pelo webserver.',
'upload_directory_read_only'  => 'O diretório de download de arquivos ($1) não tem permissões de escrita para o servidor Web.',
'uploaderror'                 => 'Erro ao carregar',
'uploadtext'                  => "Utilize o formulário abaixo para carregar novos arquivos.
Para ver ou pesquisar imagens anteriormente carregadas consulte a [[Special:FileList|lista de arquivos carregados]]. (Re)Envios são também registrados no [[Special:Log/upload|registro de carregamento]], e as eliminações no [[Special:Log/delete|registro de eliminação]]

Para incluir a imagem numa página, utilize uma ligação em um dos seguintes formatos:
* '''<tt><nowiki>[[</nowiki>{{ns:file}}<nowiki>:Arquivo.jpg]]</nowiki></tt>''' para utilizar a versão completa do arquivo;
* '''<tt><nowiki>[[</nowiki>{{ns:file}}<nowiki>:Arquivo.png|200px|thumb|left|texto]]</nowiki></tt>''' para utilizar uma renderização de 200 pixels dentro de uma caixa posicionada à margem esquerda contendo 'texto' como descrição;
* '''<tt><nowiki>[[</nowiki>{{ns:media}}<nowiki>:Arquivo.ogg]]</nowiki></tt>''' para uma ligação direta ao arquivo sem que ele seja exibido.",
'upload-permitted'            => 'Tipos de arquivos permitidos: $1.',
'upload-preferred'            => 'Tipos de arquivos preferidos: $1.',
'upload-prohibited'           => 'Tipos de arquivo proibidos: $1.',
'uploadlog'                   => 'registro de carregamento',
'uploadlogpage'               => 'Registro de carregamento',
'uploadlogpagetext'           => 'Segue-se uma lista dos carregamentos mais recentes.',
'filename'                    => 'Nome do arquivo',
'filedesc'                    => 'Descrição do arquivo',
'fileuploadsummary'           => 'Sumário:',
'filereuploadsummary'         => 'Alterações no arquivo:',
'filestatus'                  => 'Estado dos direitos de autor:',
'filesource'                  => 'Fonte:',
'uploadedfiles'               => 'Arquivos carregados',
'ignorewarning'               => 'Ignorar aviso e salvar de qualquer forma.',
'ignorewarnings'              => 'Ignorar todos os avisos',
'minlength1'                  => 'Os nomes de arquivos devem de ter pelo menos uma letra.',
'illegalfilename'             => 'O arquivo "$1" possui caracteres que não são permitidos no título de uma página. Por favor, altere o nome do arquivo e tente carregar novamente.',
'badfilename'                 => 'O nome do arquivo foi alterado para "$1".',
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
'fileexists'                  => "Já existe um arquivo com este nome. Por favor, verifique '''<tt>$1</tt>''' caso não tenha a certeza se deseja alterar o arquivo atual.",
'filepageexists'              => "A página de descrição deste arquivo já foi criada em '''<tt>$1</tt>''', mas atualmente não existe nenhum arquivo com este nome. O sumário que introduziu não aparecerá na página de descrição. Para o fazer aparecer, terá que o editar manualmente",
'fileexists-extension'        => "Já existe um arquivo de nome similar:<br />
Nome do arquivo que está sendo enviado: '''<tt>$1</tt>'''<br />
Nome do arquivo existente: '''<tt>$2</tt>'''<br />
Por gentileza, escolha um nome diferente.",
'fileexists-thumb'            => "<center>'''arquivo existente'''</center>",
'fileexists-thumbnail-yes'    => "O arquivo aparenta ser uma imagem de tamanho reduzido (''miniatura'', ou ''thumbnail''). Por gentileza, verifique o arquivo '''<tt>$1</tt>'''.<br />
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
'uploadwarning'               => 'Aviso de envio',
'savefile'                    => 'Salvar arquivo',
'uploadedimage'               => 'carregou "[[$1]]"',
'overwroteimage'              => 'foi enviada uma nova versão de "[[$1]]"',
'uploaddisabled'              => 'Carregamentos desativados',
'uploaddisabledtext'          => 'Carregamentos de arquivos estão desativados.',
'php-uploaddisabledtext'      => 'O carregamento de arquivos via PHP está desativado. Por favor, verifique a configuração file_uploads.',
'uploadscripted'              => 'Este arquivo contém HTML ou código que pode ser erradamente interpretado por um navegador web.',
'uploadcorrupt'               => 'O arquivo encontra-se corrompido ou tem uma extensão incorreta. Por gentileza, verifique o ocorrido e tente novamente.',
'uploadvirus'                 => 'O arquivo contém vírus! Detalhes: $1',
'sourcefilename'              => 'Nome do arquivo de origem:',
'destfilename'                => 'Nome do arquivo de destino:',
'upload-maxfilesize'          => 'Tamanho máximo do arquivo: $1',
'watchthisupload'             => 'Vigiar esta página',
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

'upload-proto-error'      => 'Protocolo incorreto',
'upload-proto-error-text' => 'O envio de arquivos remotos requer endereços (URLs) que iniciem com <code>http://</code> ou <code>ftp://</code>.',
'upload-file-error'       => 'Erro interno',
'upload-file-error-text'  => 'Ocorreu um erro interno ao se tentar criar um arquivo temporário no servidor. Por gentileza, contate um administrador de sistema.',
'upload-misc-error'       => 'Erro desconhecido de envio',
'upload-misc-error-text'  => 'Ocorreu um erro desconhecido durante o envio. Por gentileza, verifique se o endereço (URL) é válido e acessível e tente novamente. Caso o problema persista, contacte um administrador de sistema.',

# Some likely curl errors. More could be added from <http://curl.haxx.se/libcurl/c/libcurl-errors.html>
'upload-curl-error6'       => 'Não foi possível acessar a URL',
'upload-curl-error6-text'  => 'Não foi possível acessar o endereço (URL) fornecido. Por gentileza, se certifique que o endereço foi fornecido corretamente e de que o site esteja acessível.',
'upload-curl-error28'      => 'Tempo limite para o envio do arquivo excedido',
'upload-curl-error28-text' => 'O site demorou muito tempo para responder. Por gentileza, verifique se o site está acessível, aguarde alguns momentos e tente novamente. Talvez você deseje fazer nova tentativa em um horário menos congestionado.',

'license'            => 'Licença:',
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
'filehist'                  => 'Histórico do arquivo',
'filehist-help'             => 'Clique em uma data/horário para ver o arquivo tal como ele se encontrava em tal momento.',
'filehist-deleteall'        => 'eliminar todas',
'filehist-deleteone'        => 'eliminar',
'filehist-revert'           => 'reverter',
'filehist-current'          => 'atual',
'filehist-datetime'         => 'Data/Horário',
'filehist-thumb'            => 'Miniatura',
'filehist-thumbtext'        => 'Miniatura para a versão de $1',
'filehist-nothumb'          => 'Miniatura indisponível',
'filehist-user'             => 'Usuário',
'filehist-dimensions'       => 'Dimensões',
'filehist-filesize'         => 'Tamanho do arquivo',
'filehist-comment'          => 'Comentário',
'imagelinks'                => 'Ligações de arquivos',
'linkstoimage'              => '{{PLURAL:$1|A seguinte página aponta|As seguintes $1 páginas apontam}} para este arquivo:',
'linkstoimage-more'         => 'Mais de $1 {{PLURAL:$1|página tem alguma ligação|páginas têm alguma ligação}} para este arquivo.
A lista a seguir mostra apenas {{PLURAL:$1|a primeira ligação de página|as primeiras $1 ligações de página}} para este arquivo.
Uma [[Special:WhatLinksHere/$2|listagem completa]] está disponível.',
'nolinkstoimage'            => 'Nenhuma página aponta para este arquivo.',
'morelinkstoimage'          => 'Ver [[Special:WhatLinksHere/$1|mais ligações]] para este arquivo.',
'redirectstofile'           => '{{PLURAL:$1|O seguinte arquivo redireciona|Os seguintes arquivos redirecionam}} para este arquivo:',
'duplicatesoffile'          => '{{PLURAL:$1|O seguinte arquivo é duplicado|Os seguintes arquivos são duplicados}} deste arquivo ([[Special:FileDuplicateSearch/$2|mais detalhes]]):',
'sharedupload'              => 'Este arquivo é do $1 e pode ser usado por outros projetos. $2', # $1 is the repo name, $2 is shareduploadwiki(-desc)
'shareduploadwiki'          => 'Por favor, consulte a $1 para mais informações.',
'shareduploadwiki-desc'     => 'A descrição na sua $1 é mostrada abaixo.',
'shareduploadwiki-linktext' => 'página de descrição de arquivo',
'noimage'                   => 'Não existe nenhum arquivo com este nome, mas você pode $1.',
'noimage-linktext'          => 'carregar um',
'uploadnewversion-linktext' => 'Carregar uma nova versão deste arquivo',
'shared-repo-from'          => 'de $1', # $1 is the repository name
'shared-repo'               => 'um repositório compartilhado', # used when shared-repo-NAME does not exist

# File reversion
'filerevert'                => 'Reverter $1',
'filerevert-legend'         => 'Reverter arquivo',
'filerevert-intro'          => '<span class="plainlinks">Você está revertendo \'\'\'[[Media:$1|$1]]\'\'\' para a [$4 versão de $2 - $3].</span>',
'filerevert-comment'        => 'Comentário:',
'filerevert-defaultcomment' => 'Revertido para a versão de $1 - $2',
'filerevert-submit'         => 'Reverter',
'filerevert-success'        => '<span class="plainlinks">\'\'\'[[Media:$1|$1]]\'\'\' foi revertida para a [$4 versão de $2 - $3].</span>',
'filerevert-badversion'     => 'Não há uma versão local anterior deste arquivo no período de tempo especificado.',

# File deletion
'filedelete'                  => 'Eliminar $1',
'filedelete-legend'           => 'Eliminar arquivo',
'filedelete-intro'            => "Você está prestes a eliminar o arquivo '''[[Media:$1|$1]]''' junto com todo o seu histórico.",
'filedelete-intro-old'        => '<span class="plainlinks">Você se encontra prestes a eliminar a versão de \'\'\'[[Media:$1|$1]]\'\'\' tal como se encontrava em [$4 $3, $2].</span>',
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
** Arquivo duplicado',
'filedelete-edit-reasonlist'  => 'Editar motivos de eliminação',

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
'randompage-nopages' => 'Não há páginas no espaço nominal "$1".',

# Random redirect
'randomredirect'         => 'Redirecionamento aleatório',
'randomredirect-nopages' => 'Não há redirecionamentos no espaço nominal "$1".',

# Statistics
'statistics'                   => 'Estatísticas',
'statistics-header-pages'      => 'Estatísticas de páginas',
'statistics-header-edits'      => 'Estatísticas de edições',
'statistics-header-views'      => 'Ver estatísticas',
'statistics-header-users'      => 'Estatísticas dos usuários',
'statistics-articles'          => 'Páginas de conteúdo',
'statistics-pages'             => 'Páginas',
'statistics-pages-desc'        => 'Todas as páginas na wiki, incluindo páginas de discussão, redireccionamentos, etc.',
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
'doubleredirectstext'        => 'Cada linha contém ligações para o primeiro e segundo redirecionamento, bem como a primeira linha de conteúdo do segundo redirecionamento, geralmente contendo a página destino "real", que devia ser o destino do primeiro redirecionamento.',
'double-redirect-fixed-move' => '[[$1]] foi movido e agora é um redirecionamento para [[$2]]',
'double-redirect-fixer'      => 'Corretor de redirecionamentos',

'brokenredirects'        => 'Redirecionamentos quebrados',
'brokenredirectstext'    => 'Os seguintes redirecionamentos ligam para páginas inexistentes:',
'brokenredirects-edit'   => '(editar)',
'brokenredirects-delete' => '(eliminar)',

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
'usercreated'             => 'Criado em $1 às $2',
'newpages'                => 'Páginas novas',
'newpages-username'       => 'Nome de usuário:',
'ancientpages'            => 'Páginas mais antigas',
'move'                    => 'Mover',
'movethispage'            => 'Mover esta página',
'unusedimagestext'        => 'Por favor, note que outros websites podem apontar para um arquivo através de um URL direto e, por isso, podem estar a ser listadas aqui, mesmo estando em uso.',
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
'all-logs-page'        => 'Todos os registros',
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
'categoriespagetext'            => 'As seguintes categorias contém páginas ou multimédia.
As [[Special:UnusedCategories|categorias não utilizadas]] não são exibidas nesta listagem.
Veja também as [[Special:WantedCategories|categorias em falta]].',
'categoriesfrom'                => 'Listar categorias começando por:',
'special-categories-sort-count' => 'ordenar por contagem',
'special-categories-sort-abc'   => 'ordenar alfabeticamente',

# Special:DeletedContributions
'deletedcontributions'       => 'Contribuições de usuário eliminadas',
'deletedcontributions-title' => 'Contribuições de usuário eliminadas',

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

# Special:Log/newusers
'newuserlogpage'              => 'Registro de criação de usuários',
'newuserlogpagetext'          => 'Este é um registro de novas contas de usuário',
'newuserlog-byemail'          => 'senha enviada por correio-eletrônico',
'newuserlog-create-entry'     => 'Novo usuário',
'newuserlog-create2-entry'    => 'criou nova conta para $1',
'newuserlog-autocreate-entry' => 'Conta criada automaticamente',

# Special:ListGroupRights
'listgrouprights'                 => 'Privilégios de grupo de usuários',
'listgrouprights-summary'         => 'O que segue é uma lista dos grupos de usuários definidos nesta wiki, com os seus privilégios de acessos associados.
Pode haver [[{{MediaWiki:Listgrouprights-helppage}}|informações adicionais]] sobre privilégios individuais.',
'listgrouprights-group'           => 'Grupo',
'listgrouprights-rights'          => 'Privilégios',
'listgrouprights-helppage'        => 'Help:Privilégios de grupo',
'listgrouprights-members'         => '(lista de membros)',
'listgrouprights-addgroup'        => 'Podem adicionar {{PLURAL:$2|grupo|grupos}}: $1',
'listgrouprights-removegroup'     => 'Podem remover {{PLURAL:$2|grupo|grupos}}: $1',
'listgrouprights-addgroup-all'    => 'Podem adicionar todos os grupos',
'listgrouprights-removegroup-all' => 'Podem remover todos os grupos',

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
'noemailtext'      => 'Este utilizador não especificou um endereço de e-mail válido.',
'nowikiemailtitle' => 'E-mail não permitido',
'nowikiemailtext'  => 'Este utilizador optou por não receber e-mail de outros utilizadores.',
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
'nowatchlist'          => 'A sua lista de vigiados não possui títulos.',
'watchlistanontext'    => 'Por favor $1 para ver ou editar os itens na sua lista de páginas vigiados.',
'watchnologin'         => 'Não está autenticado',
'watchnologintext'     => 'Você precisa estar [[Special:UserLogin|autenticado]] para modificar a sua lista de artigos vigiados.',
'addedwatch'           => 'Adicionado à lista',
'addedwatchtext'       => "A página \"[[:\$1]]\" foi adicionada à sua [[Special:Watchlist|lista de vigiados]].
Modificações futuras em tal página e páginas de discussão a ela associadas serão listadas lá, com a página aparecendo a '''negrito''' na [[Special:RecentChanges|lista de mudanças recentes]], para que possa encontrá-la com maior facilidade.",
'removedwatch'         => 'Removida da lista de páginas vigiados',
'removedwatchtext'     => 'A página "<nowiki>$1</nowiki>" foi removida de sua lista de páginas vigiadas.',
'watch'                => 'Vigiar',
'watchthispage'        => 'Vigiar esta página',
'unwatch'              => 'Desinteressar-se',
'unwatchthispage'      => 'Parar de vigiar esta página',
'notanarticle'         => 'Não é uma página de conteúdo',
'notvisiblerev'        => 'Edição eliminada',
'watchnochange'        => 'Nenhum dos itens vigiados foram editados no período exibido.',
'watchlist-details'    => '{{PLURAL:$1|$1 página|$1 páginas}} na sua lista de páginas vigiadas, excluindo páginas de discussão.',
'wlheader-enotif'      => '* A notificação por email encontra-se ativada.',
'wlheader-showupdated' => "* As páginas modificadas desde a sua última visita são mostradas em '''negrito'''",
'watchmethod-recent'   => 'verificando edições recentes para as páginas vigiadas',
'watchmethod-list'     => 'verificando páginas vigiadas para edições recentes',
'watchlistcontains'    => 'Sua lista de vigiados contém $1 {{PLURAL:$1|página|páginas}}.',
'iteminvalidname'      => "Problema com item '$1', nome inválido...",
'wlnote'               => "A seguir {{PLURAL:$1|está a última alteração ocorrida|estão as últimas '''$1''' alterações ocorridas}} {{PLURAL:$2|na última hora|nas últimas '''$2''' horas}}.",
'wlshowlast'           => 'Ver últimas $1 horas $2 dias $3',
'watchlist-options'    => 'Opções da lista de vigiados',

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
'enotif_body'                  => 'Caro $WATCHINGUSERNAME,


A página $PAGETITLE na {{SITENAME}} foi $CHANGEDORCREATED a $PAGEEDITDATE por $PAGEEDITOR; consulte $PAGETITLE_URL para a versão atual.

$NEWPAGE

Sumário de edição: $PAGESUMMARY $PAGEMINOREDIT

Contate o editor:
e-mail: $PAGEEDITOR_EMAIL
wiki: $PAGEEDITOR_WIKI

Não haverá mais notificações no caso de futuras alterações a não ser que visite esta página. Poderá também restaurar as bandeiras de notificação para todas as suas páginas vigiadas na sua lista de páginas vigiadas.

             O seu amigável sistema de notificação da {{SITENAME}}

--
Para alterar as suas preferências da lista de páginas vigiados, visite
{{fullurl:Special:Watchlist/edit}}

Contato e assistência
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
'historywarning'         => 'Aviso: A página que está prestes a eliminar possui um histórico:',
'confirmdeletetext'      => 'Encontra-se prestes a eliminar permanentemente uma página ou uma imagem e todo o seu histórico.
Por favor, confirme que possui a intenção de fazer isto, que compreende as consequências e que encontra-se a fazer isto de acordo com as [[{{MediaWiki:Policy-url}}|políticas]] do projeto.',
'actioncomplete'         => 'Ação completada',
'deletedtext'            => '"<nowiki>$1</nowiki>" foi eliminada.
Consulte $2 para um registro de eliminações recentes.',
'deletedarticle'         => 'eliminada "[[$1]]"',
'suppressedarticle'      => 'suprimiu "[[$1]]"',
'dellogpage'             => 'Registro de eliminação',
'dellogpagetext'         => 'Abaixo uma lista das eliminações mais recentes.',
'deletionlog'            => 'registro de eliminação',
'reverted'               => 'Revertido para versão mais nova',
'deletecomment'          => 'Motivo de eliminação',
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
'rollback'         => 'Reverter edições',
'rollback_short'   => 'Voltar',
'rollbacklink'     => 'voltar',
'rollbackfailed'   => 'A reversão falhou',
'cantrollback'     => 'Não foi possível reverter a edição; o último contribuidor é o único autor desta página',
'alreadyrolled'    => 'Não foi possível reverter a última edição de [[:$1]] por [[User:$2|$2]] ([[User talk:$2|discussão]]{{int:pipe-separator}}[[Special:Contributions/$2|{{int:contribslink}}]]);
alguém já editou ou reverteu a página.

A última edição da página foi feita por [[User:$3|$3]] ([[User talk:$3|discussão]]{{int:pipe-separator}}[[Special:Contributions/$3|{{int:contribslink}}]]).',
'editcomment'      => "O sumário de edição era: \"''\$1''\".", # only shown if there is an edit comment
'revertpage'       => 'Foram revertidas as edições de [[Special:Contributions/$2|$2]] ([[User talk:$2|disc]]) para a última versão por [[User:$1|$1]]', # Additionally available: $3: revid of the revision reverted to, $4: timestamp of the revision reverted to, $5: revid of the revision reverted from, $6: timestamp of the revision reverted from
'rollback-success' => 'Foram revertidas as edições de $1, com o conteúdo passando a estar como na última edição de $2.',
'sessionfailure'   => 'Foram detetados problemas com a sua sessão;
Esta ação foi cancelada como medida de proteção contra a intercepção de sessões.
Experimente usar o botão "Voltar" e atualizar a página de onde veio e tente novamente.',

# Protect
'protectlogpage'              => 'Registro de proteção',
'protectlogtext'              => 'Abaixo encontra-se o registro de proteção e desproteção de páginas.
Veja a [[Special:ProtectedPages|lista de páginas protegidas]] para uma listagem das páginas que se encontram protegidas no momento.',
'protectedarticle'            => 'protegeu "[[$1]]"',
'modifiedarticleprotection'   => 'foi alterado o nível de proteção para "[[$1]]"',
'unprotectedarticle'          => 'desprotegeu "[[$1]]"',
'movedarticleprotection'      => 'moveu as configurações de proteção de "[[$2]]" para "[[$1]]"',
'protect-title'               => 'Protegendo "$1"',
'prot_1movedto2'              => '[[$1]] foi movido para [[$2]]',
'protect-legend'              => 'Confirmar proteção',
'protectcomment'              => 'Motivo de proteção',
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
'protect-cascadeon'           => 'Esta página encontra-se protegida, uma vez que se encontra incluída {{PLURAL:$1|na página listada a seguir, protegida|nas páginas listadas a seguir, protegidas}} com a "proteção progressiva" ativada. Você poderá alterar o nível de proteção desta página, mas isso não afetará a "proteção progressiva".',
'protect-default'             => 'Permitir todos os utilizadores',
'protect-fallback'            => 'É necessário o privilégio de "$1"',
'protect-level-autoconfirmed' => 'Bloquear utilizadores novos e não registrados',
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
'protect-otherreason-op'      => 'outro motivo/motivo adicional',
'protect-dropdown'            => "*Motivos comuns para proteção
** Vandalismo excessivo
** Inserção excessiva de ''spams''
** Guerra de edições improdutiva
** Página bastante acessada",
'protect-edit-reasonlist'     => 'Editar motivos de proteções',
'protect-expiry-options'      => '2 horas:2 hours,1 dia:1 day,3 dias:3 days,1 semana:1 week,2 semanas:2 weeks,1 mês:1 month,3 meses:3 months,6 meses:6 months,1 ano:1 year,indefinido:infinite', # display1:time1,display2:time2,...
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
'undeletelink'                 => 'restaurar',
'undeletereset'                => 'Limpar',
'undeleteinvert'               => 'Inverter seleção',
'undeletecomment'              => 'Comentário:',
'undeletedarticle'             => 'restaurado "[[$1]]"',
'undeletedrevisions'           => '$1 {{PLURAL:$1|edição restaurada|edições restauradas}}',
'undeletedrevisions-files'     => '$1 {{PLURAL:$2|edição restaurada|edições restauradas}} e $2 {{PLURAL:$2|arquivo restaurado|arquivos restaurados}}',
'undeletedfiles'               => '{{PLURAL:$1|arquivo restaurado|$1 arquivos restaurados}}',
'cannotundelete'               => 'Restauração falhada; alguém talvez já restaurou a página.',
'undeletedpage'                => "<big>'''$1 foi restaurada'''</big>

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
'invert'         => 'Inverter selecção',
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

'sp-contributions-newbies'       => 'Pesquisar apenas nas contribuições de contas recentes',
'sp-contributions-newbies-sub'   => 'Para contas novas',
'sp-contributions-newbies-title' => 'Contribuições de usuários de contas novas',
'sp-contributions-blocklog'      => 'Registro de bloqueios',
'sp-contributions-search'        => 'Pesquisar contribuições',
'sp-contributions-username'      => 'Endereço de IP ou usuário:',
'sp-contributions-submit'        => 'Pesquisar',

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
'ipboptions'                      => '2 horas:2 hours,1 dia:1 day,3 dias:3 days,1 semana:1 week,2 semanas:2 weeks,1 mês:1 month,3 meses:3 months,6 meses:6 months,1 ano:1 year,indefinido:infinite', # display1:time1,display2:time2,...
'ipbotheroption'                  => 'outro',
'ipbotherreason'                  => 'Outro motivo/motivo adicional:',
'ipbhidename'                     => 'Ocultar usuário/IP do registro de bloqueios, lista de bloqueios e lista de usuários',
'ipbwatchuser'                    => 'Vigiar as páginas de usuários e de discussão deste usuário',
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
'blocklistline'                   => '$1, $2 bloqueou $3 ($4)',
'infiniteblock'                   => 'infinito',
'expiringblock'                   => 'expira em $1',
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
'blocklog-fulllog'                => 'Registro completo de bloqueios',
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
'range_block_disabled'            => 'A funcionalidade de bloquear gamas de IPs encontra-se desativada.',
'ipb_expiry_invalid'              => 'Tempo de expiração inválido.',
'ipb_expiry_temp'                 => 'Bloqueios com nome de usuário ocultado devem ser permanentes.',
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
'cant-block-while-blocked'        => 'Você não pode bloquear outros usuários enquanto estiver bloqueado.',

# Developer tools
'lockdb'              => 'Trancar banco de dados',
'unlockdb'            => 'Destrancar banco de dados',
'lockdbtext'          => 'Trancar o banco de dados suspenderá a habilidade de todos os usuários de editarem páginas, mudarem suas preferências, lista de artigos vigiados e outras coisas que requerem mudanças na base de dados.<br />
Por favor, confirme que você realmente pretende fazer isso e que vai destrancar a base de dados quando a manutenção estiver concluída.',
'unlockdbtext'        => 'Desbloquear a base de dados vai restaurar a habilidade de todos os usuários de editarem páginas, mudarem suas preferências, alterarem suas listas de artigos vigiados e outras coisas que requerem mudanças na base de dados. Por favor, confirme que realmente pretende fazer isso.',
'lockconfirm'         => 'Sim, eu realmente desejo bloquear a base de dados.',
'unlockconfirm'       => 'Sim, eu realmente desejo desbloquear a base de dados.',
'lockbtn'             => 'Bloquear base de dados',
'unlockbtn'           => 'Desbloquear base de dados',
'locknoconfirm'       => 'Você não selecionou a caixa de confirmação.',
'lockdbsuccesssub'    => 'Bloqueio bem sucedido',
'unlockdbsuccesssub'  => 'Desbloqueio bem sucedido',
'lockdbsuccesstext'   => 'A base de dados da {{SITENAME}} foi bloqueada.
<br />Lembre-se de remover o bloqueio após a manutenção.',
'unlockdbsuccesstext' => 'A base de dados foi desbloqueada.',
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
'movepage-moved'               => '<big>\'\'\'"$1" foi movida para "$2"\'\'\'</big>', # The two titles are passed in plain text as $3 and $4 to allow additional goodies in the message.
'movepage-moved-redirect'      => 'Um redirecionamento foi criado.',
'movepage-moved-noredirect'    => 'A criação de um redirecionamento foi suprimida.',
'articleexists'                => 'Uma página com este título já existe, ou o título que escolheu é inválido.
Por favor, escolha outro nome.',
'cantmove-titleprotected'      => 'Você não pode mover uma página para tal denominação uma vez que o novo título se encontra protegido contra criação',
'talkexists'                   => "'''A página em si foi movida com sucesso. No entanto, a página de discussão não foi movida, uma vez que já existia uma com este título. Por favor, mescle-as manualmente.'''",
'movedto'                      => 'movido para',
'movetalk'                     => 'Mover também a página de discussão associada.',
'move-subpages'                => 'Mover subpáginas (até $1), se aplicável',
'move-talk-subpages'           => 'Mover subpáginas da página de discussão (até $1), se aplicável',
'movepage-page-exists'         => 'A página $1 já existe e não pode ser substituída.',
'movepage-page-moved'          => 'A página $1 foi movida para $2',
'movepage-page-unmoved'        => 'A página $1 não pôde ser movida para $2.',
'movepage-max-pages'           => 'O limite de $1 {{PLURAL:$1|página movida|páginas movidas}} foi atingido; não será possível mover mais páginas de forma automática.',
'1movedto2'                    => '[[$1]] foi movido para [[$2]]',
'1movedto2_redir'              => '[[$1]] foi movido para [[$2]] sob redirecionamento',
'move-redirect-suppressed'     => 'redirecionamento suprimido',
'movelogpage'                  => 'Registro de movimento',
'movelogpagetext'              => 'Abaixo encontra-se uma lista de páginas movidas.',
'movesubpage'                  => '{{PLURAL:$1|Sub-página|Sub-páginas}}',
'movesubpagetext'              => 'Esta página tem $1 {{PLURAL:$1|sub-página mostrada|sub-páginas mostradas}} abaixo.',
'movenosubpage'                => 'Esta página não tem sub-páginas.',
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

# Export
'export'            => 'Exportação de páginas',
'exporttext'        => 'Você pode exportar o texto e o histórico de edições de uma página em particular para um arquivo XML. Poderá então importar esse conteúdo noutra wiki que utilize o software MediaWiki através da [[Special:Import|página de importações]].

Para exportar páginas, introduza os títulos na caixa de texto abaixo (um título por linha) e selecione se deseja todas as versões, com as linhas de histórico de edições, ou apenas a edição atual e informações apenas sobre a mais recente das edições.

Se desejar, pode utilizar uma ligação (por exemplo, [[{{#Special:Export}}/{{MediaWiki:Mainpage}}]] para a [[{{MediaWiki:Mainpage}}]]).',
'exportcuronly'     => 'Incluir apenas a revisão atual, não o histórico inteiro',
'exportnohistory'   => "----
'''Nota:''' a exportação do histórico completo das páginas através deste formulário foi desactivada devido a motivos de performance.",
'export-submit'     => 'Exportar',
'export-addcattext' => 'Adicionar à listagem páginas da categoria:',
'export-addcat'     => 'Adicionar',
'export-download'   => 'Oferecer para salvar como um arquivo',
'export-templates'  => 'Incluir predefinições',
'export-pagelinks'  => 'Incluir páginas ligadas até uma profundidade de:',

# Namespace 8 related
'allmessages'               => 'Todas as mensagens de sistema',
'allmessagesname'           => 'Nome',
'allmessagesdefault'        => 'Texto padrão',
'allmessagescurrent'        => 'Texto atual',
'allmessagestext'           => 'Esta é uma lista de todas mensagens de sistema disponíveis no espaço nominal {{ns:mediawiki}}.
Acesse [http://www.mediawiki.org/wiki/Localisation MediaWiki Localisation] e [http://translatewiki.net translatewiki.net] caso deseje contribuir para traduções do MediaWiki feitas para uso geral.',
'allmessagesnotsupportedDB' => "Esta página não pode ser utilizada, uma vez que '''\$wgUseDatabaseMessages''' foi desativado.",
'allmessagesfilter'         => 'Filtro de nome de mensagem:',
'allmessagesmodified'       => 'Mostrar apenas modificados',

# Thumbnails
'thumbnail-more'           => 'Ampliar',
'filemissing'              => 'arquivo não encontrado',
'thumbnail_error'          => 'Erro ao criar miniatura: $1',
'djvu_page_error'          => 'página DjVu inacessível',
'djvu_no_xml'              => 'Não foi possível acessar o XML do arquivo DjVU',
'thumbnail_invalid_params' => 'Parâmetros de miniatura inválidos',
'thumbnail_dest_directory' => 'Não foi possível criar o diretório de destino',

# Special:Import
'import'                     => 'Importar páginas',
'importinterwiki'            => 'Importação transwiki',
'import-interwiki-text'      => 'Selecione uma wiki e um título de página a importar.
As datas das edições e os seus editores serão mantidos.
Todas as acções de importação transwiki são registradas no [[Special:Log/import|Registro de importações]].',
'import-interwiki-source'    => 'Wiki/página fonte:',
'import-interwiki-history'   => 'Copiar todas as edições desta página',
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
'importnosources'            => 'Não foram definidas fontes de importação transwiki e o carregamento direto de históricos encontra-se desactivado.',
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
'tooltip-pt-userpage'             => 'Sua página de utilizador',
'tooltip-pt-anonuserpage'         => 'A página de usuário para o ip que está a utilizar para editar',
'tooltip-pt-mytalk'               => 'Sua página de discussão',
'tooltip-pt-anontalk'             => 'Discussão sobre edições deste endereço de ip',
'tooltip-pt-preferences'          => 'Minhas preferências',
'tooltip-pt-watchlist'            => 'Lista de artigos vigiados.',
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
'tooltip-ca-delete'               => 'Apagar esta página',
'tooltip-ca-undelete'             => 'Restaurar edições feitas a esta página antes da eliminação',
'tooltip-ca-move'                 => 'Mover esta página',
'tooltip-ca-watch'                => 'Adicionar esta página aos artigos vigiados',
'tooltip-ca-unwatch'              => 'Remover esta página dos artigos vigiados',
'tooltip-search'                  => 'Pesquisar nesta wiki',
'tooltip-search-go'               => 'Ir a uma página com este exato nome, caso exista',
'tooltip-search-fulltext'         => 'Procurar por páginas contendo este texto',
'tooltip-p-logo'                  => 'Página principal',
'tooltip-n-mainpage'              => 'Visitar a página principal',
'tooltip-n-portal'                => 'Sobre o projeto',
'tooltip-n-currentevents'         => 'Informação temática sobre eventos atuais',
'tooltip-n-recentchanges'         => 'A lista de mudanças recentes nesta wiki.',
'tooltip-n-randompage'            => 'Carregar página aleatória',
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
'tooltip-watch'                   => 'Adicionar esta página à sua lista de artigos vigiados',
'tooltip-recreate'                => 'Recriar a página apesar de ter sido eliminada',
'tooltip-upload'                  => 'Iniciar o upload',
'tooltip-rollback'                => '"{{int:rollbacklink}}" reverte, com um só clique, as edições do último editor desta página.',
'tooltip-undo'                    => '"{{int:editundo}}" reverte esta edição exibindo a caixa de edição no modo de previsão, permitindo alterações adicionais e o uso do sumário de edição para justificativas.',

# Stylesheets
'common.css'   => '/** o código CSS colocado aqui será aplicado a todos os temas */',
'monobook.css' => '/* o código CSS colocado aqui terá efeito nos usuários do tema Monobook */',

# Scripts
'common.js'   => '/* Códigos javascript aqui colocados serão carregados por todos aqueles que acessarem alguma página deste wiki */',
'monobook.js' => '/* Qualquer JavaScript aqui colocado afetará os usuários do skin MonoBook */',

# Metadata
'nodublincore'      => 'Os metadados RDF para Dublin Core estão desabilitados neste servidor.',
'nocreativecommons' => 'Os metadados RDF para Creative Commons estão desabilitados neste servidor.',
'notacceptable'     => 'O servidor não pode fornecer os dados em um formato que o seu cliente possa ler.',

# Attribution
'anonymous'        => '{{PLURAL:$1|Usuário anônimo|Usuários anônimos}} da {{SITENAME}}',
'siteuser'         => '{{SITENAME}} usuário $1',
'lastmodifiedatby' => 'Esta página foi modificada pela última vez a $2, $1 por $3.', # $1 date, $2 time, $3 user
'othercontribs'    => 'Baseado no trabalho de $1.',
'others'           => 'outros',
'siteusers'        => '{{PLURAL:$2|um usuário|$2 usuários}} da {{SITENAME}}: $1',
'creditspage'      => 'Créditos da página',
'nocredits'        => 'Não há informação disponível sobre os créditos desta página.',

# Spam protection
'spamprotectiontitle' => 'Filtro de proteção contra spam',
'spamprotectiontext'  => "A página que deseja salvar foi bloqueada pelo filtro de spam.
Tal bloqueio foi provavelmente causado por uma ligação para um ''website'' externo que conste na lista negra.",
'spamprotectionmatch' => 'O seguinte texto ativou o filtro de spam: $1',
'spambot_username'    => 'MediaWiki limpeza de spam',
'spam_reverting'      => 'Revertendo para a última versão não contendo hiperligações para $1',
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

# Patrolling
'markaspatrolleddiff'                 => 'Marcar como verificado',
'markaspatrolledtext'                 => 'Marcar este artigo como verificado',
'markedaspatrolled'                   => 'Marcado como verificado',
'markedaspatrolledtext'               => 'A revisão selecionada foi marcada como verificada.',
'rcpatroldisabled'                    => 'Edições verificadas nas Mudanças Recentes desactivadas',
'rcpatroldisabledtext'                => 'A funcionalidade de Edições verificadas nas Mudanças Recentes está atualmente desactivada.',
'markedaspatrollederror'              => 'Não é possível marcar como verificado',
'markedaspatrollederrortext'          => 'Você precisa de especificar uma revisão para poder marcar como verificado.',
'markedaspatrollederror-noautopatrol' => 'Você não está autorizado a marcar suas próprias edições como edições patrulhadas.',

# Patrol log
'patrol-log-page'      => 'Registro de edições patrulhadas',
'patrol-log-header'    => 'Este é um registro de edições patrulhadas.',
'patrol-log-line'      => 'marcou a edição $1 de $2 como uma edição patrulhada $3',
'patrol-log-auto'      => 'automaticamente',
'patrol-log-diff'      => 'edição $1',
'log-show-hide-patrol' => '$1 registro de edições patrulhadas',

# Image deletion
'deletedrevision'                 => 'Apagada a versão antiga $1',
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

# Visual comparison
'visual-comparison' => 'Comparação visual',

# Media information
'mediawarning'         => "'''Aviso''': Este arquivo pode conter código malicioso. Ao executar, o seu sistema poderá estar comprometido.<hr />",
'imagemaxsize'         => 'Limitar imagens nas páginas de descrição a:',
'thumbsize'            => 'Tamanho de miniaturas:',
'widthheightpage'      => '$1×$2, $3 {{PLURAL:$3|página|páginas}}',
'file-info'            => '(tamanho: $1, tipo MIME: $2)',
'file-info-size'       => '($1 × $2 pixels, tamanho: $3, tipo MIME: $4)',
'file-nohires'         => '<small>Sem resolução maior disponível.</small>',
'svg-long-desc'        => '(arquivo SVG, de $1 × $2 pixels, tamanho: $3)',
'show-big-image'       => 'Resolução completa',
'show-big-image-thumb' => '<small>Tamanho desta previsão: $1 × $2 pixels</small>',

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
'sp-newimages-showfrom' => 'Mostrar novos arquivos a partir de $2, $1',

# Bad image list
'bad_image_list' => 'O formato é o seguinte:

Só itens da lista (linhas começando com *) são considerados. O primeiro link em uma linha deve ser um link para uma má imagem.
Qualquer link posterior na mesma linha são consideradas como exceções, ou seja, artigos onde a imagem pode ficar como linha.',

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
* focallength', # Do not translate list items

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
'exif-gpsdifferential'             => 'Correção do diferencial do GPS',

# EXIF attributes
'exif-compression-1' => 'Descomprimido',

'exif-unknowndate' => 'Data desconhecida',

'exif-orientation-1' => 'Normal', # 0th row: top; 0th column: left
'exif-orientation-2' => 'Espelhamento horizontal', # 0th row: top; 0th column: right
'exif-orientation-3' => 'Rotacionado em 180°', # 0th row: bottom; 0th column: right
'exif-orientation-4' => 'Espelhamento vertical', # 0th row: bottom; 0th column: left
'exif-orientation-5' => 'Rotacionado em 90º em sentido anti-horário e espelhado verticalmente', # 0th row: left; 0th column: top
'exif-orientation-6' => 'Rotacionado em 90° no sentido horário', # 0th row: right; 0th column: top
'exif-orientation-7' => 'Rotacionado em 90° no sentido horário e espelhado verticalmente', # 0th row: right; 0th column: bottom
'exif-orientation-8' => 'Rotacionado 90° no sentido anti-horário', # 0th row: left; 0th column: bottom

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

# Pseudotags used for GPSSpeedRef and GPSDestDistanceRef
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
'confirmemail_body'        => 'Alguém, provavelmente você com o endereço de IP $1,
registrou uma conta "$2" com este endereço de e-mail em {{SITENAME}}.

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
'watchlistedit-numitems'       => 'A sua lista de vigiados possui {{PLURAL:$1|um título|$1 títulos}}, além das respectivas páginas de discussão.',
'watchlistedit-noitems'        => 'A sua lista de vigiados não possui títulos.',
'watchlistedit-normal-title'   => 'Editar lista de vigiados',
'watchlistedit-normal-legend'  => 'Remover títulos da lista de vigiados',
'watchlistedit-normal-explain' => 'Os títulos de sua lista de vigiados são exibidos a seguir.
Para remover um título clique no box ao lado do mesmo e no botão Remover Títulos. Você também pode [[Special:Watchlist/raw|editar a lista crua]].',
'watchlistedit-normal-submit'  => 'Remover Títulos',
'watchlistedit-normal-done'    => '{{PLURAL:$1|um título foi removido|$1 títulos foram removidos}} de sua lista de vigiados:',
'watchlistedit-raw-title'      => 'Edição crua dos vigiados',
'watchlistedit-raw-legend'     => 'Edição crua dos vigiados',
'watchlistedit-raw-explain'    => 'Os títulos de sua lista de vigiados são exibidos a seguir e podem ser adicionados ou removidos ao se editar a lista, mantendo-se um por linha.
Ao terminar, clique no botão correspondente para atualizar.
Você também pode [[Special:Watchlist/edit|editar a lista da forma convencional]].',
'watchlistedit-raw-titles'     => 'Títulos:',
'watchlistedit-raw-submit'     => 'Atualizar a lista de vigiados',
'watchlistedit-raw-done'       => 'Sua lista de vigiados foi atualizada.',
'watchlistedit-raw-added'      => '{{PLURAL:$1|Foi adicionado um título|Foram adicionados $1 títulos}}:',
'watchlistedit-raw-removed'    => '{{PLURAL:$1|Foi removido um título|Foram removidos $1 títulos}}:',

# Watchlist editing tools
'watchlisttools-view' => 'Ver alterações relevantes',
'watchlisttools-edit' => 'Ver e editar a lista de vigiados',
'watchlisttools-raw'  => 'Edição crua dos vigiados',

# Core parser functions
'unknown_extension_tag' => '"$1" é uma tag de extensão desconhecida',
'duplicate-defaultsort' => 'Aviso: A chave de ordenação padrão "$2" sobrepõe-se à anterior chave de ordenação padrão "$1".',

# Special:Version
'version'                          => 'Versão', # Not used as normal message but as header for the special page itself
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
'version-version'                  => 'Versão',
'version-license'                  => 'Licença',
'version-software'                 => 'Software instalado',
'version-software-product'         => 'Produto',
'version-software-version'         => 'Versão',

# Special:FilePath
'filepath'         => 'Diretório do arquivo',
'filepath-page'    => 'arquivo:',
'filepath-submit'  => 'Diretório',
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
* <span class="mw-specialpagerestricted">Páginas especiais restritas.</span>',
'specialpages-group-maintenance' => 'Relatórios de manutenção',
'specialpages-group-other'       => 'Outras páginas especiais',
'specialpages-group-login'       => 'Entrar / registrar-se',
'specialpages-group-changes'     => 'Mudanças e registros recentes',
'specialpages-group-media'       => 'Relatórios de media e carregamentos',
'specialpages-group-users'       => 'usuários e privilégios',
'specialpages-group-highuse'     => 'Páginas muito usadas',
'specialpages-group-pages'       => 'Listas de páginas',
'specialpages-group-pagetools'   => 'Ferramentas de páginas',
'specialpages-group-wiki'        => 'Dados e ferramentas sobre este wiki',
'specialpages-group-redirects'   => 'Páginas especias redirecionadas',
'specialpages-group-spam'        => 'Ferramentas anti-spam',

# Special:BlankPage
'blankpage'              => 'Página em branco',
'intentionallyblankpage' => 'Esta página foi intencionalmente deixada em branco e é usada para medições de performance, etc.',

# External image whitelist
'external_image_whitelist' => "  # Deixe esta linha exatamente como ela é <pre> 
# Coloque uma expressão regular (apenas a parte que vai entre o //) a seguir
# Estes serão casados com as URLs de imagens externas (''hotlinked'')
# Aqueles que corresponderem serão exibidos como imagens, caso contrário, apenas uma ligação para a imagem será mostrada
# As linhas que começam com # são tratadas como comentários

# Coloque todos os fragmentos de ''regex'' acima dessa linha. Deixe esta linha exatamente como ela é</pre>",

# Special:Tags
'tags'                    => 'Etiquetas de modificação válidas',
'tag-filter'              => 'Filtro de [[Special:Tags|etiquetas]]:',
'tag-filter-submit'       => 'Filtrar',
'tags-title'              => 'Etiquetas',
'tags-intro'              => 'Esta página lista as etiquetas com que o software poderá marcar uma edição, e o seu significado.',
'tags-tag'                => 'Nome interno da etiqueta',
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

);
